<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthProfile;

$notes = [];
foreach ($model->notes as $note) 
    $notes[] = "[{$note->created_at}] {$note->user->username}: {$note->note}" . PHP_EOL;
$notes = implode("", $notes);

echo "{$model->attributeLabels()['project']}: {$model->project}" . PHP_EOL;
echo "{$model->attributeLabels()['company']}: {$model->company}" . PHP_EOL;
echo "{$model->project_lead->attributeLabels()['username']}: {$model->project_lead->username}" . PHP_EOL;
echo "{$model->attributeLabels()['active']}: {$model->active}" . PHP_EOL;
echo "{$model->attributeLabels()['notes']}" . PHP_EOL . $notes;
?>

