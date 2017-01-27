<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Budget */

$this->title = Module::t('app', 'Create budget');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Budgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-create">

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>