<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
    <style>
        label{font-weight: 1;line-height:30px;}
        .form-group .col-md-3{text-align:right}
    </style>
    <div class="box box-primary">
    <div class="box-body">
    <div class="auth-item-form">
<?php $form = ActiveForm::begin(['options' => [
    'enctype' => 'multipart/form-data','class'=>'box-body'
]]); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'rule_name')->dropDownList($this->context->getRules(),['value'=>empty($model->ruleName)?'':array_flip($this->context->getRules())[$model->ruleName->name],'prompt'=>'请选择','maxlength' => true]) ?>

<?= $form->field($model, 'data')->textInput(['value'=>$model->isNewRecord?'':unserialize($model->data)]) ?>

    <div class="box-footer">
        <?= Html::hiddenInput('return',$return) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','return'),'javascript:history.go(-1);', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
    </div></div>
