<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel klphp\rbac\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单';
$this->params['breadcrumbs'][] = [
    'label'=>'权限管理',
    'url'=>'/rbac'
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
<div class="menu-index">

    <p>
        <?= Html::a('新建菜单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'attribute'=>'parent',
                'value'=>function($model){
                    if($model->parent){
                        return $model->parent0->name;
                    }
                }
            ],
            'route',
            'order',
            //'data',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $model->path,['target'=>'_blank']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id]);
                    },
                    'delete' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'class' => '',
                            'data' => [
                                'confirm' => '该操作无法恢复，确定要删除吗?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center','width' => 90],
            ],
        ],
    ]); ?>


</div>
</div>
</div>
