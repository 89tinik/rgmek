<?php

use yii\db\Migration;

/**
 * Handles droping columns to table `{{%draft_contract_change}}` `{{%draft_termination}}`.
 */
class m250412_068543_drop_files_columns_to_drafts_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%draft_contract_change}}', 'files');
        $this->dropColumn('{{%draft_termination}}', 'files');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%draft_contract_change}}', 'files', $this->text());
        $this->addColumn('{{%draft_termination}}', 'files', $this->text());
    }
}
