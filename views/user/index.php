<?php

use app\modules\projects\Projects as Module;
use app\modules\projects\GridView;

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\projects\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Module::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            //'password',
            [
                'label' => Module::t('app', 'Roles'),
                'value' => function($model) {
                    return implode(', ', array_keys($model->profile->roles));  
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>