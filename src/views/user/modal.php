<?php
/**
 *
 * User: kl
 * DateTime: 2021/11/16 11:43 下午
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form=ActiveForm::begin([
    'id'=>'modal-form',
    'options'=>['class'=>'row'],
    'action' => ['modal'],
    'method' => 'post',
]);
?>
<div class="col-md-12">
<?= $form->field($model,'id',['options'=>['style'=>'display:none']])->hiddenInput(['value'=>$model->id])->label('') ?>
<?= $form->field($model,'role')->dropDownList($roleKeys,['prompt'=>'选择用户组'])->label('')  ?>
</div>
<?php
ActiveForm::end();
?>