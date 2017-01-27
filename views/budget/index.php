<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\BudgetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Budgets');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    // Project
    [
        'label' => Module::t('app', 'Project'),
        'attribute' => 'project_id',
        'value' => 'project.project',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\Project::find()->orderby('project')->all(), 'project_id', 'project'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],           
        'group'=>true,
    ],
    // Project Part
    [
        'label' => Module::t('app', 'Project Parts'),
        'attribute' => 'project_part_id',
        'value' => 'project_part.part',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\ProjectPart::find()
                        ->where(['parent_part_id' => null])->orderby('part')->all(), 'part_id', 'part'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],          
    ],
    // Budget
    [
        'attribute' => 'budget',
        'filter' => false,
    ],
    'used',
    [
        'attribute' => 'ratio',
        'value' => function($model) { return "{$model->ratio} %"; },
    ],
    // Project Lead
    [
        'label' => Module::t('app', 'Project Lead'),
        'attribute' => 'project_lead_id',
        'value' => 'project.project_lead.username',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "project_lead"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],         
    ],

    ['class' => 'yii\grid\ActionColumn'],    
];

// Toolbar
$toolbar = [
    [// Active Filter
        'content' => Html::a(Module::t('app', 'Active'), ['index', 'BudgetSearch' => ['active' => $searchModel->active ? "" : "1"]], [
            'class' => $searchModel->active
                ? 'btn btn-default glyphicon postkeylist active glyphicon-check'
                : 'btn btn-default glyphicon postkeylist glyphicon-unchecked',
            'style' => 'top: 0px;',
            'title' => Module::t('app', 'Filter Active'),
        ]),
    ],
];

if (Yii::$app->user->can('project_manager')) {
    $toolbar = array_merge([
        'content' => Html::a(Module::t('app', 'Create budget'), ['create'], ['class' => 'btn btn-success']),
            ], $toolbar);
}
?>

<div class="budget-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //if (Yii::$app->user->can('project_manager')) echo Html::a(Module::t('app', 'Create budget'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'toolbar' => $toolbar,
    ]); ?>

</div>