<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%Tasks}}".
 *
 * @property string $task_id
 * @property string $project_id
 * @property string $project_part_id
 * @property string $task
 * @property string $result
 * @property string $estimated
 * @property string $elapsed
 * @property string $start
 * @property string $deadline
 * @property string $worker_id
 * @property string $worker_check
 * @property string $technical_lead_id
 * @property string $technical_lead_check
 * @property string $project_lead_id
 * @property string $project_lead_check
 * @property string $project_lead_check
 * @property string $project_manager_id
 * @property string $project_manager_check
 * @property string $created_at
 * 
 * @property ProjectParts $projectPart
 * @property Projects $project
 */
class Task extends MyActiveRecord
{
    /**
     * Task status constants
     */
    const Active    = 1;
    const Pending   = 2;
    const Completed = 3;
    const Overdue   = -1;
    const Expired   = -2;
    
    /**
     * Attributes
     */
    public $status; // This attribute is calculated via SQL in TaskSearch model
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tasks}}';
    }
    
    /**
     * Set default values
     */
    public function init()
    {
        parent::init();
        $this->project_manager_id = 'protasov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $user = Yii::$app->user;
        $create = Yii::$app->controller->action->id === 'create';
        $update = Yii::$app->controller->action->id === 'update';
        $reassign = Yii::$app->controller->action->id === 'reassign';
        
        if ($user->can('admin'))
        // Admin
            return [
                [['project_id', 'project_part_id', 'task', 'result', 'start', 'deadline'], 'required'],
                //, 'when' => function($create) { return $create; }, 'whenClient' => "function (attribute, value) { return {$create}; }"],
                [['project_id', 'project_part_id'], 'integer'],
                [['estimated', 'elapsed'], 'integer', 'min' => 1, 'max' => 744],
                [['task'], 'string', 'max' => 500],
                [['result'], 'string', 'max' => 200],
                [['worker_check_bool', 'technical_lead_check_bool', 'project_lead_check_bool', 'project_manager_check_bool'], 'default', 'value' => null],
                [['worker_id', 'project_lead_id', 'technical_lead_id', 'project_manager_id'], 'string', 'max' => 64],
                [['start', 'deadline'], 'date', 'format' => 'dd.mm.yyyy'/*'%d.%M.%Y'*/],
            ];
        else if ($user->can('project_manager_check'))
        // Project Manager
            return [
                [['project_manager_check_bool'], 'required'],
                [['project_manager_check_bool'], 'default', 'value' => null],
            ];
        else if ($user->can('project_lead_check'))
        // Project Lead
            return [
                [['project_lead_check_bool'], 'required'],
                [['project_lead_check_bool'], 'default', 'value' => null],
            ];
        else if ($user->can('technical_lead_check'))
        // Technical Lead
            return [
                [['technical_lead_check_bool'], 'required'],
                [['technical_lead_check_bool'], 'default', 'value' => null],
            ];
        else if ($user->can('worker_check'))
        // Worker
            return ($user->can('technical_lead') && $reassign)
            ? [// Technical Leader can reassign task to subordinate
                [['worker_id', 'technical_lead_id'], 'string', 'max' => 64],
            ]
            : [// Update task as worker
                [['elapsed', 'worker_check_bool'], 'required'],
                [['elapsed'], 'integer', 'min' => 1, 'max' => 744],
                [['worker_check_bool'], 'default', 'value' => null],
            ];
        else if ($user->can('check_timeout'))
        // Project Lead update task
            return [
                [['project_id', 'project_part_id', 'task', 'result', 'estimated', 'worker_id', 'start', 'deadline'/*, 'project_lead_id', 'technical_lead_id', 'project_manager_id'*/], 'required'],
                [['project_id', 'project_part_id'], 'integer'],
                [['estimated'], 'integer', 'min' => 1, 'max' => 744],
                ['task', // Check if budget exceeded
                    function($attribute, $params) {
                        $budget = Budget::findOne(['project_id'=>$this->project_id, 'project_part_id'=>$this->project_part->parent_part_id]);
                        $task = Task::findOne(['task_id'=>$this->task_id]);
                        $task_old_estimated = isset($task) ? $task->estimated : 0;
                        if (!isset($budget))
                            $this->addError($attribute,
                                Module::t('app', 'Project`s budget is not set. Contact project manager.')
                            );
                        if (isset($budget))
                            if ($budget->used - $task_old_estimated + $this->estimated > $budget->budget * 1.05)
                                $this->addError($attribute,
                                    Module::t('app', 'Budget exceeded. Defined: {budget}. Used: {used}.',
                                        ['budget' => $budget->budget, 'used' => $budget->used]
                                    )
                                );
                    },
                    //'when'=> true,
                    //function() {
                    //    return ! Yii::$app->user->can('project_manager');  
                    //},
                    'skipOnEmpty' => false, 'skipOnError' => false
                ],                  
                [['task'], 'string', 'max' => 500],
                [['result'], 'string', 'max' => 200],
                [['worker_id', 'project_lead_id', 'technical_lead_id', 'project_manager_id'], 'string', 'max' => 64],
                [['start', 'deadline'], 'date', 'format' => 'dd.mm.yyyy'/*'%d.%M.%Y'*/], //'dd.mm.yyyy'
                ['deadline', // Check correct deadline date
                    function($attribute, $params) {
                        $start = strtotime ($this->start);
                        $deadline = strtotime ($this->deadline);
                        $deadline_max = strtotime (date ('t.m.Y', $start));
                        if ($start > $deadline)
                            $this->addError($attribute,
                                Module::t('app', 'Tasks "Deadline" ({deadline}) must be after "Start" ({start}).',
                                    ['deadline' => date('d.m.Y', $deadline), 'start' => date('d.m.Y', $start)]
                                )
                            );
                        if ($deadline > $deadline_max)
                            $this->addError($attribute,
                                Module::t('app', 'Task "Deadline" must be within the same month as "Start".')
                            );
                    },
                    'skipOnEmpty' => false, 'skipOnError' => false
                ],
            ];
        
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TaskID' => Module::t('app', 'Task ID'),
            'project_id' => Module::t('app', 'Project ID'),
            'project_part_id' => Module::t('app', 'Project Part ID'),
            'task' => Module::t('app', 'Task'),
            'result' => Module::t('app', 'Result'),
            'estimated' => Module::t('app', 'Estimated'),
            'elapsed' => Module::t('app', 'Elapsed'),
            'start' => Module::t('app', 'Start'),
            'deadline' => Module::t('app', 'Deadline'),
            'worker_id' => Module::t('app', 'Worker'),
            'worker_check' => Module::t('app', 'Worker Check'),
            'technical_lead_id' => Module::t('app', 'Technical Lead'),
            'technical_lead_check' => Module::t('app', 'Technical Lead Check'),
            'project_lead_id' => Module::t('app', 'Project Lead'),
            'project_lead_check' => Module::t('app', 'Project Lead Check'),
            'project_manager_id' => Module::t('app', 'Project Manager'),
            'project_manager_check' => Module::t('app', 'Project Manager Check'),
            
            'active' => Module::t('app', 'Active'),
            'notes' => Module::t('app', 'Notes'),
        ];
    }
    
    /**
     * Convert date fields to correct timestamp
     */
    public function behaviors()
    {
        return [
            'timestamp_deadline' => [// Convert dd.mm.yyyy to timestamp before save to db
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    // tasks->deadline
                    MyActiveRecord::EVENT_BEFORE_INSERT => ['deadline'],
                    MyActiveRecord::EVENT_BEFORE_UPDATE => ['deadline'],
                ],
                'value' => function() { return \Yii::$app->formatter->asDatetime($this->deadline, 'php:Y-m-d'); },
            ],
            'timestamp_start' => [// Convert dd.mm.yyyy to timestamp before save to db
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    // tasks->start
                    MyActiveRecord::EVENT_BEFORE_INSERT => 'start',
                    MyActiveRecord::EVENT_BEFORE_UPDATE => 'start',
                ],
                'value' => function() { return \Yii::$app->formatter->asDatetime($this->start, 'php:Y-m-d'); },
            ],            
            //'timestamp' => [// Convert timestamp to dd.mm.yyyy after load from db
            //    'class' => TimestampBehavior::className(),
            //    'attributes' => [
            //        // tasks->deadline
            //        MyActiveRecord::EVENT_AFTER_FIND => 'deadline',
            //    ],
            //    'value' => function() { return \Yii::$app->formatter->asDatetime($this->deadline, 'php:d.m.Y'); },
            //],
        ];
    }


    public function beforeSave($insert)
    {
        $user = Yii::$app->user;
        if (parent::beforeSave($insert)) {
            //if ($user->can('check_timeout')) {
            //    $this->project_lead_id = $user->id;
            //    $this->technical_lead_id = $this->worker->lead->user_id;
            //}
            //if ($user->can('project_manager_check'))
                //$this->project_manager_id = $user->id;
     
            return true;
        }
        return false;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject_part()
    {
        return $this->hasOne(ProjectPart::className(), ['part_id' => 'project_part_id']);
        //$project_part = $this->hasOne(ProjectPart::className(), ['part_id' => 'project_part_id']);
        //if ($project_part->count()) return $project_part;
        //else return new ProjectPart();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['project_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        if ($w = AuthProfile::findOne(['user_id'=>$this->worker_id])) return $w;
        return new AuthProfile();
    
        //return AuthProfile::findOne(['user_id'=>$this->worker_id]);
        //return $this->hasOne(AuthProfile::className(), ['user_id' => 'worker_id'])->from(AuthProfile::tableName() . ' WK');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject_lead()
    {
        if ($pl = AuthProfile::findOne(['user_id'=>$this->project_lead_id])) return $pl;
        return new AuthProfile();
        //return AuthProfile::findOne(['user_id'=>$this->project_lead_id]);
        //return $this->hasOne(AuthProfile::className(), ['user_id' => 'project_lead_id'])->from(AuthProfile::tableName() . ' PL');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTechnical_lead()
    {
        if ($tl = AuthProfile::findOne(['user_id'=>$this->technical_lead_id])) return $tl;
        return new AuthProfile();
    
        //return AuthProfile::findOne(['user_id'=>$this->technical_lead_id]);
        //return $this->hasOne(AuthProfile::className(), ['user_id' => 'technical_lead_id'])->from(AuthProfile::tableName() . ' TL');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject_manager()
    {
        if ($pm = AuthProfile::findOne(['user_id'=>$this->project_manager_id])) return $pm;
        return new AuthProfile();
    
        //return AuthProfile::findOne(['user_id'=>$this->project_manager_id]);
        //return $this->hasOne(AuthProfile::className(), ['user_id' => 'project_manager_id'])->from(AuthProfile::tableName() . ' PM');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotes()
    {
        return $this->hasMany(TaskNote::className(), ['task_id' => 'task_id']);
    }

    /**
     * Check if task is active or overdue
     * @return boolean
     */
    public function getActive()
    {
        return date("Y-m-d H:i:s", strtotime("-5 day")) < date("Y-m-t 20:00:00", strtotime($this->start));
    }
    
    /**
     * Virtual attributes worker_check_bool, technical_lead_check_bool, project_lead_check_bool and project_manager_check_bool
     * They represent date of check as boolean switch
     */
    public function getWorker_check_bool() { return isset ($this->worker_check); }
    public function setWorker_check_bool($value) { if ($this->worker_check_bool != $value) $this->worker_check = $value !== "0" ? date('Y-m-d H:i:s') : null; }
    public function getTechnical_lead_check_bool() { return isset ($this->technical_lead_check); }
    public function setTechnical_lead_check_bool($value) { if ($this->technical_lead_check_bool != $value) $this->technical_lead_check = $value !== "0" ? date('Y-m-d H:i:s') : null; }
    public function getProject_lead_check_bool() { return isset ($this->project_lead_check); }
    public function setProject_lead_check_bool($value) { if ($this->project_lead_check_bool != $value) $this->project_lead_check = $value !== "0" ? date('Y-m-d H:i:s') : null; }
    public function getProject_manager_check_bool() { return isset ($this->project_manager_check); }
    public function setProject_manager_check_bool($value) { if ($this->project_manager_check_bool != $value) $this->project_manager_check = $value !== "0" ? date('Y-m-d H:i:s') : null; }

    /**
     * Список проектов, отфильтрованный для текущего пользователя и этапа задачи
     * @return array [$project_id => $project]
     */
    function getFilteredProjectList() {
        $user = Yii::$app->user;
        $projects = Project::find()->orderBy(['project'=>SORT_DESC]);
        if ($user->can('check_timeout', ['task_id' => $this->task_id]) && !($user->can('admin') || $user->can('project_manager')))
            $projects->where(['project_lead_id' => $user->id, 'active'=>1]);
        return \yii\helpers\ArrayHelper::map($projects->asArray()->all(), 'project_id', 'project');
    }
}