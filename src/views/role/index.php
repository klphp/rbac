<?php
use yii\helpers\Html;
use yii\helpers\Html as Helper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel klphp\rbac\models\AuthItemRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '角色列表');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box box-primary">
    <div class="box-body">
<p>
    <?= Helper::a(Yii::t('app', '创建角色'), ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
//        [
//            'class' => 'yii\grid\CheckboxColumn',
//            'options' => ['width' => 30]
//        ],
        [
            'attribute' => 'name',
            'headerOptions' => ['class' => 'text-left col-md-2'],
            'contentOptions' => ['class' => 'text-left']
        ],
        [
            'attribute' => 'description',
            'headerOptions' => ['class' => 'text-left'],
            'contentOptions' => ['class' => 'text-left']
        ],
        [
            'attribute' => 'rule_name',
            'headerOptions' => ['class' => 'text-center col-md-3'],
            'contentOptions' => ['class' => 'text-center']
        ],
        [
            'attribute' => 'created_at',
            'format'=>'datetime',
            'headerOptions' => ['class' => 'text-center col-md-2'],
            'contentOptions' => ['class' => 'text-center']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{copy} {setting} {update} {delete}',
            'buttons' => [
                'copy'=>function($url,$model,$key){
                    return Helper::a(Html::tag('span','',['class'=>'fa fa-copy']),['/rbac/role/copy','id'=>$model->name]);
                },
                'setting'=>function($url,$model,$key){
                    return Helper::a(Html::tag('span','',['class'=>'fa fa-cog']),['setting','id'=>$model->name]);
                },
                'update' => function ($url, $model, $key) {
                    return Helper::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->name]);
                },
                'delete' => function($url, $model){
                    return Helper::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->name], [
                        'class' => '',
                        'data' => [
                            'confirm' => '该操作无法恢复，确定要删除“'.$model->name.'”吗?',
                            'method' => 'post',
                        ],
                    ]);
                }
            ],
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center','width' => 90],
        ],
    ],
    'layout'=> '<p class="text-right">{summary}</p>{items}<div class="text-right">{pager}</div>',
    'pager'=>[
        //'ptions'=>['class'=>'hidden'], //关闭自带分页
        'firstPageLabel'=>"首页",
        'prevPageLabel'=>'上页',
        'nextPageLabel'=>'下页',
        'lastPageLabel'=>'末页',
    ]
]); ?>
    </div>
</div>