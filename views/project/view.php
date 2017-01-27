<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Projects */

$this->title = Module::t('app', 'Project №{project_id}', ['project_id' => $model->project_id]);
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-view">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
    <!--Show crate note form if project exists-->
    <?php
        if (isset($model->project_id)) {
            echo '<div class="panel panel-primary"><div class="panel-heading">
                <h3 class="panel-title">' . Module::t('app', 'Make note') . '</h3>
                </div><div class="panel-body">';
            $note = new app\modules\projects\models\ProjectNote();
            $note->project_id = $model->project_id;
            $note->user_id = \Yii::$app->user->id;
            
            echo $this->render('/projectnote/_form', [
                'model' => $note,
            ]);
            echo '</div></div>';
        }
    ?>
</div>