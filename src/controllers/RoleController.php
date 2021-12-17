<?php

namespace klphp\rbac\controllers;

use Yii;
use klphp\rbac\models\AuthItem;
use klphp\rbac\models\AuthItemRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use klphp\rbac\components\Hexception;
/**
 * 角色管理
 */
class RoleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function getRules(){
        return array_keys(Yii::$app->authManager->getRules());
    }

    /**
     * 权限列表
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemRoleSearch();
        $searchModel->type=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 创建权限
     */
    public function actionCreate()
    {

        $model = new AuthItem();
        $model->loadDefaultValues();
        $return = Yii::$app->request->referrer;
        if (Yii::$app->request->isPost) {
            try{
                $auth=Yii::$app->authManager;
                $data=Yii::$app->request->post($model->formName(),[]);
                $Role = $auth->createRole($data['name']);
                $Role->description = $data['description'];
                $Role->ruleName = $data['rule_name']===''?null:static::getRules()[$data['rule_name']];
                $Role->data = $data['data'];
                if($auth->add($Role)){
                    return $this->redirect(Yii::$app->request->post('return'));
                }
            }catch (\Exception $e){
                Hexception::Alert($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'return' => $return
        ]);
    }

    /**
     * 克隆
     */
    public function actionCopy($id){

        $model = new AuthItem();
        $model->loadDefaultValues();

        $auth=Yii::$app->authManager;
        $return = Yii::$app->request->referrer;
        if (Yii::$app->request->isPost) {
            try{
                $data=Yii::$app->request->post($model->formName(),[]);
                $Role = $auth->createRole($data['name']);
                $Role->description = $data['description'];
                $Role->ruleName = $data['rule_name']===''?null:static::getRules()[$data['rule_name']];
                $Role->data = $data['data'];
                if($auth->add($Role)){
                    $permissions=$auth->getPermissionsByRole($id);
                    if($permissions){
                        $permissions=array_keys($permissions);
                        while($permissions){
                            $permissionName=array_pop($permissions);
                            $child=$auth->getPermission($permissionName);
                            $auth->addChild($Role, $child);
                        }
                    }

                    return $this->redirect(Yii::$app->request->post('return'));
                }
            }catch (\Exception $e){
                Hexception::Alert($e->getMessage());
            }
        }

        return $this->render('copy',[
            'id'=>$id,
            'model' => $model,
            'return' => $return
        ]);
    }

    /**
     * 更新权限
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $return = Yii::$app->request->referrer;
        if ($model->load(Yii::$app->request->post())) {
            try{
                $auth=Yii::$app->authManager;
                $data=Yii::$app->request->post($model->formName(),[]);

                $Role = $auth->getRole($id);
                $Role->description = $data['description'];
                $Role->ruleName = $data['rule_name']===''?null:static::getRules()[$data['rule_name']];
                $Role->data = $data['data'];
                if($auth->update($id,$Role)){
                    return $this->redirect(Yii::$app->request->post('return'));
                }
            }catch (\Exception $e){
                Hexception::Alert($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'return' => $return
        ]);
    }

    /**
     * 删除权限
     */
    public function actionDelete($id)
    {
        try{
            $auth=Yii::$app->authManager;
            $Role = $auth->getRole($id);
            if($auth->remove($Role)){
                return $this->redirect(Yii::$app->request->referrer);
            }
        }catch (\Exception $e){
            Hexception::Alert($e->getMessage());
        }
    }

    /**
     * 权限绑定
     * @param $id
     */
    public function actionSetting($id){

        $auth=Yii::$app->authManager;
        $model = new AuthItem();
        //获取角色
        $role=$auth->createRole($id);
        //已绑定权限列表
        $roles=array_keys($auth->getPermissionsByRole($id));
        //权限列表
        $permissions=array_keys($auth->getPermissions());

        if(Yii::$app->request->isPost){

            $permissions=Yii::$app->request->post('permissions',null);
//            pre($permissions); die;
            if($permissions){
                while($permissions){
                    $permissionName=array_pop($permissions);
                    $child=$auth->getPermission($permissionName);
                    $auth->addChild($role, $child);
                }
            }else{
                $rolePermissions=Yii::$app->request->post('rolePermissions',[]);
                while($rolePermissions){
                    $permissionName=array_pop($rolePermissions);
                    $child=$auth->getPermission($permissionName);
                    $auth->removeChild($role, $child);
                }
            }

            return $this->redirect(Yii::$app->request->referrer);

        }

        return $this->render('setting',[
            'role'=>$role,
            'roles'=>$roles,
            'permissions'=>array_diff($permissions,$roles),
            'model'=>$model
        ]);
    }

    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
