<?php

use yii\helpers\Html;

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;
use app\modules\projects\models\Task;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Экспорт в BDDS');
$this->params['breadcrumbs'][] = $this->title;

// Export null as emptystring
Yii::$app->formatter->nullDisplay = '';

$columns = [
    [
        'label' => 'ID',
        'value' => function () {return '11449';},
        'header' => '0',
        'headerOptions'=>['title'=>'ID'],
    ],
    [
        'label' => 'Chel-chas',
        'value' => 'estimated',
        'header' => '1',
        'headerOptions'=>['title'=>'Трудозатраты'],
    ],
    [
        'label' => 'Проект_Код',
        'value' => 'project.bdds_id',
        'header' => '2',
        'headerOptions'=>['title'=>'Код проекта'],
    ],
    [
        'label' => 'Элементы_проекта_Код',
        'value' => 'parent_part.bdds_id',
        'header' => '3',
        'headerOptions'=>['title'=>'Код элемента проекта'],
    ],
    [
        'label' => 'Сотрудники_Код',
        'value' => 'worker.bdds_id',
        'header' => '4',
        'headerOptions'=>['title'=>'Код сотрудника'],
    ],
    [
        'label' => 'Подстановка Месяцы',
        'value' => function () {return '92';},
        'header' => '5',
        'headerOptions'=>['title'=>'Подстановка Месяцы'],
        ],
    [
        'label' => 'Подразделы_Код',
        'value' => 'project_part.bdds_id',
        'header' => '6',
        'headerOptions'=>['title'=>'Код подэлемента проекта'],
    ],
    [
        'label' => 'Комментарии',
        'value' => 'task',
        'header' => '7',
        'headerOptions'=>['title'=>'Задача'],
    ],
    [
        'label' => 'Финансов_контр',
        'value' => function ($model) {return isset($model->project_manager_check) ? '-1' : '0';},
        'header' => '8',
        'headerOptions'=>['title'=>'Финансовый контроль'],
	],
    [
        'label' => 'Проект_контр',
        'value' => function ($model) {return isset($model->project_lead_check) ? '-1' : '0';},
        'header' => '9',
        'headerOptions'=>['title'=>'Проектный контроль'],
	],
    [
        'label' => 'Технический_контроль',
        'value' => function ($model) {return isset($model->technical_lead_check) ? '-1' : '0';},
        'header' => '10',
        'headerOptions'=>['title'=>'Технический контроль'],
	],
    [
    	'label' => 'Результат',
    	'value' => 'result',
        'header' => '11',
        'headerOptions'=>['title'=>'Результат'],
    ],
    [
        'label' => 'Chel-chas fakt',
        'value' => 'elapsed',
        'header' => '12',
        'headerOptions'=>['title'=>'Трудозатраты факт.'],
    ],
];

$this->beginBlock('filter_date');
	$form = yii\widgets\ActiveForm::begin(['method' => 'get', 'action' => ['report/export']]);
	$periods = Task::find()->select(['period'=>"DATE_FORMAT(start,'%Y-%m-01')", 'year'=> 'YEAR(start)', 'month'=>'MONTH(start)'])
		->groupby(['YEAR(start)', 'MONTH(start)'])
		->orderby(['start'=>SORT_DESC])->asArray()->all();
	foreach ($periods as &$period)
		$period['month'] = \Yii::$app->formatter->asDate($period['period'], 'LLLL, yyyy');// . ', ' . $period['year'];

	$periods = yii\helpers\ArrayHelper::map($periods, 'period', 'month', 'year');

	echo $form->field($searchModel, 'period')->label(false)->dropDownList($periods, ['onchange'=>'this.form.submit()']);
	
	$form::end();
$this->endBlock();

$toolbar = [
	[// Search Button
		'content' => //$this->render('_filter_period', ['model' => $searchModel]),
			$this->blocks['filter_date'],
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
		'headerRowOptions' => ['class'=>'skip-export'], // hide header from export
    	'defaultPagination' => 'all', // show all data for export        
    ]); ?>

</div>