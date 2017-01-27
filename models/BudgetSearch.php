<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\modules\projects\models\Budget;

/**
 * BudgetSearch represents the model behind the search form about `app\modules\projects\models\Budget`.
 */
class BudgetSearch extends Budget
{
    public $active; // Filter active projects
    public $project_lead_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['budget_id', 'project_id', 'project_part_id', 'budget'], 'integer'],
            [['created_at'], 'safe'],
            [['active'], 'integer', 'min' => 0, 'max' => 1],
            [['project_lead_id'], 'string', 'max' => 64]
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
        //$query = Budget::find()->joinWith(['project', 'project_part']);
        
        $query = Budget::find()
            ->select([
                'budgets.*',
                //'project' => 'projects.project',
                //'project_part' => 'project_parts.part',
                'used' => 'sum(tasks.estimated)',
                'ratio' => '100 * sum(tasks.estimated) / budgets.budget'
            ])
            ->leftJoin('projects', 'projects.project_id = budgets.project_id')
            ->leftJoin('project_parts', 'project_parts.part_id = budgets.project_part_id')
            ->leftJoin('tasks', 'tasks.project_id = budgets.project_id and tasks.project_part_id in 
                (select part_id from project_parts pp1 where pp1.parent_part_id = budgets.project_part_id)')
            ->groupBy('budgets.budget_id')
        ;
        /**
select projects.project,
project_parts.part,
budgets.budget,
sum(tasks.estimated) as 'used',
100*sum(tasks.estimated)/budgets.budget as 'ratio',
projects.project_lead_id
from budgets
left join projects on budgets.project_id = projects.project_id
left join project_parts on project_parts.part_id = budgets.project_part_id
left join tasks on budgets.project_id = tasks.project_id and tasks.project_part_id in 
	(select part_id from project_parts pp1 where pp1.parent_part_id = budgets.project_part_id)
group by budgets.budget_id
*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['project_id'=>SORT_ASC]]
        ]);
        
        $sorted_logins = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        $dataProvider->setSort([
            'attributes' => [
                'project_id' => [
                    'asc' => [new Expression('projects.project ASC')],
                    'desc' => [new Expression('projects.project DESC')],
                    'default' => SORT_ASC                    
                ],
                'project_part_id' => [
                    'asc' => [new Expression('project_parts.part ASC')],
                    'desc' => [new Expression('project_parts.part DESC')],
                    'default' => SORT_ASC                    
                ],
                'budget',
                'used',
                'ratio',
                'project_lead_id' => [
                    'asc' => [ new Expression( "FIELD (projects.project_lead_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new Expression( "FIELD (projects.project_lead_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
            ],
            'defaultOrder' => [
                'project_part_id' => SORT_ASC,
                'project_id' => SORT_ASC
            ],            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'active' => $this->active,
            'projects.project_lead_id' => $this->project_lead_id,
            'budgets.project_id' => $this->project_id,
            'budgets.project_part_id' => $this->project_part_id,
        ]);

        //$query->andFilterWhere([
        //    'budget_id' => $this->budget_id,
        //    'project_id' => $this->project_id,
        //    'project_part_id' => $this->project_part_id,
        //    'budget' => $this->budget,
        //    'created_at' => $this->created_at,
        //]);

        return $dataProvider;
    }
}
