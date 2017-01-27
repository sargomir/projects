<?php

namespace app\modules\projects\models;

use Yii;
use yii\validators\EmailValidator;

use app\modules\projects\Projects as Module;

/**
 * MyActiveRecord extends ActiveRecord for history output and custom database settings
 */
class MyActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Reset db to module specified
     */
    public static function getDb()
    {
        return Yii::$app->getModule('projects')->db;
    }
	
	/**
     * @inheritdoc
     * @return MyActiveQuery the active query used by AR class.
	 */
	public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }	
	
	/**
	 * Send e-mail message
	 * $recepients, array of string
	 * $subject, string
	 * $textBody, string
	 * $htmlBody, string with HTML formatting
	 * @return $message
	 */
//    public function send_message($recepients, $subject, $textBody, $htmlBody) {
//		if ($mailer = Yii::$app->getModule('projects')->mailer)
//			if (count($recepients) && $subject && $textBody && $htmlBody)    
//				$message = $mailer->compose()->setFrom(['projects@npp-as.ru'=>'СУЗП'])
//					->setTo($recepients)
//					->setBcc('projects@npp-as.ru')
//					->setSubject($subject)
//					->setTextBody($textBody)
//					->setHtmlBody($htmlBody)
//					->send();
//		return $message;
//	}	
    
    /**
     * Send e-mail notice about changes if AuthProfile->email supplied
     */
//    public function afterSave ( $insert, $changedAttributes )
//    {
//        parent::afterSave($insert, $changedAttributes);
//        
//        //if (Yii::$app->user->id !== 'admin')
//        if ($mailer = Yii::$app->getModule('projects')->mailer) { // Check if mailer is set
//            
//            $user = Yii::$app->user;
//            $recepients = []; //AuthProfile::findOne(['user_id'=>$user->id])->email;
//            $subject = $textBody = $htmlBody = '';
//            switch ( $this->tableName() ) {
//                case '{{%tasks}}' :
//                    $validator = new EmailValidator();
//                    if ($recepient = AuthProfile::findOne(['user_id'=>$this->worker_id]))
//                        if ($validator->validate($recepient->email, $error)) $recepients[] = $recepient->email;
//                    if ($recepient = AuthProfile::findOne(['user_id'=>$this->technical_lead_id]))
//                        if ($validator->validate($recepient->email, $error)) $recepients[] = $recepient->email;                    
//                    if ($recepient = AuthProfile::findOne(['user_id'=>$this->project_lead_id]))
//                        if ($validator->validate($recepient->email, $error)) $recepients[] = $recepient->email;                    
//                    //$recepients = ['admin@npp-as.ru'];
//                    $subject = $insert 
//                    	? Module::t('app', 'Project Notice Service: Task #{task_id} has been created', ['task_id'=>$this->task_id])
//                    	: Module::t('app', 'Project Notice Service: Task #{task_id} has been updated', ['task_id'=>$this->task_id]);
//                    $textBody = Yii::$app->controller->renderPartial('/task/text', ['model'=>$this, 'changedAttributes'=>$changedAttributes]);
//                    $htmlBody = Yii::$app->controller->renderPartial('/task/print', ['model'=>$this, 'changedAttributes'=>$changedAttributes]);
//					$this->send_message($recepients, $subject, $textBody, $htmlBody);
//					
//					// Check for budget ratio
//					$budget = \app\modules\projects\models\Budget::findOne(['project_id'=>$this->project_id, 'project_part_id'=>$this->project_part->parent_part_id]);
//					if (isset ($budget))
//					if (($budget->ratio >= 75 && $budget->flag1 == false)
//						|| ($budget->ratio >= 100 && $budget->flag2 ==false)
//					) {
//						/**
//						 * We send message only once for every flag change
//						 */
//						if ($budget->ratio < 100) {
//							$budget->flag1 = true;
//							$budget->save();
//						} else {
//							$budget->flag1 = $budget->flag2 = true;
//							$budget->save();
//						}
//						
//						$recepients = [
//							//'admin@npp-as.ru'		=> 'Админ',
//							'teplyh@enertek.ru'		=> 'Теплых Наталья Николаевна',
//							'kozorez@enertek.ru'	=> 'Козорез Наталия Анатольевна',
//							'protasov@enertek.ru'	=> 'Протасов Анатолий Юрьевич',
//							'avandeev@enertek.ru'	=> 'Авандеев Сергей Николаевич',
//						];
//						$subject = Module::t('app', 'Budget ratio for project `{project_name}` on `{project_part}` is {budget_ratio}%',
//							[
//								'project_name' => $budget->project->project,
//								'project_part' => $budget->project_part->part,
//								'budget_ratio' => number_format($budget->ratio, 2)
//							]
//						);
//						$textBody = $htmlBody = $subject;
//						$this->send_message($recepients, $subject, $textBody, $htmlBody);
//					}
//                    break;
//                case '{{%projects}}' :
//                    $validator = new EmailValidator();
//                    $recepient = AuthProfile::findOne(['user_id'=>$this->project_lead_id])->email;
//                    if ($validator->validate($recepient, $error)) $recepients[] = $recepient;
//                    
//                    $subject = $insert
//                    	? Module::t('app', 'Project Notice Service: Project #{project_id} has been created', ['project_id'=>$this->project_id])
//                    	: Module::t('app', 'Project Notice Service: Project #{project_id} has been updated', ['project_id'=>$this->project_id]);
//                    $textBody = Yii::$app->controller->renderPartial('/project/text', ['model'=>$this]);
//                    $htmlBody = Yii::$app->controller->renderPartial('/project/print', ['model'=>$this]);
//					$this->send_message($recepients, $subject, $textBody, $htmlBody);
//                    break;
//            }
//        }
//    }
}
