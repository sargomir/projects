<?php

namespace app\modules\projects\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\Task;
use app\modules\projects\models\TaskSearch;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'roles' => ['worker'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create'],
                        'roles' => ['project_lead'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update'],
                        'roles' => [
                            'admin',
                            'worker_check',
                            'technical_lead_check',
                            'project_lead_check',
                            'project_manager_check',
                            'check_timeout',
                            ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['reassign'],
                        'roles' => [
                            'worker_check',
                        ],
                        'allow' => true,
                    ],                    
                    [
                        'actions' => ['delete'],
                        'roles' => ['admin'],
                        'allow' => true,
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
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $searchModel->status = [Task::Active, Task::Pending, Task::Overdue, Task::Completed, Task::Expired];
        $searchModel->period_start = date("01.m.Y");
        $searchModel->period_end = date("t.m.Y");
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
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
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Technical Lead can reassign task to subordinate worker
     * Updates task->worker_id
     */
    public function actionReassign($id)
    {
        $model = $this->findModel($id);
        
        $load_post = $model->load(Yii::$app->request->post());
        if (isset($model->worker)) $model->technical_lead_id = $model->worker->lead_id;
        
        if ($load_post && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id]);
        } else {
            if (Yii::$app->request->isAjax)
                return $this->renderAjax('reassign', ['model' => $model]);
            return $this->render('reassign', ['model' => $model]);
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