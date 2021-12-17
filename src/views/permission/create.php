<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthItem */

$this->title = Yii::t('app', '新建规则');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '规则列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'return' => $return
    ]) ?>

</div>
