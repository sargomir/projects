<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\ProjectParts */

$this->title = $model->part_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Project Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-parts-view">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>