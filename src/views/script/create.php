<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model klphp\rbac\models\AuthRule */

$this->title = Yii::t('app', '新建规则脚本');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '规则脚本列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-rule-create">

    <?= $this->render('_form', [
        'model' => $model,
        'rules'=>$rules,
        'return' => $return
    ]) ?>

</div>
