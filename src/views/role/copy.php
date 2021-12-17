<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthItem */

$this->title = Yii::t('app', '克隆角色');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '角色列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form_copy', [
        'id'=>$id,
        'model' => $model,
        'return' => $return
    ]) ?>

</div>
