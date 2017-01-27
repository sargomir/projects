<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\projects\models\ProjectPart;

/**
 * ProjectPartSearch represents the model behind the search form about `app\modules\projects\models\ProjectPart`.
 */
class ProjectPartSearch extends ProjectPart
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_id', 'parent_part_id'], 'integer'],
            [['code', 'part'], 'safe'],
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
        $query = ProjectPart::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'part_id' => $this->part_id,
            'parent_part_id' => $this->parent_part_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'part', $this->part]);

        return $dataProvider;
    }
}