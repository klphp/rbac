<?php
namespace klphp\rbac\commands;

use klphp\rbac\components\Apps;
use klphp\rbac\rules\IsAuthor;
use Yii;
use yii\console\Controller;
use klphp\rbac\components\Routers;

class RbacController extends Controller{

    const ADMINISTRATOR='超级管理员';

    /**
     * 规则权限绑定列表
     * @var array
     */
    public $rulePermissions=[
        'data/update'=>IsAuthor::NAME,
        'data/delete'=>IsAuthor::NAME,
        'category/update'=>IsAuthor::NAME,
        'category/delete'=>IsAuthor::NAME,
        'node-group/update'=>IsAuthor::NAME,
        'node-group/delete'=>IsAuthor::NAME,
        'node/update'=>IsAuthor::NAME,
        'node/delete'=>IsAuthor::NAME,
        'attrs-group/update'=>IsAuthor::NAME,
        'attrs-group/delete'=>IsAuthor::NAME,
        'attrs/update'=>IsAuthor::NAME,
        'attrs/delete'=>IsAuthor::NAME,
    ];

    public $rolePermissions=[
        '员工'=>[
            'data',
            'site'
        ],
        '管理员'=>[
            'ad',
            'attrs',
            'category',
            'comment',
            'data',
            'link',
            'node-group',
            'node',
            'plugin',
            'search',
            'rbac/user/index'
        ],
        '超级管理员'=>'*'
    ];

    /**
     * 默认规则
     * @var array
     */
    public $rules=[
        IsAuthor::class,
    ];

    /**
     * 默认角色（角色）
     * @var array
     */
    public $roles=[
        '超级管理员',
        '管理员',
        '员工',
        '会员'
    ];

    /**
     * 初始化RBAC数据
     */
    public function actionInit(){

        //清除RBAC数据
        $auth=Yii::$app->getAuthManager();
        $auth->removeAllPermissions();
        $auth->removeAllRules();
        $auth->removeAllRoles();

        //重建规则
        foreach ($this->rules as $rule) {
            $auth->add(new $rule);
        }

        $routerList=static::getRouters();

        //重建权限
        foreach ($routerList as $router){
            $routerObj=$auth->createPermission($router);
            foreach ($this->rulePermissions as $permission => $rule){
                if($router == $permission){
                    $routerObj->ruleName=$rule;
                }
            }
            $auth->add($routerObj);
        }

        //重建角色
        foreach ($this->roles as $role){
            $roleObj = $auth->createRole($role);
            $auth->add($roleObj);

            if(isset($this->rolePermissions[$role])){

                //超级管理员导入所有路由列表
                if($this->rolePermissions[$role]=='*'){
                    foreach($routerList as $router){
                        $permissionObj=$auth->getPermission($router);
                        if($permissionObj){
                            try{
                                $auth->addChild($roleObj,$permissionObj);
                            }catch (\Exception $e){

                            }
                        }
                    }
                }

                //非超管导入指定路由列表
                foreach($this->rolePermissions[$role] as $item){

                    if(strpos($item,'/')!==false){
                        $auth->addChild($roleObj,$auth->getPermission($item));
                    }else{
                        $len=strlen($item);
                        foreach($routerList as $router){
                            if(substr($router,0,$len)==$item){
                                $permissionObj=$auth->getPermission($router);
                                //var_dump($roleObj);
                                if($permissionObj){
                                    try{
                                        $auth->addChild($roleObj,$permissionObj);
                                    }catch (\Exception $e){

                                    }
                                }
                            }
                        }
                    }

                }


            }
        }


        //检测admin是否了在，如果存在直接分组为超级管理员
        $userClass=Yii::$app->user->identityClass;
        $user=$userClass::find()->where(['username'=>'admin'])->one();
        if($user){
            $user->role=$userClass::ROLE_ADMINISTRATOR;
            var_dump($user->save());
        }

    }

    /**
     * 后台全部规则列表
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    private function getRouters(){
        $routerObj=new Routers();
        $app=new Apps();
        $routerObj->app=$app->createApp('backend');
        return $routerObj->routers;
    }

    /**
     * 清空RBAC规则
     */
    public function actionClear(){
        //清除RBAC数据
        $auth=Yii::$app->getAuthManager();
        $auth->removeAllPermissions();
        $auth->removeAllRules();
        $auth->removeAllRoles();
    }

}