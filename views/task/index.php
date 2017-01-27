<style type="text/css">
.one-long-line {
   max-width:200px;
   white-space:nowrap; 
   overflow:hidden;
   text-overflow:ellipsis;
   }
.one-long-line:hover {
   white-space:normal; 
   }
</style>

<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;
use app\modules\projects\components\DropCheckWidget;
use app\modules\projects\components\DateRangeWidget;
use app\modules\projects\models\Task;
use app\modules\projects\models\AuthProfile;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;

//yii\widgets\Pjax::begin(['id' => 'pjax-content-index', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);
//echo DatePicker::widget([
//	'name' => 'dp_1',
//	'type' => DatePicker::TYPE_INPUT,
//	'value' => '23-Feb-1983',
//	'pluginOptions' => [
//		'autoclose'=>true,
//		'format' => 'dd-M-yyyy'
//	]
//]);
//echo DateRangeWidget::widget([
//	'label' => Module::t('app', 'Period'),
//	'title' => Module::t('app', 'Filter period'),
//	'model' => $searchModel,
//	'attribute1' => 'period_start',
//	'attribute2' => 'period_end',
//	]);
//yii\widgets\Pjax::end();

$columns = [
    //['class' => 'yii\grid\SerialColumn'],
    
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
//        'filter' => DropCheckWidget::widget([
//        	'label' => Module::t('app', 'All'),
//        	'value' => 'TaskSearch[status]',
//        	'items' => [
//        		[// Active
//        			'label' => Module::t('app', 'Active'),
//        			'value' => Task::Active,
//        			'checked' => is_array($searchModel->status) ? in_array(Task::Active, $searchModel->status) : false,
//        			'class' => 'label label-default',
//        		],
//        		[// Pending
//        			'label' => Module::t('app', 'Pending'),
//        			'value' => Task::Pending,
//        			'checked' => is_array($searchModel->status) ? in_array(Task::Pending, $searchModel->status) : false,
//        			'class' => 'label label-primary',
//        		],
//        		[// Completed
//        			'label' => Module::t('app', 'Completed'),
//        			'value' => Task::Completed,
//        			'checked' => is_array($searchModel->status) ? in_array(Task::Completed, $searchModel->status) : false,
//        			'class' => 'label label-success',
//        		],
//        		[// Overdue
//        			'label' => Module::t('app', 'Overdue'),
//        			'value' => Task::Overdue,
//        			'checked' => is_array($searchModel->status) ? in_array(Task::Overdue, $searchModel->status) : false,
//        			'class' => 'label label-warning',
//        		],
//        		[// Expired
//        			'label' => Module::t('app', 'Expired'),
//        			'value' => Task::Expired,
//        			'checked' => is_array($searchModel->status) ? in_array(Task::Expired, $searchModel->status) : false,
//        			'class' => 'label label-danger',
//        		],
//        	],
//        ]),
        'width' => '1%',
//         	Html::a(Module::t('app', 'Active'), ['index',
//         			        			'TaskSearch' => [
//         			        				'status' => [1]
//         			        			],
//         			        			'TaskSearch' => [
//         			        				'status' => [2]
//         			        			],

// 					'TaskSearch' => $searchModel,
//         			'TaskSearch' => [
//         				'status' => [
//         					1 => isset($searchModel->status[1])
//         						? ""
//         						: "1"
//         				],
//         			],
//         		],
//        			[
// 	        		'class' => is_array($searchModel->status)
// 	        		? (in_array('1', $searchModel->status)
// 	        			? 'btn btn-default glyphicon postkeylist active glyphicon-check'
// 	        			: 'btn btn-default glyphicon postkeylist glyphicon-unchecked')
// 	        		: 'btn btn-default glyphicon postkeylist glyphicon-unchecked',
// 	        		'style' => 'top: 0px;',
// 	        		'title' => Module::t('app', 'Filter Active'),
// 	        	]
//        		),

//         'filter' => Html::checkboxList('TaskSearch[status][]',
//         'filter' => Html::activeCheckboxList($searchModel, 'status',
//         		[
// 					'1'  => Module::t('app', 'Active'),
// 					'2'  => Module::t('app', 'Pending'),
// 					'3'  => Module::t('app', 'Completed'),
// 					'-1' => Module::t('app', 'Overdue'),
// 					'-2' => Module::t('app', 'Expired'),
//         		]
// //         		[
// // 					'1'  => Module::t('app', 'Active'),
// // 					'2'  => Module::t('app', 'Pending'),
// // 					'3'  => Module::t('app', 'Completed'),
// // 					'-1' => Module::t('app', 'Overdue'),
// // 					'-2' => Module::t('app', 'Expired'),
// //         		]
//         	),
// 	    'filterType' => GridView::FILTER_SELECT2,
// 	    'filter' => [
// 		    	'1' => Module::t('app', 'Active'),
// 		    	'2' => Module::t('app', 'Pending'),
//     			'3' => Module::t('app', 'Completed'),
// 				'-1' => Module::t('app', 'Overdue'),
// 				'-2' => Module::t('app', 'Expired'),
// 	    	],
// 	    'filterWidgetOptions' => [
// 	        'pluginOptions' => ['allowClear'=>true],
// 	    	'pluginEvents' => [
// 	    		'change' => 'function() { event.preventDefault(); }',
// 	    	],
// 	    	'addon' => [
// 	    		'append' => [
//     				'content' => Html::button('&nbsp', [
//     						'class' => 'btn btn-primary glyphicon glyphicon-ok',
//     						'title' => 'Mark on map',
//     						'data-toggle' => 'tooltip'
//     				]),
//     				'asButton' => true
// 	    		]
//     		],
// 	    ],
// 	    'filterInputOptions' => [
// 	        'placeholder' => 'Все',
// 	        'multiple' => true,
// 	    ],
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
    [// Worker Check
        'label' => Module::t('app', 'Worker'),
        'attribute' => 'worker',
        'value' => function($model) {return '<div class="label label-' . (isset($model->worker_check)?'success':'danger') . '">' . $model->worker->username . '</div>'; },
        'format' => 'html',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "worker"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],        
    ],
    [// Technical Lead Check
        'label' => Module::t('app', 'Technical Lead'),
        'attribute' => 'technical_lead',
        'value' => function($model) {return '<div class="label label-' . (isset($model->technical_lead_check)?'success':'danger') . '">' . $model->technical_lead->username . '</div>'; },
        'format' => 'html',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "technical_lead"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],        
	],
    [// Project Lead Check
        'label' => Module::t('app', 'Project Lead'),
        'attribute' => 'project_lead',
        'value' => function($model) {return '<div class="label label-' . (isset($model->project_lead_check)?'success':'danger') . '">' . $model->project_lead->username . '</div>'; },
        'format' => 'html',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "project_lead"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],        
	],
    [// Project Manager Check
        'label' => Module::t('app', 'Project Manager'),
        'attribute' => 'project_manager',
        'value' => function($model) {return '<div class="label label-' . (isset($model->project_manager_check)?'success':'danger') . '">' . $model->project_manager->username . '</div>'; },
        'format' => 'html',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => yii\helpers\ArrayHelper::map(AuthProfile::find()->joinWith(['assignments'])
			->where('item_name = "project_manager"')->orderby('lastname')->all(), 'user_id', 'username'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear'=>true],
			'options' => ['placeholder' => Module::t('app', 'Select ...')],
		],        
	],    
    //[// Checks
    //    'label' => Module::t('app', 'Completed'),
    //    'format' => 'raw',
    //    'value' => function($model) {
    //        return '<div class="label label-' . (isset($model->worker_check)?'success':'danger') . '">' . $model->worker->username . '</div><br/>'
    //            . '<div class="label label-' . (isset($model->technical_lead_check)?'success':'danger') . '">' . $model->technical_lead->username . '</div><br/>'
    //            . '<div class="label label-' . (isset($model->project_lead_check)?'success':'danger') . '">' . $model->project_lead->username . '</div><br/>'
    //            . '<div class="label label-' . (isset($model->project_manager_check)?'success':'danger') . '">' . $model->project_manager->username . '</div><br/>';
    //            
            //$worker = isset($model->worker->username) ? $model->worker->username : Module::t('app', 'Worker');
            //$technical_lead = isset($model->technical_lead->username) ? $model->technical_lead->username : Module::t('app', 'Technical Lead');
            //$project_lead = isset($model->project_lead->username) ? $model->project_lead->username : Module::t('app', 'Project Lead');
            //$project_manager = isset($model->project_manager->username) ? $model->project_manager->username : Module::t('app', 'Project Manager');
            //return "
            //    <div class='row' style='width:100%;'>
            //        <div class='col-sm-7'>{$worker}</div><div class='col-sm-5'><div class='label label-success'>{$model->worker_check}</div></div>
            //    </div>    
            //    <div class='row' style='width:100%;'>
            //        <div class='col-sm-7'>{$technical_lead}</div><div class='col-sm-5'><div class='label label-success'>{$model->technical_lead_check}</div></div>
            //    </div>
            //    <div class='row' style='width:100%;'>
            //        <div class='col-sm-7'>{$project_lead}</div><div class='col-sm-5'><div class='label label-success'>{$model->project_lead_check}</div></div>
            //    </div>
            //    <div class='row' style='width:100%;'>
            //        <div class='col-sm-7'>{$project_manager}</div><div class='col-sm-5'><div class='label label-success'>{$model->project_manager_check}</div></div>
            //    </div>
            //";
    //    },
    //],
    [// Action Buttons
        'class' => 'kartik\grid\ActionColumn',
		'viewOptions' => ['data-pjax' => '0','target' => '_blank'], // open links in new window
        'buttons' => [
            //'note' => function ($url, $model) {
            //    $notes_count = count($model->notes);
            //    //return \yii\helpers\Html::a('<span class="glyphicon glyphicon-comment">
            //    //    <div style="color:black;font-size:xx-small;position:absolute;top:3px;left:5px;">'. $notes_count .'</div></span>', ['tasknotes/create'],
            //    //    [
            //    //        'title' => Module::t('yii', 'Make note'),
            //    //        'data' => [
            //    //            'target' => '#create-note-popup',
            //    //            'toggle' => 'modal',                            
            //    //            'method' => 'post',
            //    //            'params' => [
            //    //                'task_id' => $model->task_id,
            //    //                'user_id' => Yii::$app->user->id,
            //    //            ],
            //    //        ]
            //    //    ]);
            //    
            //    //return Html::button($notes_count, [
            //    //    'class' => 'btn-xs btn-primary btn-ajax-modal glyphicon glyphicon-comment',
            //    //    'style' => 'top: 0px;',
            //    //    'title' => Yii::t('app', 'Search product'),
            //    //    'value' => \yii\helpers\Url::toRoute('/tasks/index'),
            //    //    'data' => [
            //    //        'target' => '#create-note-popup',
            //    //        'toggle' => 'modal',
            //    //        'backdrop' => 'static',
            //    //        //'method' => 'post',
            //    //        'params' => [
            //    //            'task_id' => $model->task_id,
            //    //            'user_id' => Yii::$app->user->id,
            //    //        ]
            //    //    ],
            //    //    //'data-target' => '#create-note-popup',
            //    //    //'data-toggle' => 'modal',
            //    //]);
            //},
            'update' => function ($url, $model) {
                $user = \Yii::$app->user;
                if ($user->can('admin', ['task_id' => $model->task_id])
                    || $user->can('worker_check', ['task_id' => $model->task_id])
                    || $user->can('technical_lead_check', ['task_id' => $model->task_id])
                    || $user->can('project_lead_check', ['task_id' => $model->task_id])
                    || $user->can('project_manager_check', ['task_id' => $model->task_id])
                    || $user->can('check_timeout', ['task_id' => $model->task_id])
                )
					return Html::a('<span class="glyphicon glyphicon-pencil">', ['update', 'id' => $model->task_id, 'create' => true],
						['data-pjax' => '0','target' => '_blank'] // open links in new window
					);
                return '';
            },
            'reassign' => function ($url, $model) {
                $user = \Yii::$app->user;
                if ($user->can('worker_check', ['task_id' => $model->task_id]) && $user->can('technical_lead'))
                    return Html::a('<span class="glyphicon glyphicon-transfer">', ['reassign', 'id' => $model->task_id], [
                            'id' => 'link-reassign-task-' . $model->task_id,
                            'title' => Module::t('app', 'Reassign'),
                            'data' => [
                                'target' => '#reassign-task-popup',
                                'toggle' => 'modal',
                                'backdrop' => 'static',
                            ],
                        ]
                    );
                return '';
            },
        ],
        'template'=>'{view} {note} {update} {reassign}',           
    ],
];

// Toolbar
$toolbar = [
// 	[// Filter status
// // 		'content' => Html::checkboxList('CuisineId',[0 => 'PHP', 1 => 'MySQL', 2 => 'Javascript'],[0,1,2,3,4]),
// 		'content' => kartik\select2\Select2::widget([
// 			'model' => $searchModel,
// 			'attribute' => 'status',
// 			'data' => [
// 		    	'1' => Module::t('app', 'Active'), 
// 		    	'2' => Module::t('app', 'Pending'),
//     			'3' => Module::t('app', 'Completed'),
// 				'-1' => Module::t('app', 'Overdue'),
// 				'-2' => Module::t('app', 'Expired'),
// 	    	],
// 			'options' => [
// 				'placeholder' => 'Select filter ...',
// 				'multiple' => true
// 			],			
// 		    'pluginOptions' => ['allowClear'=>true, 'style'=>'width: 400px !important',],
// 		    'addon' => [
// 		    	'prepend' => [
// 		    		'content' => Html::button('&nbsp', [
// 	   						'class' => 'btn btn-primary glyphicon glyphicon-ok',
// 	   						'title' => 'Mark on map',
// 	   						'data-toggle' => 'tooltip'
// 	   				]),    				
// 		   			'asButton' => true
// 		    	],
// 		   		'append' => [
// 	    			'content' => Html::button('&nbsp', [
// 	   						'class' => 'btn btn-primary glyphicon glyphicon-ok',
// 	   						'title' => 'Mark on map',
// 	   						'data-toggle' => 'tooltip'
// 	   				]),    				
// 		   			'asButton' => true
// 		    	]
// 	    	],
// 		]),
// 	   	'pluginEvents' => [
// 	    	'change' => 'function() { event.preventDefault(); }',
// 	    ],
	    
// 	    'filterInputOptions' => [
// 	        'placeholder' => 'Все', 
// 	        'multiple' => true,
// 	    ],
// 	],
//    [// Search Button
//        'content' => Html::button(Module::t('app', 'Search'), [
//            'class' => 'btn btn-default btn-ajax-modal glyphicon glyphicon-search',
//            'style' => 'top: 0px;',
//            'title' => Yii::t('app', 'Search task'),
//            'data-target' => '#search-task-popup',
//            'data-toggle' => 'modal',
//        ]),
//    ],
//    [// Active Filter
//        'content' => Html::a(Module::t('app', 'Active'), ['index', 'TaskSearch' => ['active' => $searchModel->active ? "" : "1"]], [
//            'class' => $searchModel->active
//                ? 'btn btn-default glyphicon postkeylist active glyphicon-check'
//                : 'btn btn-default glyphicon postkeylist glyphicon-unchecked',
//            'style' => 'top: 0px;',
//            'title' => Module::t('app', 'Filter Active'),
//        ]),
//    ],
	[
		'content' => DropCheckWidget::widget([
			'label' => Module::t('app', 'All'),
			'value' => 'TaskSearch[status]',
			'items' => [
				[// Active
					'label' => Module::t('app', 'Active'),
					'value' => Task::Active,
					'checked' => is_array($searchModel->status) ? in_array(Task::Active, $searchModel->status) : false,
					'class' => 'label label-default',
				],
				[// Pending
					'label' => Module::t('app', 'Pending'),
					'value' => Task::Pending,
					'checked' => is_array($searchModel->status) ? in_array(Task::Pending, $searchModel->status) : false,
					'class' => 'label label-primary',
				],
				[// Completed
					'label' => Module::t('app', 'Completed'),
					'value' => Task::Completed,
					'checked' => is_array($searchModel->status) ? in_array(Task::Completed, $searchModel->status) : false,
					'class' => 'label label-success',
				],
				[// Overdue
					'label' => Module::t('app', 'Overdue'),
					'value' => Task::Overdue,
					'checked' => is_array($searchModel->status) ? in_array(Task::Overdue, $searchModel->status) : false,
					'class' => 'label label-warning',
				],
				[// Expired
					'label' => Module::t('app', 'Expired'),
					'value' => Task::Expired,
					'checked' => is_array($searchModel->status) ? in_array(Task::Expired, $searchModel->status) : false,
					'class' => 'label label-danger',
				],
			],
		]),
	],
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

    <p>
        <?php if (Yii::$app->user->can('project_lead')) echo Html::a(Module::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'toolbar' => $toolbar,
        'rowOptions' => function ($model) {
            if (isset ($model->Completed))
                return ['class' => 'task-completed'];
        },
    ]); ?>

	</div>

    <!-- Search product popup window -->
    <?php
        //$searchModel = new ProductsSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $popup_title = Module::t('app', 'Search');
        \yii\bootstrap\Modal::begin([
                'id' => 'search-task-popup',
                'header' => '<h2>' . Module::t('app', 'Search') . '</h2>',
                //'toggleButton' => ['label' => 'Поиск товара', 'class' => 'btn btn-primary'],
                'size' => \yii\bootstrap\Modal::SIZE_LARGE,
            ]);
            echo $this->render('_search', ['model' => $searchModel]);
        \yii\bootstrap\Modal::end();
    ?>
    
    <!-- Reassign task popup window -->
    <div id="reassign-task-popup" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>
    
    <style type="text/css">
    	.select2-results li {
        	font-size: 75%;
    		font-style: bold;
    	}
    </style>