<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Reception;

/**
 * ReceptionSearch represents the model behind the search form of `common\models\Reception`.
 */
class ReceptionSearch extends Reception
{
    public $patient_full_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'patient_id', 'doctor_id', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['comment','patient_full_name'], 'safe'],
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
        $query = Reception::find()->orderBy(['id' => SORT_DESC]);
        $query->joinWith('patient');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['patient.firstname','patient.lastname','patient.phone','doctor_id']]
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        echo $this->patient_full_name;
        //die();
        $query->andFilterWhere(['like', 'comment', $this->comment]);
        $query->andFilterWhere(['like', 'patient.firstname', Yii::$app->request->get('firstname')]);
        $query->andFilterWhere(['like', 'patient.lastname', Yii::$app->request->get('lastname')]);
        $query->andFilterWhere(['like', 'patient.phone', Yii::$app->request->get('phone')]);


        return $dataProvider;
    }
}
