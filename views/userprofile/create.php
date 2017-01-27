<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\UserProfiles */

$this->title = Yii::t('app', 'Create User Profiles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profiles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
