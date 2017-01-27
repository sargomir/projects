<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\ProjectParts */

$this->title = Module::t('app', 'Create Project Parts');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Project Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-parts-create">

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>