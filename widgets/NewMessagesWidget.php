<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Messages;
use Yii;

class NewMessagesWidget extends Widget
{
    public $userId;

    public function init()
    {
        parent::init();
        $this->userId = Yii::$app->user->id;
    }

    public function run()
    {
        $newMessagesCount = Messages::find()
            ->where(['user_id' => $this->userId, 'new' => 1])
            ->count();

        if ($newMessagesCount > 0) {
            return '<span class="new"></span>';
        }

        return '';
    }
}
