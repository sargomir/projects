<?php

use yii\helpers\Html;
//use yii\grid\GridView;
//use kartik\grid\GridView;
use app\modules\projects\GridView;

use app\modules\projects\Projects as Module;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\ProjectPartsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Project Parts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-parts-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Module::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'parent_part_id',
                'value' => 'parent.part',
                'group' => true,
                //'groupedRow' => true,     
            ],
            'code',
            'part',
            'bdds_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        //'export' => false,
    ]); ?>

</div>