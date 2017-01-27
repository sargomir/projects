<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use kartik\detail\DetailView;
use kartik\widgets\DepDrop;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\Project;
use app\modules\projects\models\ProjectPart;
use app\modules\projects\models\AuthProfile;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Tasks */
/* @var $form yii\widgets\ActiveForm */

$user = Yii::$app->user;

$notes = [];
    foreach ($model->notes as $note) {
        //$notes[] = "<div><div>{$note->created_at}</div><div>{$note->user_id}</div><div>{$note->note}</div></div>";
        $notes[] = "<div>[<i>{$note->created_at}</i>] <b>{$note->user->username}:</b> {$note->note}</div>";
    }
$notes = implode("", $notes);

// Default values for datepicker
$model->start = isset($model->start)
    ? Yii::$app->formatter->asDate($model->start, 'dd.MM.yyyy')
    : Yii::$app->formatter->asDate (date("Y-m-d H:i:s"), 'dd.MM.yyyy');
$model->deadline = isset($model->deadline)
    ? Yii::$app->formatter->asDate($model->deadline, 'dd.MM.yyyy')
    : Yii::$app->formatter->asDate (date("Y-m-t H:i:s"), 'dd.MM.yyyy');

// Disable delete button. Deleting tasks is not allowed
$this->registerCss('.kv-action-btn.kv-btn-delete{display: none;}');
?>


<div class="task-form">

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => isset($model->task_id) ? Module::t('app', 'Task №{task_id}', ['task_id' => $model->task_id]) : Module::t('app', 'Create task'),
            'type' => DetailView::TYPE_PRIMARY,
            'headingOptions' => ['template'=> (\Yii::$app->user->can('admin', ['task_id' => $model->task_id])
                || $user->can('worker_check', ['task_id' => $model->task_id])
                || $user->can('technical_lead_check', ['task_id' => $model->task_id])
                || $user->can('project_lead_check', ['task_id' => $model->task_id])
                || $user->can('project_manager_check', ['task_id' => $model->task_id])
                || $user->can('check_timeout', ['task_id' => $model->task_id]))
                ? '{buttons}{title}'
                : '{title}'
            ],
        ],        
        'attributes' => [
        /**
         * Task condition
         */
            [
                'group' => true,
                'label' => Module::t('app', 'Task condition'),
                'rowOptions' => ['class' => 'info']
            ],
            [
                'columns' => [
                    [// Project
                        'attribute' => 'project_id',
                        'format' => 'raw',
                        'value' => isset ($model->project->project) ? $model->project->project : null,
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
//                            'data' => $user->can('admin') || $user->can('project_manager')
//                                ? ArrayHelper::map(Project::find()->asArray()->all(), 'project_id', 'project')
//                                : ArrayHelper::map(Project::find()->where(['project_lead_id' => $user->id, 'active'=>1])
//                                    ->orderBy(['project'=>SORT_ASC])->asArray()->all(), 'project_id', 'project'),
                            'data' => $model->getFilteredProjectList(),
                            'options' => ['placeholder' => Module::t('app', 'Select ...')],
                            'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                            'disabled' => ! $model->isAttributeActive('project_id'),
                        ],
                        'valueColOptions'=>['class' => 'sm', 'style'=>'width:30%']
                    ],
                    [// Project Part
                        'attribute' => 'project_part_id',
                        'format' => 'raw',
                        'value' => isset ($model->project_part->part) ? $model->project_part->part : null,
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => ArrayHelper::map(ProjectPart::find()
                                ->joinWith('parent')
                                ->where(['not', [ProjectPart::tableName().'.parent_part_id' => null]])
                                ->asArray()->all(), 'part_id', 'part', 'parent.part'),
                            'options' => ['placeholder' => Module::t('app', 'Select ...')],
                            'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],                            
                            'disabled' => ! $model->isAttributeActive('project_id'),
                        ],
                        'valueColOptions'=>['style'=>'width:30%']
                    ],             
                ]
            ],
            [// Task
                'attribute' => 'task',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXTAREA,
                'options' => ['disabled' => ! $model->isAttributeActive('task')],
            ],// Result
            [
                'attribute' => 'result',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXTAREA,
                'options' => ['disabled' => ! $model->isAttributeActive('result')],
            ],
            [// Active
                'attribute' => 'active',
                'value' => $model->active ? Module::t('app', 'Yes') : Module::t('app', 'No'),
                'format' => 'raw',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'columns' => [
                    [// Start
                        'attribute' => 'start',
                        'value' => Yii::$app->formatter->asDate($model->start, 'dd.MM.yyyy'),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_DATE,
                        'options' => ['disabled' => ! $model->isAttributeActive('start')],
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'startDate' => date("01.m.Y", strtotime("-5 days")),
                                //'endDate' => date("t.m.Y"),
                                'endDate' => date("t.m.Y", strtotime("+1 months")),
                            ],
                        ],                        
                    ],            
                    [// Deadline
                        'attribute' => 'deadline',
                        'value' => Yii::$app->formatter->asDate($model->deadline, 'dd.MM.yyyy'),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_DATE,
                        'options' => ['disabled' => ! $model->isAttributeActive('deadline')],
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'startDate' => date("01.m.Y", strtotime("-5 days")),
                                //'endDate' => date("05.m.Y", strtotime("+1 month")),
                                'endDate' => date("t.m.Y", strtotime("+1 months")),
                            ],
                        ],
                    ],
                ],
            ],
            [
                'columns' => [
                    // Estimated
                    [
                        'attribute' => 'estimated',
                        'value' => "{$model->estimated} чел*час",
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SPIN,
                        'options' => ['disabled' => ! $model->isAttributeActive('estimated')],
                        'widgetOptions' => ['pluginOptions' => ['max'=>200]],                        
                    ],
                    // Worker
                    [
                        'label' => Module::t('app', 'Worker'),
                        'attribute' => 'worker_id',
                        'value' => isset($model->worker->username) ? $model->worker->username : null,
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => ArrayHelper::map(AuthProfile::find()
                                ->joinWith(['assignments'])->where('item_name = "worker"')
                                ->orderBy(['lastname'=>SORT_ASC])
                                ->all(), 'user_id', 'username'),
                            'options' => ['placeholder' => Module::t('app', 'Select ...')],
                            'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                            'disabled' => ! $model->isAttributeActive('worker_id'),
                        ],
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
                ]
            ],
            
        /**
         * Task progress
         */
            [
                'group' => true,
                'label' => Module::t('app', 'Task progress'),
                'rowOptions' => ['class' => 'info']
            ],
            [
                'columns' => [
                    // Elapsed
                    [
                        'attribute' => 'elapsed',
                        'value' => "{$model->elapsed} чел*час",
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SPIN,
                        'options' => ['disabled' => ! $model->isAttributeActive('elapsed')],
                        'widgetOptions' => ['pluginOptions' => ['max'=>200]],                        
                    ],
                    // Worker Check
                    [
                        'label' => Module::t('app', 'Completed'),
                        'attribute' => 'worker_check_bool',
                        'format'=>'raw',
                        'type' => DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'onText' => Module::t('app', 'Yes'),
                                'offText' => Module::t('app', 'No'),
                            ],
                            'disabled' => ! $model->isAttributeActive('worker_check_bool'),
                        ],
                        'value' => $model->worker_check_bool ? "<span class='label label-success'>{$model->worker_check}</span>" : '<span class="label label-danger">'. Module::t('app', 'No') .'</span>',
                        'valueColOptions'=>['style'=>'width:20%']
                    ],
                ],
            ],
            
        /**
         * Task review
         */
            [
                'group' => true,
                'label' => Module::t('app', 'Task review'),
                'rowOptions' => ['class' => 'info']
            ],
            [
                'columns' => [            
                    // Technical Lead
                    [
                        'label' => Module::t('app', 'Technical Lead'),
                        'attribute' => 'technical_lead_id',
                        'value' => isset($model->technical_lead->username) ? $model->technical_lead->username : null,
                        'format' => 'raw',
                        'type' => DetailView::INPUT_DEPDROP, // Dependant DropDownList. 
                        'widgetOptions' => [
                            'type'=>DepDrop::TYPE_SELECT2,
                            'data' => ArrayHelper::map(AuthProfile::find()
                                ->joinWith(['assignments'])->where('item_name = "technical_lead"')
                                ->orderBy(['lastname'=>SORT_ASC])
                                ->all(), 'user_id', 'username'),
                            'options' => ['placeholder' => Module::t('app', 'Select ...')],
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true, 'width'=>'100%']],
                            'pluginOptions' => [
                                'depends'=>['task-worker_id'],
                                'url'=>\yii\helpers\Url::to(['depdrop/workerstechlead']),
                                'placeholder' => Module::t('app', 'Select ...'),
                            ],
                            'disabled' => ! $model->isAttributeActive('technical_lead_id'),
                            //'disabled' => ! Yii::$app->user->can('admin'),                            
                            /*'pluginEvents' => Yii::$app->user->can('admin')?"":[// Disable edit
                                "depdrop.afterChange"=>"function(event, id, value) { $('#task-technical_lead_id').attr('disabled','disabled'); }",
                            ],*/
                        ],
                    ],
                    // Technical Lead Check
                    [
                        'label' => Module::t('app', 'Approved'),
                        'attribute' => 'technical_lead_check_bool',
                        'format'=>'raw',
                        'type' => DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'onText' => Module::t('app', 'Yes'),
                                'offText' => Module::t('app', 'No'),
                            ],
                            'disabled' => ! $model->isAttributeActive('technical_lead_check_bool'),
                        ],
                        'value' => $model->technical_lead_check_bool ? "<span class='label label-success'>{$model->technical_lead_check}</span>" : '<span class="label label-danger">'. Module::t('app', 'No') .'</span>',
                        'valueColOptions'=>['style'=>'width:20%']                        
                    ]
                ]
            ],
            [
                'columns' => [
                    // Project Lead
                    [
                        'label' => Module::t('app', 'Project Lead'),
                        'attribute' => 'project_lead_id',
                        'value' => isset($model->project_lead->username) ? $model->project_lead->username : null,
                        'format' => 'raw',
                        'type' => DetailView::INPUT_DEPDROP, // Dependant DropDownList. 
                        'widgetOptions' => [
                            'type'=>DepDrop::TYPE_SELECT2,
                            'data' => ArrayHelper::map(AuthProfile::find()
                                ->joinWith(['assignments'])->where('item_name = "project_lead"')
                                ->orderBy(['lastname'=>SORT_ASC])
                                ->all(), 'user_id', 'username'),
                            'value' => Yii::$app->user->id,
                            'options' => ['placeholder' => Module::t('app', 'Select ...')],
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true, 'width'=>'100%']],
                            'pluginOptions' => [
                                'depends'=>['task-project_id'],
                                'url'=>\yii\helpers\Url::to(['depdrop/projectslead']),
                              'placeholder' => Module::t('app', 'Select ...'),

                            ],
                            'disabled' => ! $model->isAttributeActive('project_lead_id'),
                            //'disabled' => ! Yii::$app->user->can('admin'),
                            /*'pluginEvents' => Yii::$app->user->can('admin')?"":[// Disable edit
                                "depdrop.afterChange"=>"function(event, id, value) { $('#task-project_lead_id').attr('disabled','disabled'); }",
                            ],*/
                        ],
                    ],
                    // Project Lead Check
                    [
                        'label' => Module::t('app', 'Approved'),
                        'attribute' => 'project_lead_check_bool',
                        'format'=>'raw',
                        'type' => DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'onText' => Module::t('app', 'Yes'),
                                'offText' => Module::t('app', 'No'),
                            ],
                            'disabled' => ! $model->isAttributeActive('project_lead_check_bool'),
                        ],
                        'value' => $model->project_lead_check_bool ? "<span class='label label-success'>{$model->project_lead_check}</span>" : '<span class="label label-danger">'. Module::t('app', 'No') .'</span>',
                        'valueColOptions'=>['style'=>'width:20%']                               
                    ],
                ]
            ],
            [
                'columns' => [
                    // Project Manager
                    [
                        'label' => Module::t('app', 'Project Manager'),
                        'attribute' => 'project_manager_id',
                        //'value' => isset($model->project_manager->username) ? $model->project_manager->username : null,
                        'value' => $model->project_manager->username,
                        'format' => 'raw',
                        
                        'type' => DetailView::INPUT_DEPDROP, // Dependant DropDownList. 
                        'widgetOptions' => [
                            'type'=>DepDrop::TYPE_SELECT2,
                            'data' => ArrayHelper::map(AuthProfile::find()
                                ->joinWith(['assignments'])->where('item_name = "project_manager"')
                                ->orderBy(['lastname'=>SORT_ASC])
                                ->all(), 'user_id', 'username'),
                            'value' => Yii::$app->user->id,
                            'options' => ['placeholder' => Module::t('app', 'Select ...')],
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true, 'width'=>'100%']],
                            'pluginOptions' => [
                                'depends'=>['task-project_id'],
                                'url'=>\yii\helpers\Url::to(['depdrop/projectsmanager']),
                              'placeholder' => Module::t('app', 'Select ...'),

                            ],
                            'disabled' => ! $model->isAttributeActive('project_manager_id'),
                            //'disabled' => ! Yii::$app->user->can('admin'),
                            /*'pluginEvents' => Yii::$app->user->can('admin')?"":[// Disable edit
                                "depdrop.afterChange"=>"function(event, id, value) { $('#task-project_lead_id').attr('disabled','disabled'); }",
                            ],*/
                        ],
                        
                        //'type' => DetailView::INPUT_SELECT2,
                        //'widgetOptions' => [
                        //    'data' => ArrayHelper::map(AuthProfile::find()
                        //        ->joinWith(['assignments'])->where('item_name = "project_manager"')
                        //        ->orderBy(['lastname'=>SORT_ASC])
                        //        ->all(), 'user_id', 'username'),
                        //    //'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        //    //'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                        //    'disabled' => ! $model->isAttributeActive('project_manager_id'),
                        //    //'disabled' => ! Yii::$app->user->can('admin'),
                        //    /*'pluginEvents' => Yii::$app->user->can('admin')?"":[// Disable edit
                        //        "depdrop.afterChange"=>"function(event, id, value) { $('#task-project_manager_id').attr('disabled','disabled'); }",
                        //    ],*/
                        //],
                    ],
                    // Project Manager Check
                    [
                        'label' => Module::t('app', 'Approved'),
                        'attribute' => 'project_manager_check_bool',
                        'format'=>'raw',
                        'type' => DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'onText' => Module::t('app', 'Yes'),
                                'offText' => Module::t('app', 'No'),
                            ],
                            'disabled' => ! $model->isAttributeActive('project_manager_check_bool'),
                        ],
                        'value' => $model->project_manager_check_bool ? "<span class='label label-success'>{$model->project_manager_check}</span>" : '<span class="label label-danger">'. Module::t('app', 'No') .'</span>',
                        'valueColOptions'=>['style'=>'width:20%']                            
                    ]
                ]
            ],
            // Notes
            [
                'label' => Module::t('app', 'Notes'),
                'value' => $notes,
                'format' => 'raw',
                'type' => DetailView::INPUT_HIDDEN,
                        'widgetOptions' => ['disabled' => true],
            ]
        ],
    ]) ?>

</div>
