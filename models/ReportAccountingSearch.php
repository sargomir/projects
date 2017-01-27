<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use app\modules\projects\Projects as Module;

/**
 * ReportWorkerSearch represents the model behind the search form about `app\modules\projects\models\ReportWorker`.
 */

class ReportAccountingSearch extends Report
{
	/**
	 * Search parameters
	 */
	public $worker, $technical_lead, $tasks_estimated, $tasks_elapsed;
    public $period_start = "";
    public $period_end = "";
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['tasks_estimated', 'tasks_elapsed'], 'integer'],
            [['worker', 'technical_lead'], 'safe'],
			[['period_start', 'period_end'], 'date']
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
        $query = Report::find()
            ->select([
				'technical_lead' => 'CONCAT(lead_profile.lastname," ",UPPER(LEFT(lead_profile.firstname,1)),".",UPPER(LEFT(lead_profile.secondname,1)),".")',
				'worker' => 'CONCAT(worker_profile.lastname," ",UPPER(LEFT(worker_profile.firstname,1)),".",UPPER(LEFT(worker_profile.secondname,1)),".")',
                'tasks.task_id', 'tasks.worker_id',
                'tasks_issued' => 'SUM(tasks.estimated)',
                'tasks_estimated' => 'SUM( IF(tasks.project_manager_check, tasks.estimated, 0) )',
                'tasks_elapsed' => 'SUM( IF(tasks.project_manager_check, tasks.elapsed, 0) )',
                'tasks_failed' => 'SUM(tasks.estimated) - SUM( IF(tasks.project_manager_check, tasks.estimated, 0) )',
            ])
			->leftJoin('auth.auth_profile as worker_profile', 'worker_profile.user_id = worker_id')
			->leftJoin('auth.auth_profile as lead_profile', 'lead_profile.user_id = worker_profile.lead_id')
//            ->where(['not', ['worker_check'=>'null', 'technical_lead_check'=>'null', 'project_lead_check'=>'null', 'project_manager_check'=>'null']])
            ->groupBy(['tasks.worker_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sorted_workers = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        $sorted_technical_leads = array_column (AuthProfile::find()->joinWith(['lead'])->select(['`auth_profile`.*'])->orderBy(['`TL`.lastname'=>'asc', '`auth_profile`.lastname' => 'asc'])->asArray()->all(), 'user_id');

        $dataProvider->setSort([
            'attributes' => [
                'tasks_issued',
                'tasks_estimated',
                'tasks_elapsed',
                'tasks_failed',
				'worker',
				'technical_lead',
            ],
            'defaultOrder' => [
                'worker' => SORT_ASC,
            ],            
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // Filter previous month only
        //$query->andFilterWhere (['>=', '`tasks`.start', new Expression('DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 2 MONTH)), INTERVAL 1 DAY)')])
            //->andFilterWhere (['<=', '`tasks`.start', new Expression('LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))')]); 
        
        // Filter search parameters
        $query->andFilterWhere([
        	'worker_profile.user_id' => $this->worker,
        	'lead_profile.user_id' => $this->technical_lead,
        ]);
		
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
        
        return $dataProvider;
    }
}