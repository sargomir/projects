<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\TaskNotes */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Task Notes',
]) . ' ' . $model->note_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Task Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->note_id, 'url' => ['view', 'id' => $model->note_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="task-notes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
