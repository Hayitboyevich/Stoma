<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PriceListItemSearch represents the model behind the search form of `common\models\PriceListItem`.
 */
class PriceListItemSearch extends PriceListItem
{
    public $section;

    public function rules(): array
    {
        return [
            [['id', 'price_list_id', 'parent_id', 'price'], 'integer'],
            [['name','section'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
    public function search(array $params): ActiveDataProvider
    {
        $query = PriceListItem::find()->joinWith('priceList');

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
            'price_list_id' => $this->price_list_id,
            'parent_id' => $this->parent_id,
            'price' => $this->price,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'priceList.section', $this->section]);

        return $dataProvider;
    }
}
