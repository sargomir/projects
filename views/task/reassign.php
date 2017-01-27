<?php

use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthProfile;

$this->title = Module::t('app', 'Reassign task {task_id}', ['task_id' => "№{$model->task_id}"]);
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Hide header in main layout
$hidden = Yii::$app->request->isAjax ? '' : 'hidden';
?>

<div class="modal-header <?= $hidden ?>">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h2><?= Module::t('app', 'Reassign task {task_id}', ['task_id' => "<div class='label label-default'>№{$model->task_id}</div>"]) ?></h2>
</div>

<div class="modal-body">
<?php
    $form = ActiveForm::begin();
    
    echo $form->field($model, 'worker_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(AuthProfile::find()
            ->joinWith(['assignments'])
            ->where(['item_name' => new Expression('"worker"'), 'lead_id' => $model->worker_id])
            ->orderBy(['lastname'=>SORT_ASC])
            ->all(), 'user_id', 'username'),
        'options' => ['placeholder' => Module::t('app', 'Select ...')],
    ]);
    
    echo Html::submitButton (Module::t('app', 'Update'), ['class' => 'btn btn-primary']);
    
    ActiveForm::end();
?>
</div>