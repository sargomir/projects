<?php

use \yii\helpers\ArrayHelper;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\Project;

$form = yii\widgets\ActiveForm::begin(['method' => 'get']);
echo $form->field($model, 'project_id')->label(false)->dropDownList(
    ArrayHelper::map(Project::find()->asArray()->all(), 'project_id', 'project'),
    ['onchange'=>'this.form.submit()', 'prompt'=>'Все проекты']);
$form::end();
?>