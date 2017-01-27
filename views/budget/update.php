<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Budget */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Budget',
]) . ' ' . $model->budget_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Budgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->budget_id, 'url' => ['view', 'id' => $model->budget_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="budget-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>