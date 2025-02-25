<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message_themes}}`.
 */
class m241217_165039_add_created_column_to_draft_contract_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%draft_contract}}', 'send', $this->timestamp()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%draft_contract}}', 'send');
    }
}