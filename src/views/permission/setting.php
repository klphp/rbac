<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title=$role->name.' 权限绑定';

$this->params['breadcrumbs'][] = [
    'label'=>'角色列表',
    'url'=>['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $form = ActiveForm::begin(['options' => [
    'enctype' => 'multipart/form-data','class'=>'box-body'
]]); ?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">待绑定权限</h3>
            </div>
            <div class="panel-body">
                <select name="permissions[]" multiple class="form-control" style="height:500px;">
                    <?php if($permissions): ?>
                        <?php foreach($permissions as $permission): ?>
                            <option value="<?=$permission?>"><?=$permission?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-1 text-center" style="padding-top:180px;">
        <?= Html::submitButton('', ['class' =>'fa fa-exchange btn btn-primary','style'=>'margin:10px;']) ?>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">已绑定权限</h3>
            </div>
            <div class="panel-body">
                <select name="rolePermissions[]" multiple class="form-control" style="min-height:500px;">
                    <?php if($roles): ?>
                        <?php foreach($roles as $role): ?>
                            <option value="<?=$role?>"><?=$role?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
