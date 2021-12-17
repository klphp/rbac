<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthItem */

$this->title = '编辑权限：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '权限列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'return' => $return
    ]) ?>

</div>
