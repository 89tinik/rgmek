<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * InvoiceSearch represents the model behind the search form of `pantera\yii2\pay\sberbank\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    public $user_name;
    public $user_login;
    public $contract;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'order_id', 'remote_id'], 'integer'],
            [['sum'], 'number'],
            [['status', 'created_at', 'pay_time', 'method', 'orderId', 'data', 'url','user_name','user_login','contract'], 'safe'],
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
        $query = Invoice::find()->alias('i');

        // add conditions that should always apply here
        $query->joinWith(['user','receipt.indenture']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);
        $dataProvider->sort->attributes['user_login'] = [
            'asc' => ['users.username' => SORT_ASC],
            'desc' => ['users.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['user_name'] = [
            'asc' => ['users.full_name' => SORT_ASC],
            'desc' => ['users.full_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['contract'] = [
            'asc' => ['contracts.number' => SORT_ASC],
            'desc' => ['contracts.number' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'sum' => $this->sum,
            'pay_time' => $this->pay_time,
            'remote_id' => $this->remote_id,
        ]);

        $query->andFilterWhere(['like', 'i.status', $this->status])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'orderId', $this->orderId])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'contracts.number', $this->contract])
            ->andFilterWhere(['like', 'users.username', $this->user_login])
            ->andFilterWhere(['like', 'users.full_name', $this->user_name]);
        if ($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $this->created_at . ' 00:00:00', $this->created_at . ' 23:59:59']);
        }
        return $dataProvider;
    }
}
