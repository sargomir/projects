<?php

namespace app\modules\projects;

use Yii;
use yii\rbac\DbManager;

class Projects extends \yii\base\Module
{
    public static $version = 1.9;
    
    public $controllerNamespace = 'app\modules\projects\controllers';
    
    public $company;
    
    public function init()
    {
        parent::init();
        
        // Overwrite error route so we will stay in module default controller and see module main.php layout
        //Yii::$app->errorHandler->errorAction = 'projects/default/error';
        Yii::$app->errorHandler->errorAction = 'projects/module/error';
        
        // Load module specified users
        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\projects\models\AuthUser',
            'enableAutoLogin' => false,
            //'loginUrl' => ['projects/default/login'],
            'loginUrl' => ['projects/module/login'],
            'identityCookie' => ['name' => 'editor', 'httpOnly' => true],
            'idParam' => 'easpm_id', //this is important !
        ]);
        
        // Load module specified rbac for users
        Yii::$app->set('authManager', [
            'class' => 'yii\rbac\DbManager',
            // Enumerate all roles as default because we have UserGroupRule with roles hierarchy
            'defaultRoles' => ['worker'],
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=192.168.0.21;dbname=auth',
                'username' => 'root',
                'password' => 'HSoJpGqji*',
                'charset' => 'utf8',
            ],

        ]);
        
        // Custom initialization code goes here
        $this->layout = 'main.php';
        $this->registerTranslations();
        
        // Set null display format
        Yii::$app->formatter->nullDisplay = '<span class="not-set">(нет)</span>';        
    }

    // Enable translations    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/projects/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/projects/messages',
            'fileMap' => [
                'modules/projects/app' => 'app.php',
            ],
        ];
    }
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/projects/' . $category, $message, $params, $language);
    }
    
    public function getMailer()
    {
        if (Yii::$app->getModule('projects')->has('mailer')) return Yii::$app->getModule('projects')->get('mailer');
        return false;
    }
}
