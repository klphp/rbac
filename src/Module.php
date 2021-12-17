<?php
/**
 * rbac 管理模块
 * User: www
 * Date: 19-8-7
 * Time: 上午7:24
 */
namespace klphp\rbac;

class Module extends \yii\base\Module
{

    public $rule=false;
    public $userSearchModel=false;
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'klphp\rbac\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
        $this->layout='main';
        \Yii::$container->set('klphp\rbac\userSearch',$this->userSearchModel);
    }
}
