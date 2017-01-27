<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Users */

$this->title = Module::t('app', 'Create user');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-create">

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>