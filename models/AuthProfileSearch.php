<?php

namespace app\modules\projects\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\projects\models\UserProfile;

/**
 * UserProfilesSearch represents the model behind the search form about `app\modules\projects\models\UserProfiles`.
 */
class AuthProfileSearch extends AuthProfile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'lead_id'], 'safe'],
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
        $query = UserProfiles::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'lead_id', $this->lead_id]);

        return $dataProvider;
    }
}
