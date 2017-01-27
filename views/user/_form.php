<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

use kartik\widgets\Select2;
use kartik\detail\DetailView;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthAssignment;
use app\modules\projects\models\AuthUser;
use app\modules\projects\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?= DetailView::widget([
            'model' => $model,
            'condensed' => true,
            'hover' => true,
            'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel' => [
                'heading' => isset($model->id) ? Module::t('app', 'User `{user_id}`', ['user_id' => $model->id]) : Module::t('app', 'Create user'),
                'type' => DetailView::TYPE_PRIMARY,
                'headingOptions' => ['template'=> (\Yii::$app->user->can('admin')
                    || \Yii::$app->user->can('user_manager'))
                    ? '{buttons}{title}'
                    : '{title}'
                ],
            ],
            'deleteOptions' => [ // your ajax delete parameters
                'url'=>['delete', 'id' => $model->id],
                'params' => ['id' => $model->id, 'kvdelete'=>true],
            ],
            'attributes' => [
            /**
             * AuthUser
             */
                [
                    'group' => true,
                    'label' => Module::t('app', 'Credentials'),
                    'rowOptions' => ['class' => 'info']
                ],
                // login
                [
                    'attribute' => 'id',
                    'type' => 'textInput',
                    'widgetOptions' => ['maxlength' => true],
                ],
                // username
                [
                    'attribute' => 'username',
                    'type' => 'textInput',
                ],
                // password
                [
                    'attribute' => 'password',
                    'type' => 'textInput', //'passwordInput',
                ],
                // disabled state
                [
                    'attribute' => 'disabled',
                    'type' => DetailView::INPUT_SWITCH,
                    'format' => 'raw',
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'onText' => Module::t('app', 'Yes'),
                            'offText' => Module::t('app', 'No'),
                        ],
                    ],
                    'value' => $model->disabled
                        ? '<span class="label label-danger">'. Module::t('app', 'Yes') .'</span>'
                        : '<span class="label label-success">'. Module::t('app', 'No') .'</span>',
                ],
                
            /**
             * AuthProfile
             */
                [
                    'group' => true,
                    'label' => Module::t('app', 'Profile'),
                    'rowOptions' => ['class' => 'info']
                ],
                // profile->firstname
                [
                    'viewModel' => $model->profile, 'editModel' => $model->profile,
                    'attribute' => 'firstname',
                ],
                // profile->secondname
                [
                    'viewModel' => $model->profile, 'editModel' => $model->profile,
                    'attribute' => 'secondname',
                ],
                // profile->lastname
                [
                    'viewModel' => $model->profile, 'editModel' => $model->profile,
                    'attribute' => 'lastname',
                ],
                // profile->lastname
                [
                    'viewModel' => $model->profile,'editModel' => $model->profile,
                    'attribute' => 'email',
                ],
                // profile->roles
                [
                    'viewModel' => $model->profile, 'editModel' => $model->profile,
                    'label' => Module::t('app', 'Roles'),
                    'attribute' => 'roles',
                    'value' => isset ($model->profile->roles) ? implode(", ", array_keys($model->profile->roles)) : '',
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(AuthItem::find()
                            ->select(['id'=>'name', 'value'=>'description'])
                            ->where(['type' => 1])
                            ->andWhere(!Yii::$app->user->can('admin') ? ['!=', 'name', 'admin'] : '1=1')
                            ->asArray()->all(), 'id', 'value'),
                        'options' => ['multiple' => true],
                    ],
                ],
                // profile->lead_id
                [
                    'viewModel' => $model->profile, 'editModel' => $model->profile,
                    'label' => Module::t('app', 'Technical Lead'),
                    'attribute' => 'lead_id',
                    'value' => isset($model->profile->lead->username) ? $model->profile->lead->username : null,
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(AuthUser::find()
                            ->where(['id' => AuthAssignment::find()
                                    ->select(['id'=>'user_id', 'value'=>'user_id'])
                                    ->where(['item_name' => 'technical_lead'])
                                    ->asArray()->all()
                                ])
                            ->asArray()->all(), 'id', 'username'),
                        'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                    ],                    
                ],
                // profile->1C_id
                [
                    'viewModel' => $model->profile,'editModel' => $model->profile,
                    'label' => Module::t('app', 'BDDS ID'),
                    'attribute' => 'bdds_id',
                ],                
            ],
        ])
    ?>
    
</div>