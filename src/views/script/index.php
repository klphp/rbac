<?php
use yii\helpers\Html;
use yii\helpers\Html as Helper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel klphp\rbac\models\AuthRuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '规则脚本列表');
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box box-primary">
    <div class="box-body">
    <p>
        <?= Helper::a(Yii::t('app', '新建规则脚本'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<table class="table table-bordered">
    <tr>
        <td class="col-md-2">标识</td>
        <td>描述</td>
        <td class="col-md-1 text-center">操作</td>
    </tr>
    <?php foreach($rules as $rule): ?>
        <tr>
            <td><?=$rule->name?></td>
            <td><?=$rule->description?></td>
            <td class="text-center">
                <?=Html::a(Html::tag('span','',['class'=>'glyphicon glyphicon-trash']),['delete','id'=>$rule->name],['data-method'=>'post'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

    </div>
</div>
