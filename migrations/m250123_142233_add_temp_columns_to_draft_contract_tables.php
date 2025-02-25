<?php


use yii\db\Migration;

/**
 * Handles adding columns to table `{{%draft_contract}}`.
 */
class m250123_142233_add_temp_columns_to_draft_contract_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%draft_contract_change}}', 'temp_data', $this->text());
        $this->addColumn('{{%draft_termination}}', 'temp_data', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%draft_contract_change}}', 'temp_data');
        $this->dropColumn('{{%draft_termination}}', 'temp_data');
    }
}
