<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Tasks */

$this->title = Module::t('app', 'Task №{task_id}', ['task_id' => $model->task_id]);
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tasks-view">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <!--Show create note form if model exists-->
    <?php
        if (isset($model->task_id)) {
            $user = Yii::$app->user;
            echo '<div class="panel panel-primary"><div class="panel-heading">
                <h3 class="panel-title">' . Module::t('app', 'Make note') . '</h3>
                </div><div class="panel-body">';
            $note = new app\modules\projects\models\TaskNote();
            $note->task_id = $model->task_id;
            $note->user_id = $user->id;
            echo $this->render('/tasknote/_form', [
                'model' => $note,
            ]);
            echo '</div></div>';
        }
    ?>

</div>