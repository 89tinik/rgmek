<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Messages;

/**
 * MessagesSearch represents the model behind the search form of `app\models\Messages`.
 */
class MessagesSearch extends Messages
{
    public $user_name;
    public $contract_number;
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'subject_id', 'contract_id', 'user_id', 'status_id', 'new'], 'integer'],
            [['message', 'files', 'created', 'published', 'admin_num', 'answer', 'answer_files', 'user_name', 'contract_number', 'date_to', 'date_from'], 'safe'],
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
        $query = Messages::find();

        $query->joinWith(['user']);
        $query->joinWith(['contract']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>['id'=> SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'created' => $this->created,
            'status_id' => $this->status_id,
            'published' => $this->published,
            'new' => $this->new,
        ]);

        $query->andFilterWhere(['like', 'users.full_name', $this->user_name]);
        $query->andFilterWhere(['like', 'contracts.number', $this->contract_number]);

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'files', $this->files])
            ->andFilterWhere(['like', 'admin_num', $this->admin_num])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'answer_files', $this->answer_files]);

        return $dataProvider;
    }
/**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchForUser($params, $userId)
    {
        $query = Messages::find()->alias('m');

        $query->andWhere(['m.user_id' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>[
                    'status_id' => SORT_ASC,
                    'new' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        if ($this->date_from) {
            $dateFrom = \DateTime::createFromFormat('d.m.Y', $this->date_from)->format('Y-m-d');
        }

        if ($this->date_to) {
            $dateTo = \DateTime::createFromFormat('d.m.Y', $this->date_to)->format('Y-m-d');
        }

        if ($dateFrom && $dateTo) {
            $query->andFilterWhere(['between', 'published', $dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->andFilterWhere(['>=', 'published', $dateFrom]);
        } elseif ($dateTo) {
            $query->andFilterWhere(['<=', 'published', $dateTo]);
        }




        return $dataProvider;
    }
}
