<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use klphp\rbac\models\Menu;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
<div class="menu-form">

    <div class="col-md-4">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('菜单名') ?>

        <?= $form->field($model, 'parent')->widget(\kartik\select2\Select2::class, [
            'data' =>Menu::getSelectItems(),
            'options' => ['placeholder' => '选择父级 ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('父级');?>

        <?= $form->field($model, 'route')->textInput(['maxlength' => true])->label('路由') ?>

        <?= $form->field($model, 'order')->textInput()->label('排序') ?>

        <?= $form->field($model, 'data')->textarea()->label('数据') ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
