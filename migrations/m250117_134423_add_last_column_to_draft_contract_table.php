<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%draft_contract}}`.
 */
class m250117_134423_add_last_column_to_draft_contract_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%draft_contract}}', 'last', $this->integer());
        $this->addColumn('{{%draft_contract_change}}', 'last', $this->integer());
        $this->addColumn('{{%draft_termination}}', 'last', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%draft_contract}}', 'last');
        $this->dropColumn('{{%draft_contract_change}}', 'last');
        $this->dropColumn('{{%draft_termination}}', 'last');
    }
}
