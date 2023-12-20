<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InvoiceRefund;

/**
 * InvoiceRefundSearch represents the model behind the search form of `common\models\InvoiceRefund`.
 */
class InvoiceRefundSearch extends InvoiceRefund
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'requested_user_id', 'approved_or_declined_user_id'], 'integer'],
            [['approved_or_declined_at', 'approved_or_declined_comment', 'status', 'comments', 'created_at'], 'safe'],
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
        $query = InvoiceRefund::find();

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
            'invoice_id' => $this->invoice_id,
            'requested_user_id' => $this->requested_user_id,
            'approved_or_declined_user_id' => $this->approved_or_declined_user_id,
            'approved_or_declined_at' => $this->approved_or_declined_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'approved_or_declined_comment', $this->approved_or_declined_comment])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
