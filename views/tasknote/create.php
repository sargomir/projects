<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\TaskNotes */

$this->title = Yii::t('app', 'Create Task Notes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Task Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-notes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
