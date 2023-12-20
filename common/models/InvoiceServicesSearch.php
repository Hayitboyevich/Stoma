<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InvoiceServices;

/**
 * InvoiceServicesSearch represents the model behind the search form of `common\models\InvoiceServices`.
 */
class InvoiceServicesSearch extends InvoiceServices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'price_list_item_id', 'amount', 'price'], 'integer'],
            [['teeth'], 'safe'],
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
        $query = InvoiceServices::find();

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
            'price_list_item_id' => $this->price_list_item_id,
            'amount' => $this->amount,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'teeth', $this->teeth]);

        return $dataProvider;
    }
}
