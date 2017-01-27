<?php

namespace app\modules\projects\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;

use app\modules\projects\models\AuthProfile;
use app\modules\projects\models\Project;

/**
 * DepdropController implements actions for Dependant Dropdown Lists in views _form.php
 */
class DepdropController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['workerstechlead', 'projectslead', 'projectsmanager'],
                        'allow' => '*',
                    ],
                ],
            ],              
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * This if for depentant dropdownlist in task/_form
     * When worker_id is selected, default tech_lead_id must be set
     */
    public function actionWorkerstechlead()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $worker_id = $parents[0];
     
                $technical_leaders = AuthProfile::find()->joinWith(['assignments'])->where(['item_name'=>'technical_lead'])
                ->orderBy(['lastname'=>SORT_ASC])
                ->all();
                
                $out = [];
                foreach ($technical_leaders as $profile)
                    $out[] = ['id' => $profile->user_id, 'name'=>$profile->username];
                    
                //$out = AuthProfile::find()->select(['id'=>AuthProfile::TableName().'.`user_id`', 'name'=>'firstname'])
                //    ->joinWith(['assignments'])
                //    ->where('item_name = "technical_lead"')
                //    ->createCommand()->queryAll();

                //var_dump($out);
                //: AuthProfile::find()->select(['id' => 'user_profiles.lead_id', 'name'=>'user_profiles.lead_id'])
                //    ->joinWith('profile')
                //    ->where(['id'=> $worker_id])->asArray()->all();
                
                $selected = AuthProfile::find()->where(['user_id'=> $worker_id])->one()->lead_id;
                
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    
    /**
     * This if for depentant dropdownlist in task/_form
     * When project_id is selected, default project_lead_id must be set
     */
    public function actionProjectslead()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $project_id = $parents[0];
     
                $project_leaders = AuthProfile::find()->joinWith(['assignments'])->where(['item_name'=>'project_lead'])
                ->orderBy(['lastname'=>SORT_ASC])
                ->all();
                
                $out = [];
                foreach ($project_leaders as $profile)
                    $out[] = ['id' => $profile->user_id, 'name'=>$profile->username];
     
                //$out = AuthProfile::find()->select(['id'=>AuthProfile::TableName().'.`user_id`', 'name'=>'firstname'])
                //    ->joinWith(['assignments'])
                //    ->where('item_name = "project_lead"')
                //    ->createCommand()->queryAll();
                
                $selected = Project::find()->where(['project_id'=> $project_id])->one()->project_lead_id;
                
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    
    /**
     * This if for depentant dropdownlist in task/_form
     * When project_id is selected, default project_manager_id must be set
     */
    public function actionProjectsmanager()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $project_id = $parents[0];
     
                $project_managers = AuthProfile::find()->joinWith(['assignments'])->where(['item_name'=>'project_manager'])
                ->orderBy(['lastname'=>SORT_DESC])
                ->all();
                
                $out = [];
                foreach ($project_managers as $profile)
                    $out[] = ['id' => $profile->user_id, 'name'=>$profile->username];
     
                //$out = AuthProfile::find()->select(['id'=>AuthProfile::TableName().'.`user_id`', 'name'=>'firstname'])
                //    ->joinWith(['assignments'])
                //    ->where('item_name = "project_lead"')
                //    ->createCommand()->queryAll();
                
                $selected = false;
                if (isset ($out[0]['id']))
                    $selected = $out[0]['id'];
                if (isset (Project::find()->where(['project_id'=> $project_id])->one()->project_manager_id))
                    $selected = Project::find()->where(['project_id'=> $project_id])->one()->project_manager_id;
                
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }    
}