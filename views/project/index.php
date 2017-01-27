<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\inandex\models\TagsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    // Project
    [
        'attribute' => 'project_id',
        'value' => 'project',
        'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\Project::find()->orderby('project')->all(), 'project_id', 'project'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],      
    ],
    // Company
    [
        'attribute' => 'company',
        'value' => 'company',
        'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\Project::find()->orderby('company')->distinct()->all(), 'company', 'company'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],           
    ],
    // Project Lead
    [
        'label' => Module::t('app', 'Project Lead'),
        'attribute' => 'project_lead_id',
        'value' => 'project_lead.username',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "project_lead"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],       
    ],
	// Project Manager (default)
	[
        'label' => Module::t('app', 'Project Manager'),
        'attribute' => 'project_manager_id',
        'value' => 'project_manager.username',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "project_manager"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],       		
	],
    // Active
    [
        'attribute' => 'active',
        'value' => function ($model) { return $model->active ? Module::t('app', 'Yes') : Module::t('app', 'No'); },
        'visible' => ! $searchModel->active,
        'filter' => false,
    ],
    // Action Buttons
    [
        'class' => 'yii\grid\ActionColumn',
        'buttons' => [
            'update' => function ($url, $model) {
                $user = \Yii::$app->user;
                if ($user->can('admin') || $user->can('project_manager'))
                    return Html::a('<span class="glyphicon glyphicon-pencil">', ['update', 'id' => $model->project_id, 'create' => true]);
                return '';
            }
        ],
        'template'=>'{view} {note} {update}',          
    ],
];

// Toolbar        
$toolbar = [
    [// Active Filter
        'content' => Html::a(Module::t('app', 'Active'), ['index', 'ProjectSearch' => ['active' => $searchModel->active ? "" : "1"]], [
            'class' => $searchModel->active
                ? 'btn btn-default glyphicon postkeylist active glyphicon-check'
                : 'btn btn-default glyphicon postkeylist glyphicon-unchecked',
            'style' => 'top: 0px;',
            'title' => Module::t('app', 'Filter Active'),
        ]),
    ],
];
?>

<div class="projects-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if (Yii::$app->user->can('project_manager')) echo Html::a(Module::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'toolbar' => $toolbar,
    ]); ?>

</div>
