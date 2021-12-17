<?php
/**
 * 命令行用户管理
 * User: www
 * Date: 18-7-17
 * Time: 上午9:13
 */
namespace klphp\rbac\commands;

use klphp\rbac\models\AdminSignupForm;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class UserController extends Controller{

    /**
     * CLI/RBAC管理帐号注册
     */
    public function actionSignup(){


        $params['role'] = Console::select("用户组：",Yii::$app->user->identityClass::LABEL_ROLES);
        $params['username'] = Console::input("请输入要注册的帐号：");
        $params['email'] = Console::input("请输入要注册的邮箱：");
        $params['password'] = Console::input("请输入要注册的密码：");

        $model = new AdminSignupForm();
        $model->attributes=$params;
        $res=$model->signup();
        if($res===true){
            Console::output('注册成功!');
        }else{
            Console::output($res['message']);
        }
    }

    /**
     * 删除用户
     */
    public function actionDelete()
    {
        $params['id'] = Console::input("请输入要删除的用户ID：");

        if ($params['id'] && is_numeric($params['id'])) {
            $userClass = Yii::$app->user->identityClass;
            $auth = Yii::$app->getAuthManager();

            $model = $userClass::findOne($params['id']);

            if ($model && $model->username != 'admin') {
                $auth->revokeAll($params['id']);
                if ($model->delete()) {
                    Console::output('删除成功!');
                }
            } else {
                Console::output('删除失败，可能是用户不存在!');
            }
        }

    }

}