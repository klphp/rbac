<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-body">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'options' => ['width' => 30]
                ],
                [
                    'attribute' => 'id',
                    'contentOptions' => ['class' => 'text-left col-md-3'],
                    'headerOptions' => ['class' => 'text-left'],
                ],
                [
                    'attribute' => 'username',
                    'contentOptions' => ['class' => 'col-md-1'],
                    'headerOptions' => ['class' => 'col-md-1'],
                ],

                [
                    'attribute' => 'phone',
                    'contentOptions' => ['class' => 'text-left'],
                    'headerOptions' => ['class' => 'text-left'],
                ],

                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                    'contentOptions' => ['class' => 'text-center'],
                    'headerOptions' => ['class' => 'text-center', 'width' => 150],
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($data) use ($searchModel) {
                        return $searchModel::STATUS_ITEMS[$data->status];
                    },
                    'contentOptions' => ['class' => 'text-center col-md-1'],
                    'headerOptions' => ['class' => 'text-center col-md-1',],

                    'filter' => $searchModel::STATUS_ITEMS,
                ],
                [
                    'header' => '绑定用户组',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a('分配用户组', '#', [
                            'id' => 'create',
                            'data-toggle' => 'modal',
                            'data-target' => '#create-modal',
                            'class' => 'modal-btn btn btn-success btn-xs',
                            'data-id' => $model->id,
                        ]);
                    },
                    'contentOptions' => ['class' => 'text-center'],
                    'headerOptions' => ['class' => 'text-center col-md-1'],
                ],

            ],
            'layout' => '<p class="text-right">{summary}</p>{items}<div class="text-right">{pager}</div>',
            'pager' => [
                //'ptions'=>['class'=>'hidden'], //关闭自带分页
                'firstPageLabel' => "首页",
                'prevPageLabel' => '上页',
                'nextPageLabel' => '下页',
                'lastPageLabel' => '末页',
            ]
        ]); ?>

    </div>
</div>
<?php
Modal::begin([
    'id' => 'create-modal',
    'header' => '角色绑定',
    'footer' => '<button type="submit" class="send-btn btn btn-primary">确定</button>',
]);
Modal::end();
?>
<?php \common\widgets\JsBlock::begin(); ?>
<script>
    <?php $requestUrl = \yii\helpers\Url::toRoute('modal'); ?>
    $('.modal-btn').on('click', function () {
        $.get('<?= $requestUrl ?>', { id: $(this).attr('data-id') },
            function (data) {
                $('.modal-body').html(data);
            }
        );
    });
    $('.send-btn').on('click',function(e){
        var form = $('#modal-form');
        var form_data = new FormData($('form')[0]);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                console.log(data);
                $('#create-modal').modal("hide");
                return false;
            }
       });
       return false;
    });

</script>
<?php \common\widgets\JsBlock::end() ?>
