<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\Menu */

$this->title = '新建菜单';
$this->params['breadcrumbs'][] = [
    'label'=>'权限管理',
    'url'=>'/rbac'
];
$this->params['breadcrumbs'][] = ['label' => '菜单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
