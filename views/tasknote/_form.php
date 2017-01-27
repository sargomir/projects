<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\TaskNotes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-note-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_id', ['template'=>'{input}'])->hiddenInput() ?>
    <?= $form->field($model, 'user_id', ['template'=>'{input}'])->hiddenInput() ?>

    <div class="row">
        <div class="col-sm-10">
            <?= $form->field($model, 'note', ['template'=>'{input}{error}{hint}'])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                    ['class' => $model->isNewRecord
                        ? 'btn btn-success glyphicon glyphicon-comment'
                        : 'btn btn-primary glyphicon glyphicon-comment'
                    ]
                )
            ?>
        </div>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- Prevent form submit on Enter keypress -->
<?php $this->RegisterJS("$('#{$form->id}').bind('keypress keydown keyup', function(e){
    if(e.keyCode == 13) { e.preventDefault(); }
});"); ?>