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
class ReportProjectSearch extends Report
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
                '_project_lead' => 'projects.project_lead_id',
                '_parent_part' => 'parent_parts.part',
                '_budget' => 'budgets.budget',
                '_project_part' => 'project_parts.part',
            ])
            ->groupBy(['_project', '_parent_part', '_project_part']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $sorted_logins = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        $dataProvider->setSort([
            'attributes' => [
                'worker' => [
                    'asc' => [ new Expression( "FIELD (worker_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (worker_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                '_project_lead' => [
                    'asc' => [ new Expression( "FIELD (_project_lead, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (_project_lead,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                '_project',
                '_parent_part',
                '_budget',
                '_project_part',
                'tasks_issued',
                'tasks_estimated',
                'tasks_elapsed',
                'tasks_failed',
            ],
            'defaultOrder' => [
                '_project' => SORT_ASC,
                '_parent_part' => SORT_ASC,
                '_project_part' => SORT_ASC,
            ],
        ]);           

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}