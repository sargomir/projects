<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Users */

$this->title = Module::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Users',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('app', 'Update');
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>