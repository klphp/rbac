<?php
/**
 * 权限管理首页
 * User: kl
 * DateTime: 2021/11/16 2:41 上午
 */
namespace klphp\rbac\controllers;

use yii\web\Controller;

class DefaultController extends Controller{
    public function actionIndex(){
        return $this->render('index');
    }
}