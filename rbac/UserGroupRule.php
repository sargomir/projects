<?php
namespace app\modules\projects\rbac;
 
use Yii;
use yii\rbac\Rule;

/**
 * Чтобы все это хозяйство работало делаем следущее:
 * 1). В AuthManager перечисляем defaulRoles все доступные роли,
 * чтобы для всех них попадать сюда при проверке прав доступа.
 * Именно здесь мы будем решать как эти роли пересекаются между собой.
 * 2). В контроллере access->rules прописываем каждому методу
 * минимальную роль для выполнения.
 * 3). Дописываем иерархическое перекрытие ролей в методе execute(...)
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';
 
    public function execute($user, $item, $params)
    {
        \Yii::trace($user, 'rbac');
        \Yii::trace($item, 'rbac');
        \Yii::trace($params, 'rbac');

        $user = \Yii::$app->user;
        $roles = \Yii::$app->authManager->getRolesByUser(\Yii::$app->user->id);
        
        if (!$user->isGuest) {
            switch ($item->name) {
                case 'admin' :
                    return isset($roles['admin']);
                case 'user_manager' :
                    return isset($roles['admin']) || isset($roles['user_manager']);
                case 'project_manager' :
                    return isset($roles['admin']) || isset($roles['project_manager']);
                case 'technical_lead' :
                    return isset($roles['admin']) || isset($roles['technical_lead']);
                case 'project_lead' :
                    return isset($roles['admin']) || isset($roles['project_lead']);
                case 'worker' :
                    return isset($roles['admin'])
                    || isset($roles['user_manager'])
                    || isset($roles['project_manager'])
                    || isset($roles['technical_lead'])
                    || isset($roles['project_lead'])
                    || isset($roles['worker']);
                case 'worker_check' :
                    if ($task = $this->GetCurrentTask($params))
                        return $task->worker_id === $user->id
                            && !isset($task->technical_lead_check)
                            && !isset($task->project_lead_check)
                            && !isset($task->project_manager_check)
                            && $task->active;
                    return false;
                case 'technical_lead_check' :
                    if ($task = $this->GetCurrentTask($params))
                        return $task->technical_lead_id === $user->id
                            && isset($task->worker_check)
                            && !isset($task->project_lead_check)
                            && !isset($task->project_manager_check)
                            && $task->active;
                    return false;
                case 'project_lead_check' :
                    if ($task = $this->GetCurrentTask($params))
                        return $task->project_lead_id === $user->id
                            && isset($task->worker_check)
                            && (isset($task->technical_lead_check) || !isset($task->technical_lead_id))
                            && !isset($task->project_manager_check)
                            && $task->active;
                    return false;
                case 'project_manager_check' :
                    if ($task = $this->GetCurrentTask($params))
                        return isset($task->worker_check)
                            && (isset($task->technical_lead_check) || !isset($task->technical_lead_id))
                            && isset($task->project_lead_check)
                            && $task->active;
                    return false;
                case 'check_timeout' :
                    if ($task = $this->GetCurrentTask($params))
                        return (date("Y-m-d H:i:s", strtotime("-1 day")) < $task->start || date("Y-m-d H:i:s", strtotime("-1 day")) < $task->created_at)
                            && ($task->project_lead_id === $user->id || $user->can('project_manager'))
                            && !isset($task->worker_check)
                            && !isset($task->elapsed)
                            && !isset($task->technical_lead_check)
                            && !isset($task->project_lead_check)
                            && !isset($task->project_manager_check)
                            && $task->active;
                    return true;
                default :
                    return false;
            }
        }
        
        return false;
    }
    
    /**
     * return @Tasks
     */
    private function GetCurrentTask($params) {
        
        if (($task_id = isset($params['task_id']) ? $params['task_id'] : \Yii::$app->request->get('id')) !== null) 
            if (($task = \app\modules\projects\models\Task::findOne($task_id)) !== null)
                return $task;
        return null;
    }
}