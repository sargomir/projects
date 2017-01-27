<?php

use yii\helpers\Html;

//use kartik\grid\GridView;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;
use app\modules\projects\models\Task;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Едеждневный отчет о загрузке сотрудников');
$this->params['breadcrumbs'][] = $this->title;

// Export null as emptystring
Yii::$app->formatter->nullDisplay = '';

$columns = [
    [// Project Lead Check
        'label' => Module::t('app', 'Project Lead'),
        'attribute' => 'project_lead_id',
        'value' => function($model) {return $model->project_lead->username; },
        'format' => 'html',
        'group' => true,
	],
    [// Worker Check
        'label' => Module::t('app', 'Worker'),
        'attribute' => 'worker_id',
        'value' => function($model) {return $model->worker->username; },
        'format' => 'html',
        'group' => true,
        'subGroupOf' => 0,
    ],
    
    [// Project group
        'attribute' => 'project_id',
        'value' => 'project.project',
        'contentOptions' => ['class' => 'one-long-line'],
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\Project::find()
			->orderby('project')->all(), 'project_id', 'project'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],        
    ],
    [// ProjectPart group
        //'label' => Module::t('app', 'Code'),
        'attribute' => 'project_part_id',
        'value' => 'project_part.part',
        'contentOptions' => ['class' => 'one-long-line'],
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(app\modules\projects\models\ProjectPart::find()
			->orderby('part')->all(), 'part_id', 'part'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],        
    ],
	[// Task export column
		'attribute' => 'task',
		'hidden' => true,
	],
	[// Task
        'attribute' => 'task',
        'value' => function($model) { return '<div class="label label-default">№' . $model->task_id . '</div> ' . $model->task; },
        'format' => 'html',
        'contentOptions' => ['class' => 'one-long-line'],
		'hiddenFromExport' => true,
    ],
    [// Start
        'label' => 'Начало',
        'attribute' => 'start',
        'format' => ['date', 'php:d.m.Y'],
    	'width' => '1%',
    ],
    [// Deadline
        'label' => 'Сдача',
        'attribute' => 'deadline',
        'format' => ['date', 'php:d.m.Y'],
    	'width' => '1%',
    ],
    [// Status
        'label' => Module::t('app', 'Status'),
        'attribute' => 'status',
        'format' => 'html',
        'value' => function($model) {
            switch ($model->status) {
                case $model::Completed :
                    return '<div class="label label-success">' . Module::t('app', 'Completed') . '</div>';
                case $model::Pending :
                    return '<div class="label label-primary">' . Module::t('app', 'Pending') . '</div>';
                case $model::Active :
                    return '<div class="label label-default">' . Module::t('app', 'Active') . '</div>';
                case $model::Overdue :
                    return '<div class="label label-warning">' . Module::t('app', 'Overdue') . '</div>';
                case $model::Expired :
                    return '<div class="label label-danger">' . Module::t('app', 'Expired') . '</div>';

            }
            return $model->status;
                //? Module::t('app', 'Active')
                //: Module::t('app', 'Closed');
        },
		'filter' => false,
        'width' => '1%',
    ],
    [// Estimated
        'label' => Module::t('app', 'Est.'),
        'attribute' => 'estimated',
    	'width' => '1%',
    	'filter' => false,
    ],
    [// Elapsed
        'label' => Module::t('app', 'Ela.'),
        'attribute' => 'elapsed',
    	'width' => '1%',
    	'filter' => false,
    ],  
//    [// Worker Check
//        'label' => Module::t('app', 'Worker'),
//        'attribute' => 'worker',
//        'value' => function($model) {return '<div class="label label-' . (isset($model->worker_check)?'success':'danger') . '">' . $model->worker->check . '</div>'; },
//        'format' => 'html',
//    ],
//    [// Technical Lead Check
//        'label' => Module::t('app', 'Technical Lead'),
//        'attribute' => 'technical_lead',
//        'value' => function($model) {return '<div class="label label-' . (isset($model->technical_lead_check)?'success':'danger') . '">' . $model->technical_lead_check . '</div>'; },
//        'format' => 'html',
//	],
//    [// Project Lead Check
//        'label' => Module::t('app', 'Project Lead'),
//        'attribute' => 'project_lead',
//        'value' => function($model) {return '<div class="label label-' . (isset($model->project_lead_check)?'success':'danger') . '">' . $model->project_lead_check . '</div>'; },
//        'format' => 'html',
//	],
//    [// Project Manager Check
//        'label' => Module::t('app', 'Project Manager'),
//        'attribute' => 'project_manager',
//        'value' => function($model) {return '<div class="label label-' . (isset($model->project_manager_check)?'success':'danger') . '">' . $model->project_manager_check . '</div>'; },
//        'format' => 'html',
//	],    
];

$columns = [
	'lead_lastname',
	'lead_firstname',
	'lead_secondname',
	'worker_lastname',
	'worker_firstname',
	'worker_secondname',
	'project',
	'project_part',
	'task',
	'start',
	'deadline',
	'status',
	'estimated',
	'elapsed'
]

?>

<div class="tasks-index">

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        //'export' => false,
        'panel' => [
            'heading' => '<h3>Отчет от "'. date("d.m.Y") .'" по текущим задачам</h3>'
        ],
        //'toolbar' => false,
    ]); ?>

</div>