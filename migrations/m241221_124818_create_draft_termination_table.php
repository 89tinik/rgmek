<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%draft_termination}}`.
 */
class m241221_124818_create_draft_termination_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%draft_termination}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contract_id' => $this->string(255),
            'contract_price' => $this->decimal(10, 2),
            'contract_volume_price' => $this->decimal(10, 2),
            'files' => $this->text(),
            'contact_name' => $this->string(255),
            'contact_phone' => $this->string(255),
            'contact_email' => $this->string(255),
            'send' => $this->timestamp()->null(),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-draft_termination-user_id}}',
            '{{%draft_termination}}',
            'user_id'
        );
        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-draft_termination-user_id}}',
            '{{%draft_termination}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-draft_termination-user_id}}',
            '{{%draft_termination}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-draft_termination-user_id}}',
            '{{%draft_termination}}'
        );

        $this->dropTable('{{%draft_termination}}');
    }
}
