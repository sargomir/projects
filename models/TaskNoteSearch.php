<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\projects\models\TaskNote;

/**
 * TaskNoteSearch represents the model behind the search form about `app\modules\projects\models\TaskNote`.
 */
class TaskNoteSearch extends TaskNote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['note_id', 'task_id'], 'integer'],
            [['user_id', 'note', 'created_at'], 'safe'],
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
        $query = TaskNote::find();

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
            'note_id' => $this->note_id,
            'task_id' => $this->task_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
