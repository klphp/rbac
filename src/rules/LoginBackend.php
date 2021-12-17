<?php
/**
 * 后台帐号检测
 */
namespace klphp\rbac\rules;
use yii\rbac\Rule;
use Yii;

class LoginBackend extends Rule{

    public $name='LoginBackend';

    public $description='后台帐号检测';

    private $_user;

    public function execute($userId, $item, $params)
    {
        $userClass=Yii::$app->user->identityClass;
        $roles=[$userClass::ROLE_ADMIN,$userClass::ROLE_ADMINISTRATOR];
        $userRole=Yii::$app->user->identity->role;
        if (!in_array($userRole,$roles)){
            Yii::$app->user->logout();
            return Yii::$app->controller->goHome();
        }
        return true;
    }

}