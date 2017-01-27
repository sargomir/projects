<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\Task;

/**
 * TaskSearch represents the model behind the search form about `app\modules\projects\models\Task`.
 */
class TaskSearch extends Task
{
	public $task;
	public $worker;
	public $technical_lead;
	public $project_lead;
	public $project_manager;
    public $period_start = "";
    public $period_end = "";
    public $active = "";
    public $status;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'project_id', 'project_part_id', 'estimated', 'elapsed'], 'integer'],
            [['worker_check', 'project_lead_check', 'technical_lead_check', 'worker_id', 'project_lead_id', 'technical_lead_id'], 'safe'],
            [['period_start', 'period_end', 'active', 'status', 'task', 
            	'worker', 'technical_lead', 'project_lead', 'project_manager'], 'safe'],
        ];
    }
	
	public function attributeLabels()
    {
        return [
            'period_start' => Module::t('app', 'Period Start'),
            'period_end' => Module::t('app', 'Period End'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Task::find();

        $completed	= "(NOT ISNULL(tasks.worker_check))";
        $tlcheck	= "(NOT ISNULL(tasks.technical_lead_check))";
        $plcheck	= "(NOT ISNULL(tasks.project_lead_check))";
        $pmcheck	= "(NOT ISNULL(tasks.project_manager_check))";
        $accepted	= "({$tlcheck} AND {$plcheck} AND {$pmcheck})";
        $overdue	= "(NOW() > DATE_ADD(tasks.deadline, INTERVAL 18 HOUR))";
        $active		= "(tasks.start>=DATE_ADD(DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY)),INTERVAL 1 DAY),INTERVAL -1 MONTH))";
        $status		= "(IF ({$completed} AND {$accepted}, 3,
							IF (NOT {$active}, -2,
								IF (NOT {$completed} AND {$overdue}, -1,
									IF (NOT {$completed} AND {$active}, 1, 2)
								)
							)
						))";
            
        // Select calculated fields to define task status
        $query->select([
            '*',
        	'status' => $status,
//             'completed' => '@completed := (NOT ISNULL(tasks.worker_check))',
//             'technical_lead_check_bool' => '@tlcheck := (NOT ISNULL(tasks.technical_lead_check))',
//             'project_lead_check_bool' => '@plcheck := (NOT ISNULL(tasks.project_lead_check))',
//             'project_manager_check_bool' => '@pmcheck := (NOT ISNULL(tasks.project_manager_check))',
//             'accepted' => '@accepted := (@tlcheck AND @plcheck AND @pmcheck)',
//             'overdue' => '@overdue := (NOW() > DATE_ADD(tasks.deadline, INTERVAL 18 HOUR))',
//             'active' => '@active := (tasks.created_at>=DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))',
//             'status' => '@status := (
//                 IF (@completed AND @accepted, 3, 
//                     IF (@completed AND NOT @accepted, 2, 
//                         IF (NOT @completed AND @overdue, -1,
//                             IF (NOT @completed AND @active, 1, -2)
//                         )
//                     )
//                 )
//             )',
        ]);      
        
        //Filter data
        $user = Yii::$app->user;
        $user_id = Yii::$app->user->id;
        if (! $user->can('admin') && ! $user->can('project_manager')) {
            if (Yii::$app->user->can('project_lead'))
                $query->orWhere(['project_lead_id' => $user_id]); //->joinWith(['project'])->where(['projects.project_lead_id' => $user_id]);
            if (Yii::$app->user->can('technical_lead'))
                $query->orWhere(['technical_lead_id' => $user_id]); //->joinWith(['technical_lead'])->where(['user_id' => $user_id]);
            if (Yii::$app->user->can('worker'))
                $query->orWhere(['worker_id' => $user_id]);
        }
                    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sorted_logins = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
		$sorted_projects = array_column (Project::find()->select(['project_id'])->orderBy(['project' => 'asc'])->asArray()->all(), 'project_id');
		$sorted_project_parts = array_column (ProjectPart::find()->select(['part_id'])->orderBy(['part' => 'asc'])->asArray()->all(), 'part_id');
        $dataProvider->setSort([
            'attributes' => [
				'project_id' => [
                    'asc' => [ new Expression( "FIELD (project_id, '" . implode("','", $sorted_projects) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (project_id,'" . implode("','", array_reverse($sorted_projects)) . "')" ) ],
                    'default' => SORT_ASC
				],
				'project_part_id' => [
                    'asc' => [ new Expression( "FIELD (project_part_id, '" . implode("','", $sorted_project_parts) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (project_part_id,'" . implode("','", array_reverse($sorted_project_parts)) . "')" ) ],
                    'default' => SORT_ASC
				],
                'task',
                'start',
                'deadline',
                'estimated',
                'elapsed',
                'worker' => [
                    'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'technical_lead' => [
                    'asc' => [ new Expression( "FIELD (technical_lead_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (technical_lead_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'project_lead' => [
                    'asc' => [ new Expression( "FIELD (project_lead_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (project_lead_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'project_manager' => [
                    'asc' => [ new Expression( "FIELD (project_manager_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (project_manager_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'status'                
            ],
            'defaultOrder' => [
                'deadline' => SORT_ASC
            ],
        ]);         
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'task_id' => $this->task_id,
            'project_id' => $this->project_id,
            'project_part_id' => $this->project_part_id,
            'estimated' => $this->estimated,
            'elapsed' => $this->elapsed,
            'worker_id' => $this->worker,
            'worker_check' => $this->worker_check,
            'technical_lead_id' => $this->technical_lead,
            'technical_lead_check' => $this->technical_lead_check,
        	'project_lead_id' => $this->project_lead,
            'project_lead_check' => $this->project_lead_check,
        	'project_manager_id' => $this->project_manager,
            'project_manager_check' => $this->project_manager_check,
        ]);
        
        $query->andFilterWhere(['like', 'task', $this->task]);
        $query->orFilterWhere(['task_id' => $this->task]);

        /**
         * Filter date period.
         * Default empty parameter is ""
         * But we need to set it null for datepicker to be set "Select..." instead 01.01.1970 by default
         */
        $this->period_start !== ""
            ? $query->andFilterWhere (['>=', 'start', Yii::$app->formatter->asDate ($this->period_start, 'yyyy-MM-dd')])
            : $this->period_start = null; 
        $this->period_end !== "" 
            ? $query->andFilterWhere (['<=', 'start', Yii::$app->formatter->asDate ($this->period_end, 'yyyy-MM-dd')])
            : $this->period_end = null;           

        /**
         * Filter active tasks
         */
        if ($this->active != null) 
        	$query->andWhere('NOW() < DATE_ADD(LAST_DAY(created_at), INTERVAL 5 DAY)');
        
		/**
		* Filter tasks by status
		*/
        $filter_status = [];
        if ($this->status != null)
        foreach ($this->status as $i => $item)
        	if ($item != null)
        		$filter_status[] = $item;
       	
			if (count($filter_status)>0)
				$query->having($status . 'IN (' . new Expression (implode (', ', $filter_status)) . ')');
// 			foreach ($this->status as $key=>$value)
// 			{
// 				$query->having("{$status} = :value", ['value' => $value]);
// 			};

        return $dataProvider;
    }
}