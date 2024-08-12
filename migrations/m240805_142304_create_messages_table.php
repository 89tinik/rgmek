<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 */
class m240805_142304_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'subject_id' => $this->integer()->notNull(),
            'contract_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'files' => $this->text(),
            'created' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'user_id' => $this->integer()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'published' => $this->timestamp(),
            'admin_num' => $this->string(255),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        // creates index for column `subject_id`
        $this->createIndex(
            '{{%idx-messages-subject_id}}',
            '{{%messages}}',
            'subject_id'
        );

        // add foreign key for table `{{%theme}}`
        $this->addForeignKey(
            '{{%fk-messages-subject_id}}',
            '{{%messages}}',
            'subject_id',
            '{{%message_themes}}',
            'id',
            'CASCADE'
        );

        // creates index for column `contract_id`
        $this->createIndex(
            '{{%idx-messages-contract_id}}',
            '{{%messages}}',
            'contract_id'
        );

        // add foreign key for table `{{%contracts}}`
        $this->addForeignKey(
            '{{%fk-messages-contract_id}}',
            '{{%messages}}',
            'contract_id',
            '{{%contracts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-messages-user_id}}',
            '{{%messages}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-messages-user_id}}',
            '{{%messages}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-messages-status_id}}',
            '{{%messages}}',
            'status_id'
        );

        // add foreign key for table `{{%statuses}}`
        $this->addForeignKey(
            '{{%fk-messages-status_id}}',
            '{{%messages}}',
            'status_id',
            '{{%message_statuses}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%theme}}`
        $this->dropForeignKey(
            '{{%fk-messages-subject_id}}',
            '{{%messages}}'
        );

        // drops index for column `subject_id`
        $this->dropIndex(
            '{{%idx-messages-subject_id}}',
            '{{%messages}}'
        );

        // drops foreign key for table `{{%contract}}`
        $this->dropForeignKey(
            '{{%fk-messages-contract_id}}',
            '{{%messages}}'
        );

        // drops index for column `contract_id`
        $this->dropIndex(
            '{{%idx-messages-contract_id}}',
            '{{%messages}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-messages-user_id}}',
            '{{%messages}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-messages-user_id}}',
            '{{%messages}}'
        );

        // drops foreign key for table `{{%statuses}}`
        $this->dropForeignKey(
            '{{%fk-messages-status_id}}',
            '{{%messages}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-messages-status_id}}',
            '{{%messages}}'
        );

        $this->dropTable('{{%messages}}');
    }
}

