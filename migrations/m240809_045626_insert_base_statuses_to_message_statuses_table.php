<?php

use yii\db\Migration;

/**
 * Class m240809_045626_insert_base_statuses_to_message_statuses_table
 */
class m240809_045626_insert_base_statuses_to_message_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%message_statuses}}', [
            'status' => 'В обработке'
        ]);
        $this->insert('{{%message_statuses}}', [
            'status' => 'В работе'
        ]);
        $this->insert('{{%message_statuses}}', [
            'status' => 'Исполнено'
        ]);
        $this->insert('{{%message_statuses}}', [
            'status' => 'Отозвано'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

}
