<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'authKey') ?>

    <?= $form->field($model, 'accessToken') ?>

    <?php // echo $form->field($model, 'group') ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>