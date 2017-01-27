<?php

use yii\helpers\Html;
//use yii\grid\GridView;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Report by projects');
$this->params['breadcrumbs'][] = $this->title;

// Export null as emptystring
Yii::$app->formatter->nullDisplay = 0;

$columns = [
    //['class' => 'yii\grid\SerialColumn'],
    
    [// Project group
        'label' => Module::t('app', 'Project'),
        'attribute' => '_project',
        //'value' => 'project',
        'format' => 'raw',
        'group' => true,
    ],
    [// ProjectParts
        'label' => Module::t('app', 'Project Parts'),
        'attribute' => '_parent_part',
        //'value' => 'part',
        'group' => true,
        'subGroupOf' => 0,
    	'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
    		return [
    				'mergeColumns'=>[[1,3]], // columns to merge in summary
    				'content'=>[             // content to show in each summary cell
    						1=>'Итого: ' . $model->_parent_part,
    						4=>GridView::F_AVG,
    						5=>GridView::F_SUM,
    						6=>GridView::F_SUM,
    				],
    				'contentFormats'=>[      // content reformatting for each summary cell
    						4=>['format'=>'integer', 'decimals'=>0],
    						5=>['format'=>'integer', 'decimals'=>0],
    						6=>['format'=>'integer', 'decimals'=>0],
    				],
    				'contentOptions'=>[      // content html attributes for each summary cell
    						1=>['style'=>'font-variant:small-caps'],
//     						4=>['style'=>'text-align:right'],
//     						5=>['style'=>'text-align:right'],
//     						6=>['style'=>'text-align:right'],
    				],
    				// html attributes for group summary row
    				'options'=>['class'=>'danger','style'=>'font-weight:bold;']
    		];
    	}    		
    ],

    [// ProjectPart
        'label' => Module::t('app', 'Project Part'),
        'attribute' => '_project_part', 		
        //'value' => 'part',
//         'group' => true,
//         'subGroupOf' => 1,		
    ],
    [// Project Lead
    	'label' => Module::t('app', 'Project Lead'),
    	'attribute' => '_project_lead',
    	'value' => 'project.project_lead.username',
    	'group' => true,
    	'subGroupOf' => 1,    		
    ],    
    [// Budget
    	'label' => Module::t('app', 'Budget'),
    	'attribute' => '_budget',
    	'group' => true,
    	'subGroupOf' => 1,    		
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

?>

<div class="tasks-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
        //'toolbar' => $toolbar,
        'rowOptions' => function ($model) {
            if (isset ($model->Completed))
                return ['class' => 'task-completed'];
        },
    ]); ?>

</div>