<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\detail\DetailView;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\ProjectPart;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\ProjectParts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-parts-form">

    <?= DetailView::widget([
            'model' => $model,
            'condensed' => true,
            'hover' => true,
            'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel' => [
                'heading' => isset($model->project_id) ? Module::t('app', 'Project №{project_id}', ['project_id' => $model->project_id]) : Module::t('app', 'Create project'),
                'type' => DetailView::TYPE_PRIMARY,
                'headingOptions' => ['template'=> (\Yii::$app->user->can('admin') || \Yii::$app->user->can('project_manager'))
                    ? '{buttons}{title}'
                    : '{title}'],
            ],          
            'attributes' => [
                'code',
                'part',
                [
                    'attribute' => 'parent_part_id',
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(ProjectPart::find()
                            ->where('ISNULL(parent_part_id)')
                            ->orderBy('part')->all(), 'part_id', 'part'),
                        'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                        'disabled' => ! $model->isAttributeActive('parent_part_id'),
                    ],
                ],
                'bdds_id',
            ],
    ]) ?>

</div>