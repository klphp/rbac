<?php
/**
 * 前台登陆
 */
namespace klphp\rbac\rules;
use yii\rbac\Rule;
use Yii;

class LoginFrontend extends Rule{

    public $name='LoginFrontend';

    public $description='前台帐号检测';

    public function execute($user, $item, $params)
    {
        $userClass=Yii::$app->user->identityClass;
        $userGroups=[$userClass::ROLE_USER,$userClass::ROLE_VIP];

        pre(Yii::$app->user->identity->attributes); die;
        $userRole=Yii::$app->user->identity->role;
        if (!in_array($userRole,$userGroups)){
            Yii::$app->user->logout();
            return Yii::$app->controller->goHome();
        }

        return true;
    }

}