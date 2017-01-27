<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\ReportWorker;

/**
 * ReportWorkerSearch represents the model behind the search form about `app\modules\projects\models\ReportWorker`.
 */

class ReportWorkerSearch extends Report
{
    /**
     * Search params
     */
    public $period_start = "";
    public $period_end = "";
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period_start', 'period_end'], 'date']
        ];
    }
    
    /**
     * @inheritdoc
     */
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
            ->leftJoin('projects', 'projects.project_id = tasks.project_id')
            ->leftJoin('project_parts', 'project_parts.part_id = tasks.project_part_id')
            ->leftJoin('project_parts parent_parts', 'project_parts.parent_part_id = parent_parts.part_id')
            ->leftJoin('budgets', 'budgets.project_id = tasks.project_id and budgets.project_part_id = parent_parts.part_id')
            ->select([
                'tasks.task_id', 'tasks.worker_id', 'tasks.project_id', 'tasks.project_part_id',
                'tasks_issued' => 'SUM(tasks.estimated)',
                'tasks_estimated' => 'SUM( IF(tasks.project_manager_check, tasks.estimated, 0) )',
                'tasks_elapsed' => 'SUM( IF(tasks.project_manager_check, tasks.elapsed, 0) )',
                'tasks_failed' => 'SUM(tasks.estimated) - SUM( IF(tasks.project_manager_check, tasks.estimated, 0) )',
                '_project' => 'projects.project',
                '_parent_part' => 'parent_parts.part',
                '_budget' => 'budgets.budget',
                '_project_part' => 'project_parts.part',
            ])
            ->groupBy(['_project', '_parent_part', '_project_part', 'tasks.worker_id']);
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $sorted_logins = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        $sorted_logins_by_lead = array_column (AuthProfile::find()->joinWith(['lead'])->select(['`auth_profile`.*'])->orderBy(['`TL`.lastname'=>'asc', '`auth_profile`.lastname' => 'asc'])->asArray()->all(), 'user_id');
        $dataProvider->setSort([
            'attributes' => [
                'worker' => [
                    'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'technical_lead' => [
                    'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_logins_by_lead) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_logins_by_lead)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                '_project',
                '_project_part',
                'tasks_issued',
                'tasks_estimated',
                'tasks_elapsed',
                'tasks_failed',
            ],
            'defaultOrder' => [
                'worker' => SORT_ASC,
                'technical_lead' => SORT_ASC,
            ],
        ]);   
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

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


//class ReportWorkerSearch extends ReportWorker
//{
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['worker_id', 'project', 'part'], 'safe'],
//            [['tasks_total', 'tasks_completed'], 'integer'],
//            [['tasks_active', 'tasks_estimated', 'tasks_elapsed'], 'number'],
//        ];
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function scenarios()
//    {
//        // bypass scenarios() implementation in the parent class
//        return Model::scenarios();
//    }
//
//    /**
//     * Creates data provider instance with search query applied
//     *
//     * @param array $params
//     *
//     * @return ActiveDataProvider
//     */
//    public function search($params)
//    {
//        $query = ReportWorker::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//        
//        $sorted_logins = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
//        $dataProvider->setSort([
//            'attributes' => [
//                'worker' => [
//                    'asc' => [ new \yii\db\Expression( "FIELD (worker_id, '" . implode("','", $sorted_logins) . "')" ) ],
//                    'desc' => [ new \yii\db\Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
//                    'default' => SORT_ASC
//                ],
//                'project',
//                'part',
//                'tasks_total',
//                'tasks_active',
//                'tasks_completed',
//                'tasks_estimated',
//                'tasks_elapsed',
//            ]
//        ]);   
//        
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//
//        $query->andFilterWhere([
//            'tasks_total' => $this->tasks_total,
//            'tasks_active' => $this->tasks_active,
//            'tasks_completed' => $this->tasks_completed,
//            'tasks_estimated' => $this->tasks_estimated,
//            'tasks_elapsed' => $this->tasks_elapsed,
//        ]);
//
//        $query->andFilterWhere(['like', 'worker_id', $this->worker_id])
//            ->andFilterWhere(['like', 'project', $this->project])
//            ->andFilterWhere(['like', 'part', $this->part]);
//
//        return $dataProvider;
//    }
//}