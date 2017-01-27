<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;

/**
 * MyActiveRecord extends ActiveRecord for history output and custom database settings
 */
class AuthActiveRecord extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->AuthManager->db;
    }
    
    /**
     * Send e-mail notice about changes if AuthProfile->email supplied
     */
    public function afterSave ( $insert, $changedAttributes )
    {
        parent::afterSave($insert, $changedAttributes);
        if ($mailer = Yii::$app->getModule('projects')->mailer) { // Check if mailer is set
            
            $user = Yii::$app->user;
            $profile = AuthProfile::findOne(['user_id'=>$user->id]);
            if (!$profile) return;
            $recepient = $profile->email;
            $subject = $textBody = $htmlBody = '';
            switch ( $this->tableName() ) {
                case '{{%auth_users}}' :
                    $profile = AuthProfile::findOne(['user_id'=>$this->id]);
                    if (!$profile) return;
                    $recepient = $profile->email;
                    $subject = Module::t('app', 'Project Notice Service: User credentials has been updated');
                    $textBody = Module::t('app', "Login: {login} \r\nUsername: {username} \r\nPassword: {password} \r\n",
                        ['login'=>$this->id, 'username'=>$this->username, 'password'=>$this->password]);
                    $htmlBody = Yii::$app->controller->renderPartial('/user/print', ['model'=>$this]);
            }
            
            $validator = new yii\validators\EmailValidator();
            if ($validator->validate($recepient, $error) && $subject && $textBody && $htmlBody)    
            $message = $mailer->compose()->setFrom('projects@npp-as.ru')
                ->setTo($recepient)
                ->setSubject($subject)
                ->setTextBody($textBody)
                ->setHtmlBody($htmlBody)
                ->send();
        }
    }    
}