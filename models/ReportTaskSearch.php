<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * ReportWorkerSearch represents the model behind the search form about `app\modules\projects\models\ReportWorker`.
 */

class ReportTaskSearch extends Report
{
    /**
     * Constants
     */
    const All = 0;
    const ThisMonth = 1;
    const LastMonth = 2;
    const Last3Months = 3;
    
    public $period;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'project_id', 'project_part_id', 'estimated', 'elapsed'], 'integer'],
            [['task', 'worker_check', 'project_lead_check', 'technical_lead_check', 'worker_id', 'project_lead_id', 'technical_lead_id'], 'safe'],
            [['active', 'period'], 'safe'],
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
            //->joinWith([/*'project', 'parent_part', */'budget', /*'project_part'*/])
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

        $sorted_workers = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        $sorted_technical_leads = array_column (AuthProfile::find()->joinWith(['lead'])->select(['`auth_profile`.*'])->orderBy(['`TL`.lastname'=>'asc', '`auth_profile`.lastname' => 'asc'])->asArray()->all(), 'user_id');

        $dataProvider->setSort([
            'attributes' => [
                '_project',
                '_parent_part',
                '_budget',
                '_project_part',
                'tasks_issued',
                'tasks_estimated',
                'tasks_elapsed',
                'tasks_failed',
                'worker' => [
                    'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_workers) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_workers)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'technical_lead' => [
                    'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_technical_leads) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_technical_leads)) . "')" ) ],
                    'default' => SORT_ASC
                ],                
            ],
            'defaultOrder' => [
                '_project' => SORT_ASC,
                '_project_part' => SORT_ASC,
                'worker' => SORT_ASC,
            ],            
        ]);
        
        //return $dataProvider;
        //$query = Report::find();
        ////$query->joinWith(['budget', 'parent_part']);
        //$query->select([
        //    '`tasks`.task_id', '`tasks`.worker_id', '`tasks`.project_id', '`tasks`.project_part_id',
        //    'tasks_estimated' => 'SUM(tasks.estimated)',
        //    'tasks_elapsed' => 'SUM(tasks.elapsed)',
        //]);
        //$query->groupBy(['tasks.project_id', 'tasks.project_part_id', 'tasks.worker_id']);
        //
        //$dataProvider = new ActiveDataProvider([
        //    'query' => $query,
        //]);
        
        //$sorted_projects = array_column (Project::find()->select(['project_id'])->orderBy(['project' => 'asc'])->asArray()->all(), 'project_id');
        //$sorted_workers = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        //$sorted_technical_leads = array_column (AuthProfile::find()->joinWith(['lead'])->select(['`auth_profile`.*'])->orderBy(['`TL`.lastname'=>'asc', '`auth_profile`.lastname' => 'asc'])->asArray()->all(), 'user_id');
        ////$sorted_project_parts = array_column (ProjectPart::find()->select(['part_id'])->orderBy(['part' => 'asc'])->asArray()->all(), 'part_id');
        //$dataProvider->setSort([
        //    'attributes' => [
        //        '_project',
        //        '_parent_part',
        //        '_project_part',
        //        '_budget',
        //        //'project.project' => [
        //        //    'asc' => [ new Expression( "FIELD (`tasks`.project_id, '" . implode("','", $sorted_projects) . "')" ) ],
        //        //    'desc' => [ new Expression( "FIELD (`tasks`.project_id, '" . implode("','", array_reverse($sorted_projects)) . "')" ) ],
        //        //    'default' => SORT_ASC                    
        //        //],
        //        //'_budget',
        //        //'_parent_part',
        //        //'_project_part',
        //        'worker' => [
        //            'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_workers) . "')" ) ],
        //            'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_workers)) . "')" ) ],
        //            'default' => SORT_ASC
        //        ],
        //        //'project_part.part' => [
        //        //    'asc' => [ new Expression( "FIELD (`tasks`.project_part_id, '" . implode("','", $sorted_project_parts) . "')" ) ],
        //        //    'desc' => [ new Expression( "FIELD (`tasks`.project_part_id,'" . implode("','", array_reverse($sorted_project_parts)) . "')" ) ],
        //        //    'default' => SORT_ASC                    
        //        //],
        //        //'project_part_id',
        //        'tasks_estimated',
        //        'tasks_elapsed',
        //        'technical_lead' => [
        //            'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_technical_leads) . "')" ) ],
        //            'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_technical_leads)) . "')" ) ],
        //            'default' => SORT_ASC
        //        ],
        //    ],
        //    'defaultOrder' => [
        //        '_project' => SORT_ASC,
        //        'worker' => SORT_ASC,
        //    ],
        //]);   
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /**
         * Filter date period.
         */
        //switch($this->period) {
        //    case ReportWorkerSearch::ThisMonth :
        //        $query->andFilterWhere (['>=', '`tasks`.created_at', new Expression('DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)), INTERVAL 1 DAY)')]);
        //        $query->andFilterWhere (['<=', '`tasks`.created_at', new Expression('LAST_DAY(CURRENT_DATE)')]);
        //        break;
        //    case ReportWorkerSearch::LastMonth :
        //        $query->andFilterWhere (['>=', '`tasks`.created_at', new Expression('DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 2 MONTH)), INTERVAL 1 DAY)')]);
        //        $query->andFilterWhere (['<=', '`tasks`.created_at', new Expression('LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))')]);
        //        break;
        //    case ReportWorkerSearch::Last3Months :
        //        $query->andFilterWhere (['>=', '`tasks`.created_at', new Expression('DATE_ADD(LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)), INTERVAL 1 DAY)')]);
        //        $query->andFilterWhere (['<=', '`tasks`.created_at', new Expression('LAST_DAY(CURRENT_DATE)')]);
        //        break;
        //    case ReportWorkerSearch::All :
        //        break;
        //}
        
        $query->andFilterWhere([
            '{{tasks}}.project_id' => $this->project_id,
        //    'tasks_active' => $this->tasks_active,
        //    'tasks_completed' => $this->tasks_completed,
        //    'tasks_estimated' => $this->tasks_estimated,
        //    'tasks_elapsed' => $this->tasks_elapsed,
        ]);

        //$query->andFilterWhere(['like', 'worker_id', $this->worker_id])
        //    ->andFilterWhere(['like', 'project', $this->project])
        //    ->andFilterWhere(['like', 'part', $this->part]);

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