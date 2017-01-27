<?php

use yii\helpers\ArrayHelper;

use kartik\detail\DetailView;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\Project;
use app\modules\projects\models\ProjectPart;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Budget */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budget-form">


    <?= DetailView::widget([
            'model' => $model,
            'condensed' => true,
            'hover' => true,
            'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel' => [
                'heading' => isset($model->project_id) ? Module::t('app', 'Budget №{budget_id}', ['budget_id' => $model->budget_id]) : Module::t('app', 'Create budget'),
                'type' => DetailView::TYPE_PRIMARY,
                'headingOptions' => ['template'=> (\Yii::$app->user->can('admin') || \Yii::$app->user->can('project_manager'))
                    ? '{buttons}{title}'
                    : '{title}'],
            ],          
            'attributes' => [
                // Project
                [
                    'label' => Module::t('app', 'Project'),
                    'attribute' => 'project_id',
                    'value' => isset($model->project) ? $model->project->project : '',
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(Project::find()->asArray()->all(), 'project_id', 'project'),
                        'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                        //'disabled' => ! $model->isAttributeActive('project_lead_id'),
                    ],
                ],
                // Project Part
                [
                    'label' => Module::t('app', 'Project Part'),
                    'attribute' => 'project_part_id',
                    'value' => isset($model->project_part) ? $model->project_part->part : '',
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(
                                ProjectPart::find()
                                ->joinWith('parent')
                                ->where([ProjectPart::tableName().'.parent_part_id' => null])
                                ->asArray()->all(), 'part_id', 'part', 'parent.part'
                            ),
                        'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                        //'disabled' => ! $model->isAttributeActive('project_lead_id'),
                    ],
                ],
                // Budget
                'budget',
            ]
        ])
    ?>

</div>