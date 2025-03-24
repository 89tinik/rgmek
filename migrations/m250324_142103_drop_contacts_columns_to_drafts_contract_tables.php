<?php

use yii\db\Migration;

/**
 * Handles droping columns to table `{{%draft_contract}}` `{{%draft_contract_change}}` `{{%draft_termination}}`.
 */
class m250324_142103_drop_contacts_columns_to_drafts_contract_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%draft_contract}}', 'restriction_notify_fn');
        $this->dropColumn('{{%draft_contract_change}}', 'contact_name');
        $this->dropColumn('{{%draft_contract_change}}', 'contact_phone');
        $this->dropColumn('{{%draft_contract_change}}', 'contact_email');
        $this->dropColumn('{{%draft_termination}}', 'contact_name');
        $this->dropColumn('{{%draft_termination}}', 'contact_phone');
        $this->dropColumn('{{%draft_termination}}', 'contact_email');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%draft_contract}}', 'restriction_notify_fn', $this->string(255)->after('budget_value'));
        $this->addColumn('{{%draft_contract_change}}', 'contact_name', $this->string(255));
        $this->addColumn('{{%draft_contract_change}}', 'contact_phone', $this->string(255));
        $this->addColumn('{{%draft_contract_change}}', 'contact_email', $this->string(255));
        $this->addColumn('{{%draft_termination}}', 'contact_name', $this->string(255));
        $this->addColumn('{{%draft_termination}}', 'contact_phone', $this->string(255));
        $this->addColumn('{{%draft_termination}}', 'contact_email', $this->string(255));
    }
}
