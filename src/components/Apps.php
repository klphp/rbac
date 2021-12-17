<?php
/**
 * 指定应用实例化
 */
namespace klphp\rbac\components;

use Yii;
use yii\base\Component;

class Apps extends Component{

    public function createApp($appName){
        $appName='@'.$appName;
        $appdir=Yii::getAlias($appName);

        $config = \yii\helpers\ArrayHelper::merge(
            require $appdir . '/../common/config/main.php',
            require $appdir . '/../common/config/main-local.php',
            require $appdir . '/config/main.php',
            require $appdir . '/config/main-local.php'
        );

        return new \yii\web\Application($config);
    }

}