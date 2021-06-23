<?php

namespace app\components;


use app\models\Contract;
use yii\base\Widget;

class Summary extends Widget
{

    public function run()
    {

        if (!empty($contracts = Contract::find()->where(['user_id'=>\Yii::$app->user->identity->id])->asArray()->all())){
            $outputLeft = '';
            foreach ($contracts as $contract){
                $outputLeft.= $this->toTemplateLeft($contract);
            }
        }

        return $outputLeft;


    }

    protected function toTemplateLeft ($contract){
        ob_start();
        include __DIR__.'/tpl/contract_left.php';
        return ob_get_clean();
    }


}