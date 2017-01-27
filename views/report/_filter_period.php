<?php

use yii\helpers\Html;
//use kartik\select2\Select2;
//use yii\grid\GridView;
use app\modules\projects\Projects as Module;

$form = yii\widgets\ActiveForm::begin(['method' => 'get', 'action' => ['report/worker'],]);
//echo $form->field($model, 'period')->label(false)->widget(Select2::classname(), [
//        'data' => [
//            '0' => 'За всё время',
//            '1' => 'Текущий месяц',
//            '2' => 'Предыдущий месяц',
//            '3' => 'Последние 3 месяца'
//        ],
//        'options' => ['onchange'=>'this.form.submit()'],
//        'addon' => [
//            'prepend' => [
//                'content' => 'Период',  
//            ],
//        ]
//    ]);
echo $form->field($model, 'period')->label(false)->dropDownList([
        '0' => 'За всё время',
        '1' => 'Текущий месяц',
        '2' => 'Предыдущий месяц',
        '3' => 'Последние 3 месяца',
    ], ['onchange'=>'this.form.submit()']);
$form::end();
?>