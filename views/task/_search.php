<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use kartik\date\DatePicker;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\Project;
use app\modules\projects\models\ProjectPart;
use app\modules\projects\models\AuthUser;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\TasksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= // Projects downdownlist
        $form->field($model, 'project_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Project::find()->orderBy('project')->all(), 'project_id', 'project'),
            'options' => ['placeholder' => Module::t('app', 'Select...')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])
    ?>

    <?= // ProjectParts downdownlist
        $form->field($model, 'project_part_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(ProjectPart::find()->orderBy('code')->all(), 'part_id', 'part'),
            'options' => ['placeholder' => Module::t('app', 'Select...')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])
    ?>            

    <?= $form->field($model, 'task') ?>

    <?= // Workers downdownlist. Chech_task is available for project_lead, technical_lead ans project_manager roles
        $workers_dropdownlist = Yii::$app->user->can('check_task')
        ? $form->field($model, 'worker_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(AuthUser::find()->orderBy('username')->all(), 'id', 'username'),
                'options' => ['placeholder' => Module::t('app', 'Select...')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])
        : null;
    ?>
    
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'estimated') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'elapsed') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">    
            <?php $model->period_start = isset($model->period_start) ? Yii::$app->formatter->asDate($model->period_start, 'dd.MM.yyyy') : '' ?>
            <?= // Filter by date: period start
                $form->field($model, 'period_start')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' =>Module::t('app', 'Select...')],
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose'=>true,
                        'todayHighlight' => true,
                        'todayBtn' => "linked",
                    ]
                ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?php $model->period_end = isset($model->period_end) ? Yii::$app->formatter->asDate($model->period_end, 'dd.MM.yyyy') : '' ?>
            <?= // Filter by date: period end
                $form->field($model, 'period_end')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Module::t('app', 'Select...'),
                        //'value' => date("d.m.Y"), // default value
                        'value' => $model->period_end,
                    ],
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose'=>true,
                        'todayHighlight' => true,
                        'todayBtn' => "linked",
                    ]
                ]);
            ?>
        </div>
    </div>
    <p>
        <?= Html::button(Module::t('app', 'This month'), [
            'id' => 'btn-filter',
            'class' => 'btn btn-xs btn-success',
            'style' => 'top: 0px;', //glyphicon has 1 px
            'onclick' => '
                var date = new Date();
                var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).toLocaleDateString();
                var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).toLocaleDateString();
                document.getElementById("tasksearch-period_start").value = firstDay;
                document.getElementById("tasksearch-period_end").value = lastDay;
            '
        ])?>
        <?= Html::button(Module::t('app', 'Last month'), [
            'id' => 'btn-filter',
            'class' => 'btn btn-xs btn-success',
            'style' => 'top: 0px;', //glyphicon has 1 px
            'onclick' => '
                var date = new Date();
                var firstDay = new Date(date.getFullYear(), date.getMonth() - 1, 1).toLocaleDateString();
                var lastDay = new Date(date.getFullYear(), date.getMonth(), 0).toLocaleDateString();
                document.getElementById("tasksearch-period_start").value = firstDay;
                document.getElementById("tasksearch-period_end").value = lastDay;
            '
        ])?>
    </p>
    

    <div class="form-group">
        <?= Html::submitButton(Module::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Module::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>