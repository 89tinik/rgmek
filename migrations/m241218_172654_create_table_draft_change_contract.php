<?php

use yii\db\Migration;

/**
 * Class m241016_172653_create_table_draft_contract_change
 */
class m241218_172654_create_table_draft_change_contract extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%draft_contract_change}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contract_id' => $this->integer(),
            'contract_price_new' => $this->decimal(10, 2),
            'contract_volume_new' => $this->decimal(10, 2),
            'contract_volume_plane_include' => $this->integer(),
            'files' => $this->text(),
            'contact_name' => $this->string(255),
            'contact_phone' => $this->string(255),
            'contact_email' => $this->string(255),
            'send' => $this->timestamp()->null(),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-draft_contract_change-user_id}}',
            '{{%draft_contract_change}}',
            'user_id'
        );
        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-draft_contract_change-user_id}}',
            '{{%draft_contract_change}}',
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
            '{{%fk-draft_contract_change-user_id}}',
            '{{%draft_contract_change}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-draft_contract_change-user_id}}',
            '{{%draft_contract_change}}'
        );

        $this->dropTable('{{%draft_contract_change}}');
    }
}
