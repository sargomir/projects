<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthProfile;

$notes = [];
    foreach ($model->notes as $note) {
        //$notes[] = "<div><div>{$note->created_at}</div><div>{$note->user_id}</div><div>{$note->note}</div></div>";
        $notes[] = "<div>[<i>{$note->created_at}</i>] <b>{$note->user->username}:</b> {$note->note}</div>";
    }
$notes = implode("", $notes);
?>

<style type="text/css">
    th {
        text-align: left;
        vertical-align: sub;
    }
</style>

<div class="project-form">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'project',
            'company',
            'project_lead.username',
            'active',
            ['attribute'=>'notes', 'value'=>$notes, 'format'=>'html'],
        ],
    ]) ?>

</div>