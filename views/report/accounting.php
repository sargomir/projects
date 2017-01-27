<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;
use app\modules\projects\models\AuthProfile;
use app\modules\projects\components\DateRangeWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Report for accounting');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    //['class' => 'yii\grid\SerialColumn'],
    [// Worker
        'label' => Module::t('app', 'Worker'),
        'attribute' => 'worker',
        'value' => 'worker.username',
    	'filterType' => GridView::FILTER_SELECT2,
    	'filter' => yii\helpers\ArrayHelper::map(AuthProfile::find()->joinWith(['assignments'])
   			->where('item_name = "worker"')->orderby('lastname')->all(), 'user_id', 'username'),
 		'filterWidgetOptions' => [
   			'pluginOptions' => ['allowClear'=>true],
    		'options' => ['placeholder' => Module::t('app', 'Select ...')],
   		],    		
        //'group' => true,
        //'subGroupOf' => 3,
    ],
	[// Tasks Estimated
		'label' => Module::t('app', 'Est.'),
		'attribute' => 'tasks_estimated',
		'headerOptions'=>['title'=>Module::t('app', 'Estimated')],
		'width' => '5%',
	],
	[// Tasks Elapsed
		'label' => Module::t('app', 'Ela.'),
		'attribute' => 'tasks_elapsed',
		'headerOptions'=>['title'=>Module::t('app', 'Elapsed')],
		'width' => '5%',
	],
	[// Tasks Issued
		'label' => Module::t('app', 'Iss.'),
		'attribute' => 'tasks_issued',
		'headerOptions'=>['title'=>Module::t('app', 'Issued')],
		'width' => '5%',
	],
	[// Tasks Failed
		'label' => Module::t('app', 'Fai.'),
		'attribute' => 'tasks_failed',
		'headerOptions'=>['title'=>Module::t('app', 'Failed')],
		'width' => '5%',
	],
    [// Technical Lead
        'label' => Module::t('app', 'Technical Lead'),
        'attribute' => 'technical_lead',
        'value' => 'worker.lead.username',
    	'filterType' => GridView::FILTER_SELECT2,
    	'filter' => yii\helpers\ArrayHelper::map(AuthProfile::find()->joinWith(['assignments'])
    		->where('item_name = "technical_lead"')->orderby('lastname')->all(), 'user_id', 'username'),
   		'filterWidgetOptions' => [
    		'pluginOptions' => ['allowClear'=>true],
    		'options' => ['placeholder' => Module::t('app', 'Select ...')],
   		],
        //'group' => true,
    ],
];

$toolbar = [
    [// Period Filter
		'content' => DateRangeWidget::widget([
			'label' => Module::t('app', 'Period'),
			'title' => Module::t('app', 'Filter period'),
			'model' => $searchModel,
			'attribute1' => 'period_start',
			'attribute2' => 'period_end',
			'presets' => [
				[// This month
					'label' => Yii::$app->formatter->asDate(date("M Y", strtotime("-1 month")), 'LLLL, yyyy'), // Module::t('app', 'Last month'),
					'value' => [
						'attribute1' => date("01.m.Y", strtotime("-1 month")),
						'attribute2' => date("t.m.Y", strtotime("-1 month"))
					],
				],
				[// Last month
					'label' => Yii::$app->formatter->asDate(date("M Y", strtotime("-2 month")), 'LLLL, yyyy'), // Module::t('app', '{months_ago} months ago', ['months_ago' => 2]),
					'value' => [
						'attribute1' => date("01.m.Y", strtotime("-2 month")),
						'attribute2' => date("t.m.Y", strtotime("-2 month"))
					],
				],
				[// All
					'label' => Yii::$app->formatter->asDate(date("M Y", strtotime("-3 month")), 'LLLL, yyyy'), // Module::t('app', '{months_ago} months ago', ['months_ago' => 3]),
					'value' => [
						'attribute1' => date("01.m.Y", strtotime("-3 month")),
						'attribute2' => date("t.m.Y", strtotime("-3 month"))
					],
				],
			],
		]),
	],
];
?>

<div class="tasks-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'toolbar' => $toolbar,
    ]); ?>

</div>