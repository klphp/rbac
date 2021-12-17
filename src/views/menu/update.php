<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\Menu */

$this->title = '编辑菜单: ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label'=>'权限管理',
    'url'=>'/rbac'
];
$this->params['breadcrumbs'][] = ['label' => '菜单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '编辑'.$model->name];
?>
<div class="menu-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
