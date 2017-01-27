<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\ProjectPartsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-parts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'part_id') ?>

    <?= $form->field($model, 'parent_part_id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'part') ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>