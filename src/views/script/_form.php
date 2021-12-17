<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthRule */
/* @var $form yii\widgets\ActiveForm */
?>
    <style>
        label{font-weight: 1;line-height:30px;}
        .form-group .col-md-3{text-align:right}
    </style>
    <div class="box box-primary">
    <div class="box-body">
    <div class="auth-rule-form">
<?php $form = ActiveForm::begin(['options' => [
    'enctype' => 'multipart/form-data','class'=>'box-body'
]]); ?>

<?= $form->field($model, 'name')->dropDownList($rules,['prompt'=>'请选择','maxlength' => true]) ?>
    <div class="box-footer">
        <?= Html::hiddenInput('return',$return) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','return'),'javascript:history.go(-1);', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
    </div>
    </div>
