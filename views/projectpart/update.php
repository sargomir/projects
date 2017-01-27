<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\ProjectParts */

$this->title = Module::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Project Parts',
]) . ' ' . $model->part_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Project Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->part_id, 'url' => ['view', 'id' => $model->part_id]];
$this->params['breadcrumbs'][] = Module::t('app', 'Update');
?>
<div class="project-parts-update">

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>