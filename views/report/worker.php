<?php

use yii\helpers\Html;
//use yii\grid\GridView;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;
use app\modules\projects\components\DateRangeWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Report by workers');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    //['class' => 'yii\grid\SerialColumn'],
        [
        'label' => Module::t('app', 'Technical Lead'),
        'attribute' => 'technical_lead',
        'value' => 'worker.lead.username',
        //'group' => true,
    ],
    [
        'label' => Module::t('app', 'Worker'),
        'attribute' => 'worker',
        'value' => 'worker.username',
        //'group' => true,
        //'subGroupOf' => 0,
    ],
   
    [// Project group
        'label' => Module::t('app', 'Project'),
        'attribute' => '_project',
        'format' => 'raw',
        //'group' => true,
        //'subGroupOf' => 1,
    ],
    [// ProjectPart group
        'label' => Module::t('app', 'Project Part'),
        'attribute' => '_project_part',
        //'group' => true,
        //'subGroupOf' => 2,
    ],
    [// Tasks Estimated
        'label' => Module::t('app', 'Est.'),
        'attribute' => 'tasks_estimated',
        'headerOptions'=>['title'=>Module::t('app', 'Estimated')],
    ],
    [// Tasks Elapsed
        'label' => Module::t('app', 'Ela.'),
        'attribute' => 'tasks_elapsed',
        'headerOptions'=>['title'=>Module::t('app', 'Elapsed')],
    ],
    [// Tasks Issued
        'label' => Module::t('app', 'Iss.'),
        'attribute' => 'tasks_issued',
        'headerOptions'=>['title'=>Module::t('app', 'Issued')],
    ],
    [// Tasks Failed
        'label' => Module::t('app', 'Fai.'),
        'attribute' => 'tasks_failed',
        'headerOptions'=>['title'=>Module::t('app', 'Failed')],
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
					'label' => Module::t('app', 'This month'),
					'value' => [
						'attribute1' => date("01.m.Y"),
						'attribute2' => date("t.m.Y")
					],
				],
				[// Last month
					'label' => Module::t('app', 'Last month'),
					'value' => [
						'attribute1' => date("01.m.Y", strtotime("-1 month")),
						'attribute2' => date("t.m.Y", strtotime("-1 month"))
					],
				],
				[// All
					'label' => Module::t('app', 'Reset'),
					'value' => ['attribute1' => '', 'attribute2' => ''],
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
        //'filterModel' => $searchModel,
        'columns' => $columns,
        'toolbar' => $toolbar,
    ]); ?>

</div>