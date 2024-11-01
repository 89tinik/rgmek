<?php

use yii\db\Migration;

/**
 * Class m241016_172653_create_table_draft_contract
 */
class m241016_172653_create_table_draft_contract extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%draft_contract}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contract_id' => $this->integer(),
            'contract_type' =>$this->string(255),
            'from_date' => $this->string(255),
            'to_date' => $this->string(255),
            'basis_purchase' =>$this->string(255),
            'ikz' =>$this->string(255),
            'contract_price' => $this->decimal(10, 2),
            'contract_volume_plane' => $this->decimal(10, 2),
            'contract_volume_plane_include' => $this->integer(),
            'source_funding' => $this->string(255),
            'off_budget' => $this->integer(),
            'off_budget_name' => $this->string(255),
            'off_budget_value' => $this->decimal(10, 2),
            'budget_value' => $this->decimal(10, 2),
            'user_phone' => $this->string(255),
            'user_email' => $this->string(255),
            'files' => $this->text(),
            'contact_name' => $this->string(255),
            'contact_phone' => $this->string(255),
            'contact_email' => $this->string(255),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-draft_contract-user_id}}',
            '{{%draft_contract}}',
            'user_id'
        );
        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-draft_contract-user_id}}',
            '{{%draft_contract}}',
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
            '{{%fk-draft_contract-user_id}}',
            '{{%draft_contract}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-draft_contract-user_id}}',
            '{{%draft_contract}}'
        );

        $this->dropTable('{{%draft_contract}}');
    }
}
