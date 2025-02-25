<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_statuses}}`.
 */
class m240805_142300_create_message_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_statuses}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message_statuses}}');
    }
}
