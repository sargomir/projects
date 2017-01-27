<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthProfile;
?>

<style type="text/css">
    th {
        text-align: left;
        vertical-align: sub;
    }
</style>

<div class="user-form">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'password',
        ],
    ]) ?>

</div>