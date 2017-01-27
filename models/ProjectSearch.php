<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * ExpensesSearch represents the model behind the search form about `app\modules\projects\models\Expenses`.
 */
class ProjectSearch extends Project
{
    public $active; // Filter active projects
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'integer'],
            [['project_id', 'company', 'project_lead_id', 'project_manager_id'], 'safe'],
            [['active'], 'integer', 'min' => 0, 'max' => 1],
            [['company', 'project_lead_id', 'project_manager_id'], 'string', 'max' => 64],
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
        $query = Project::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sorted_logins = array_column (AuthProfile::find()->select(['user_id'])->orderBy(['lastname' => 'asc'])->asArray()->all(), 'user_id');
        $dataProvider->setSort([
            'attributes' => [
                'project_id' => [
                    'asc' => [new Expression('project ASC')],
                    'desc' => [new Expression('project DESC')],
                    'default' => SORT_ASC    
                ],
                'company',
                'project_lead_id' => [
                    'asc' => [ new \yii\db\Expression( "FIELD (project_lead_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new \yii\db\Expression( "FIELD (project_lead_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],
                'project_manager_id' => [
                    'asc' => [ new \yii\db\Expression( "FIELD (project_manager_id, '" . implode("','", $sorted_logins) . "')" ) ],
                    'desc' => [ new \yii\db\Expression( "FIELD (project_manager_id,'" . implode("','", array_reverse($sorted_logins)) . "')" ) ],
                    'default' => SORT_ASC
                ],                
                'active',
            ],
            'defaultOrder' => [
                'project_id' => SORT_DESC
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
            'project_id' => $this->project_id,
            'company' => $this->company,
            'project_lead_id' => $this->project_lead_id,
            'project_manager_id' => $this->project_manager_id
        ]);

        //$query->andFilterWhere(['like', 'Expense', $this->Expense]);

        return $dataProvider;
    }
}
