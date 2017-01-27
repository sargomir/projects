<?php

use yii\helpers\Html;
//use yii\grid\GridView;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Report by tasks');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    //['class' => 'yii\grid\SerialColumn'],
    [// Project export
        'label' => Module::t('app', 'Project'),
        'attribute' => '_project',
        'hidden' => true,
    ],
    [// Project group
        'label' => Module::t('app', 'Project'),
        'attribute' => '_project',
        'group' => true,
        'hiddenFromExport' => true,
    ],
    [// ParentPart export
        'label' => Module::t('app', 'Project Parts'),
        'attribute' => '_parent_part',
        'hidden' => true,
    ],
    [// ParentPart group
        'label' => Module::t('app', 'Project Parts'),
        'attribute' => '_parent_part',  
        'group' => true,
        'subGroupOf' => 0,
        'hiddenFromExport' => true,
    ],
    [// Budget export
        'label' => Module::t('app', 'Budget'),
        'attribute' => '_budget',
        'hidden' => true,
    ],
    [// Budget
        'label' => Module::t('app', 'Budget'),
        'attribute' => '_budget',
        'group' => true,
        'subGroupOf' => 1,
        'hiddenFromExport' => true,
    ],
    [// ProjectPart export
        'label' => Module::t('app', 'Project Part'),
        'attribute' => '_project_part',
        'hidden' => true,
    ],
    [// ProjectPart group
        'label' => Module::t('app', 'Project Part'),
        'attribute' => '_project_part',
        'group' => true,
        'subGroupOf' => 2,
        'hiddenFromExport' => true,
    ],
    [// Worker export
        'label' => Module::t('app', 'Worker'),
        'value' => 'worker.username',
        'hidden' => true,
    ],
    [// Worker
        'label' => Module::t('app', 'Worker'),
        'attribute' => 'worker',
        'value' => 'worker.username',
        'group' => true,
        'subGroupOf' => 3,
        'hiddenFromExport' => true,
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
    [// Technical Lead
        'label' => Module::t('app', 'Technical Lead'),
        'attribute' => 'technical_lead',
        'value' => 'worker.lead.username',
        //'group' => true,
    ], 
];

$toolbar = [
    [// Search Button
        'content' => $this->render('_filter_project', ['model' => $searchModel]),
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