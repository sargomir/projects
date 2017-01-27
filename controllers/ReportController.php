<?php

namespace app\modules\projects\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\ReportProjectSearch;
use app\modules\projects\models\ReportWorkerSearch;
use app\modules\projects\models\ReportTaskSearch;
use app\modules\projects\models\ReportAccountingSearch;
use app\modules\projects\models\ReportExportSearch;
use app\modules\projects\models\ReportDailySearch;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            //'access' => [
            //    'class' => AccessControl::className(),
            //    'rules' => [
            //        [
            //            'actions' => ['index', 'view'],
            //            'roles' => ['worker'],
            //            'allow' => true,
            //        ],
            //    ],
            //],            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * Daily report
     */
    public function actionDaily()
    {
        $searchModel = new ReportDailySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $htmlBody = $this->renderAjax('daily', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        $recepients = [
            'admin@npp-as.ru'		=> 'Админ',
            //'teplyh@enertek.ru'		=> 'Теплых Наталья Николаевна',
            //'kozorez@enertek.ru'	=> 'Козорез Наталия Анатольевна',
            //'protasov@enertek.ru'	=> 'Протасов Анатолий Юрьевич',
            //'avandeev@enertek.ru'	=> 'Авандеев Сергей Николаевич',
        ];
        $subject = 'Отчет от "'. date("d.m.Y") .'" по текущим задачам';
        $textBody = 'Для просмотра отчета необходима поддержка разметки html.';
    
    
//    	if ($mailer = Yii::$app->getModule('projects')->mailer)
//			if (count($recepients) && $subject && $textBody && $htmlBody)    
//				$message = $mailer->compose()->setFrom(['projects@npp-as.ru'=>'СУЗП'])
//					->setTo($recepients)
//					//->setBcc('projects@npp-as.ru')
//					->setSubject($subject)
//					->setTextBody($textBody)
//					->setHtmlBody($htmlBody)
//					->send();
		return $htmlBody;
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionProject()
    {
        $searchModel = new ReportProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('project', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionWorker()
    {
        $searchModel = new ReportWorkerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('worker', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);        
    }
    
    public function actionTask()
    {
        $searchModel = new ReportTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('task', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);              
    }
    
    public function actionAccounting()
    {
        $searchModel = new ReportAccountingSearch();
        
        // Show last closed period
        $searchModel->period_start = date("01.m.Y", strtotime("-1 month"));//date("01.m.Y");
        $searchModel->period_end = date("t.m.Y", strtotime("-1 month"));//date("t.m.Y");
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('accounting', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);             
    }
    
    public function actionExport()
    {
        $searchModel = new ReportExportSearch();
        $searchModel->period = date('Y-m-01', strtotime("-1 month"));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('export', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);             
    }

    /**
     * Displays a single Task model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // We create TaskNote if parameters supplied
        $note = new \app\modules\projects\models\TaskNote();
        $note->task_id = Yii::$app->request->post('task_id');
        $note->user_id = Yii::$app->request->post('user_id');
        $note_created = $note->load(Yii::$app->request->post()) && $note->save();

        // Now we save Task if detailview updated
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}