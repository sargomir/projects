<?php

namespace app\modules\projects\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\modules\projects\models\Project;
use app\modules\projects\models\ProjectSearch;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                        'actions' => ['create', 'update'],
                        'roles' => ['project_manager'],
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
     * Lists all Tags models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $searchModel->active = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tags model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // We create ProjectNote if parameters supplied
        $note = new \app\modules\projects\models\ProjectNote();
        $note->project_id = Yii::$app->request->post('project_id');
        $note->user_id = Yii::$app->request->post('user_id');
        $note_created = $note->load(Yii::$app->request->post()) && $note->save();
        
        // Now we save Project if detailview updated
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->project_id]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Tags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->project_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // We create ProjectNote if parameters supplied
        $note = new \app\modules\projects\models\ProjectNote();
        $note->project_id = Yii::$app->request->post('project_id');
        $note->user_id = Yii::$app->request->post('user_id');
        $note_created = $note->load(Yii::$app->request->post()) && $note->save();
        
        // Now we save Project if detailview updated
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->project_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }    
    
    /**
     * Finds the Tags model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
