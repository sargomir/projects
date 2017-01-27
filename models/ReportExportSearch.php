<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * ReportWorkerSearch represents the model behind the search form about `app\modules\projects\models\ReportWorker`.
 */

class ReportExportSearch extends Report
{
    public $period;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period'], 'date', 'format'=>/*'%Y-%M-%d'*/ 'yyyy-mm-dd'],
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
        $query = Report::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
		// Filter start date period
        $last_day = "LAST_DAY('{$this->period}')";
        $first_day = "DATE_ADD(DATE_ADD({$last_day},interval 1 DAY),interval -1 MONTH)";
		$query->andFilterWhere (['>=', '`tasks`.start', new Expression($first_day)]);
		$query->andFilterWhere (['<=', '`tasks`.start', new Expression($last_day)]);        

        return $dataProvider;
    }
}