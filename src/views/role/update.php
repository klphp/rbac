<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthItem */

$this->title = '编辑角色：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '角色列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'return' => $return
    ]) ?>

</div>
