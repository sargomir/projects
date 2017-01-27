<?php

namespace app\modules\projects\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;

use app\modules\projects\Projects as Module;

use app\modules\projects\models\AuthUser;
use app\modules\projects\models\AuthUserSearch;
use app\modules\projects\models\AuthProfile;

/**
 * UserController implements the CRUD actions for AuthUser model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['department'],
                        'allow' => '*',
                    ],
                    [
                        'actions' => ['index', 'view', 'create', 'update'],
                        'roles' => ['user_manager'],
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if ($id == 'admin' && !Yii::$app->user->can('admin')) throw new \yii\web\HttpException(403, "You can't!" );
        
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        
        //// process ajax delete
        //if (isset($post['kvdelete'])) {
        //    //$user->delete();
        //    echo Json::encode([
        //        'success' => true,
        //        'messages' => [
        //            'kv-detail-info' => Module::t('app', 'User `{user_id}` was successfully deleted.', ['user_id' => $model->id])
        //        ]
        //    ]);
        //    return;
        //}
        
        // Roles
        $profile = $model->profile;
        $profile->load(Yii::$app->request->post());
        $profile->user_id = $id;
        $profile->save();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new AuthUser();
        $ok = $user->load(Yii::$app->request->post());
        $profile = $user->profile;
        $profile->user_id = $user->id; // if we created new auth_profile link it to auth_user.id
        $ok = $ok && $profile->load(Yii::$app->request->post());
        
        // Generate authKey and accessToken
        if (!isset ($user->authKey)) $user->authKey = \Yii::$app->security->generateRandomString();
        if (!isset ($user->accessToken)) $user->accessToken = \Yii::$app->security->generateRandomString();
        
        if ($ok)
        try {
            $transaction = $user->db->beginTransaction();
            if ($user->save())
                if ($profile->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $user->id]);   
                }
        } catch (Exception $e) {
            $transaction->rollback();
        }
        else return $this->render('create', ['model' => $user]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if ($id == 'admin' && !Yii::$app->user->can('admin')) throw new \yii\web\HttpException(403, "You can't!" );
        
        $user = $this->findModel($id);
        $post = Yii::$app->request->post();
        
        $ok = $user->load($post);
        $profile = $user->profile;
        $profile->user_id = $user->id; // if we created new auth_profile link it to auth_user.id
        $ok = $ok && $profile->load($post);
        
        if ($ok)
        try {
            $transaction = $user->db->beginTransaction();
            if ($user->save())
                if ($profile->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $user->id]);   
                }
        } catch (Exception $e) {
            $transaction->rollback();
        }
        else return $this->render('update', ['model' => $user]);
    }
    
    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == 'admin' && !Yii::$app->user->can('admin')) throw new \yii\web\HttpException(403, "You can't!" );
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}