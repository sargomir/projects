<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\modules\projects\models\ReportProject;

/**
 * ReportProjectSearch represents the model behind the search form about `app\modules\projects\models\ReportProject`.
 */
class ReportDailySearch extends Report
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['project', 'part'], 'safe'],
            //[['budget', 'tasks_total'], 'integer'],
            [['tasks_estimated', 'tasks_elapsed'], 'number'],
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
        //$query = Report::find()
        //    ->leftJoin('projects', 'projects.project_id = tasks.project_id')
        //    ->leftJoin('project_parts', 'project_parts.part_id = tasks.project_part_id')
        //    ->leftJoin('project_parts parent_parts', 'project_parts.parent_part_id = parent_parts.part_id')
        //    ->leftJoin('budgets', 'budgets.project_id = tasks.project_id and budgets.project_part_id = parent_parts.part_id')
        //    ->select([
        //        'tasks.task_id', 'tasks.worker_id', 'tasks.project_id', 'tasks.project_part_id',
        //        'tasks_issued' => 'SUM(tasks.estimated)',
        //        'tasks_estimated' => 'SUM( IF(tasks.project_manager_check, tasks.estimated, 0) )',
        //        'tasks_elapsed' => 'SUM( IF(tasks.project_manager_check, tasks.elapsed, 0) )',
        //        'tasks_failed' => 'SUM(tasks.estimated) - SUM( IF(tasks.project_manager_check, tasks.estimated, 0) )',
        //        '_project' => 'projects.project',
        //        '_project_lead' => 'projects.project_lead_id',
        //        '_parent_part' => 'parent_parts.part',
        //        '_budget' => 'budgets.budget',
        //        '_project_part' => 'project_parts.part',
        //    ])
        //    ->groupBy(['_project', '_parent_part', '_project_part']);
        
/**
select 
	lead_profile.lastname,
	lead_profile.firstname,
	lead_profile.secondname,
	worker_profile.lastname,
	worker_profile.firstname,
	worker_profile.secondname,
	projects.project,
	project_parts.part,
	tasks.task,
	tasks.`start`,
	tasks.deadline,
	task_status(task_id) as `status`,
	tasks.estimated,
	tasks.elapsed
from (select * from auth.auth_users where disabled is null or disabled < 1) as worker
left join auth.auth_profile as worker_profile on worker.id = worker_profile.user_id
left join auth.auth_profile as lead_profile on lead_profile.user_id = worker_profile.lead_id
left join tasks on tasks.worker_id = worker.id
	and ((tasks.start>=DATE_ADD(DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY)),INTERVAL 1 DAY),INTERVAL -1 MONTH))) 
	and (task_status(task_id) <> 3) 
left join projects on tasks.project_id = projects.project_id
left join project_parts on project_parts.part_id = tasks.project_part_id
order by lead_profile.lastname, worker_profile.lastname
*/
        $query = (new \yii\db\Query())
            ->select([
                'lead_lastname' => 'lead_profile.lastname',
                'lead_firstname' => 'lead_profile.firstname',
                'lead_secondname' => 'lead_profile.secondname',
                'worker_lastname' => 'worker_profile.lastname',
                'worker_firstname' => 'worker_profile.firstname',
                'worker_secondname' => 'worker_profile.secondname',
                'project' => 'projects.project',
                'project_part' => 'project_parts.part',
                'task' => 'tasks.task',
                'start' => 'tasks.`start`',
                'deadline' => 'tasks.deadline',
                'status' => 'task_status(task_id)',
                'estimated' => 'tasks.estimated',
                'elapsed' => 'tasks.elapsed'                
            ])
            ->from(['worker' => '(select * from auth.auth_users where disabled is null or disabled < 1)'])
            ->leftJoin('auth.auth_profile as worker_profile', 'worker.id = worker_profile.user_id')
            ->leftJoin('auth.auth_profile as lead_profile', 'lead_profile.user_id = worker_profile.lead_id')
            ->leftJoin('tasks', 'tasks.worker_id = worker.id
	and ((tasks.start>=DATE_ADD(DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY)),INTERVAL 1 DAY),INTERVAL -1 MONTH))) 
	and (task_status(task_id) <> 3)')
            ->leftJoin('projects', 'tasks.project_id = projects.project_id')
            ->leftJoin('project_parts', 'project_parts.part_id = tasks.project_part_id')
        ;
        //$query = Report::find()
        //    ->select(['*', 'status'=>'task_status(task_id)'])
        //    ->leftJoin('auth.auth_profile as worker_profile', 'worker_profile.user_id = tasks.worker_id')
        //    ->leftJoin('auth.auth_profile as project_lead_profile', 'project_lead_profile.user_id = tasks.project_lead_id')
        //    ->andWhere('(tasks.start>=DATE_ADD(DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY)),INTERVAL 1 DAY),INTERVAL -1 MONTH))')
        //    ->andWhere('task_status(task_id) <> 3')
        //;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        
        //$dataProvider->setSort([
        //    'attributes' => [
        //        'worker_id' => [
        //            'asc' => ['worker_profile.lastname' => SORT_ASC],
        //            'desc' => ['worker_profile.lastname' => SORT_DESC],
        //            'default' => SORT_ASC
        //        ],
        //        'project_lead_id' => [
        //            'asc' => ['project_lead_profile.lastname' => SORT_ASC],
        //            'desc' => ['worker_profile.lastname' => SORT_DESC],
        //            'default' => SORT_ASC
        //        ],
        //    ],
        //    'defaultOrder' => [
        //        'project_lead_id' => SORT_ASC,
        //        'worker_id' => SORT_ASC,
        //    ],
        //]);           

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}