<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\detail\DetailView;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthProfile;

/* @var $this yii\web\View */
/* @var $model app\modules\projects\models\Projects */
/* @var $form yii\widgets\ActiveForm */

$notes = [];
    foreach ($model->notes as $note) {
        //$notes[] = "<div><div>{$note->created_at}</div><div>{$note->user_id}</div><div>{$note->note}</div></div>";
        $notes[] = "<div>[<i>{$note->created_at}</i>] <b>{$note->user->username}:</b> {$note->note}</div>";
    }
$notes = implode("", $notes);

// Disable delete button. Deleting projects is not allowed
$this->registerCss('.kv-action-btn.kv-btn-delete{display: none;}');
?>

<div class="project-form">

    <?= DetailView::widget([
            'model' => $model,
            'condensed' => true,
            'hover' => true,
            'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel' => [
                'heading' => isset($model->project_id) ? Module::t('app', 'Project №{project_id}', ['project_id' => $model->project_id]) : Module::t('app', 'Create project'),
                'type' => DetailView::TYPE_PRIMARY,
                'headingOptions' => ['template'=> (\Yii::$app->user->can('admin') || \Yii::$app->user->can('project_manager'))
                    ? '{buttons}{title}'
                    : '{title}'],
            ],          
            'attributes' => [
                'project',
                'company',
                [// Project Lead
                    'label' => Module::t('app', 'Project Lead'),
                    'attribute' => 'project_lead_id',
                    'value' => isset ($model->project_lead->username) ? $model->project_lead->username : null,
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(AuthProfile::find()
                            ->joinWith(['assignments'])->where('item_name = "project_lead"')->all(), 'user_id', 'username'),
                            //->join('left join', 'auth_assignment', 'user_id = user_id')->where('item_name = "project_lead"')
                            //->orderBy('username')->all(), 'id', 'username'),
                        'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                        'disabled' => ! $model->isAttributeActive('project_lead_id'),
                    ],
                ],
                [// Project Manager (default)
                    'label' => Module::t('app', 'Project Manager'),
                    'attribute' => 'project_manager_id',
                    'value' => isset ($model->project_manager->username) ? $model->project_manager->username : null,
                    'type' => DetailView::INPUT_SELECT2,
                    'widgetOptions' => [
                        'data' => ArrayHelper::map(AuthProfile::find()
                            ->joinWith(['assignments'])->where('item_name = "project_manager"')->all(), 'user_id', 'username'),
                            //->join('left join', 'auth_assignment', 'user_id = user_id')->where('item_name = "project_lead"')
                            //->orderBy('username')->all(), 'id', 'username'),
                        'options' => ['placeholder' => Module::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                        'disabled' => ! $model->isAttributeActive('project_manager_id'),
                    ],
                ],                
                [// Active
                    'attribute' => 'active',
                    'format'=>'raw',
                    'type' => DetailView::INPUT_SWITCH,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'onText' => Module::t('app', 'Yes'),
                            'offText' => Module::t('app', 'No'),
                        ],
                        'disabled' => ! $model->isAttributeActive('active'),
                    ],
                    'value' => $model->active
                        ? '<span class="label label-success">'. Module::t('app', 'Yes') .'</span>'
                        : '<span class="label label-danger">'. Module::t('app', 'No') .'</span>',
                ],
                // Notes
                [
                    'label' => Module::t('app', 'Make note'),
                    'value' => $notes,
                    'format' => 'raw',
                    'type' => DetailView::INPUT_HIDDEN,
                            'widgetOptions' => ['disabled' => true],
                ],
                // BDDS ID
                'bdds_id',
            ],
        ]);
    ?>
</div>