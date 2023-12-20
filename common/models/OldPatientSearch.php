<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OldPatient;

/**
 * OldPatientSearch represents the model behind the search form of `common\models\OldPatient`.
 */
class OldPatientSearch extends OldPatient
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'card_number', 'discount', 'doctor_id', 'hygienist_id', 'debt', 'credit', 'recommendations_amount'], 'integer'],
            [['first_visit', 'last_name', 'first_name', 'patronymic', 'gender', 'dob', 'phone', 'phone_home', 'phone_work', 'home_address', 'source', 'recommended_patient', 'recommended_user', 'who_were_recommended', 'patient_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = OldPatient::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'card_number' => $this->card_number,
            'first_visit' => $this->first_visit,
            'dob' => $this->dob,
            'discount' => $this->discount,
            'doctor_id' => $this->doctor_id,
            'hygienist_id' => $this->hygienist_id,
            'debt' => $this->debt,
            'credit' => $this->credit,
            'recommendations_amount' => $this->recommendations_amount,
        ]);

        $query->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'patronymic', $this->patronymic])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'phone_home', $this->phone_home])
            ->andFilterWhere(['like', 'phone_work', $this->phone_work])
            ->andFilterWhere(['like', 'home_address', $this->home_address])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'recommended_patient', $this->recommended_patient])
            ->andFilterWhere(['like', 'recommended_user', $this->recommended_user])
            ->andFilterWhere(['like', 'who_were_recommended', $this->who_were_recommended])
            ->andFilterWhere(['like', 'patient_status', $this->patient_status]);

        return $dataProvider;
    }
}
