<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_history}}`.
 */
class m240910_161803_create_table_message_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_history}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(),
            'created' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'log' => $this->string(255),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        // creates index for column `subject_id`
        $this->createIndex(
            '{{%idx-message_history-message_id}}',
            '{{%message_history}}',
            'message_id'
        );

        // add foreign key for table `{{%theme}}`
        $this->addForeignKey(
            '{{%fk-mmessage_history-message_id}}',
            '{{%message_history}}',
            'message_id',
            '{{%messages}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%messages}}`
        $this->dropForeignKey(
            '{{%fk-mmessage_history-message_id}}',
            '{{%message_history}}'
        );

        // drops index for column `message_id`
        $this->dropIndex(
            '{{%idx-message_history-message_id}}',
            '{{%message_history}}'
        );

        $this->dropTable('{{%messages}}');
    }
}
