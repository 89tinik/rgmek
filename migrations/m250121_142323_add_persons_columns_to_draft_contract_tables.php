<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%draft_contract}}`.
 */
class m250121_142323_add_persons_columns_to_draft_contract_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%draft_contract}}', 'user_phone', 'restriction_notify_p');
        $this->renameColumn('{{%draft_contract}}', 'user_email', 'restriction_notify_e');
        $this->addColumn('{{%draft_contract}}', 'restriction_notify_fn', $this->string(255)->after('budget_value'));
        $this->addColumn('{{%draft_contract}}', 'responsible_4device_contact_fn', $this->string(255)->after('contact_email'));
        $this->addColumn('{{%draft_contract}}', 'responsible_4device_contact_p', $this->string(255)->after('responsible_4device_contact_fn'));
        $this->addColumn('{{%draft_contract}}', 'responsible_4device_contact_e', $this->string(255)->after('responsible_4device_contact_p'));
        $this->addColumn('{{%draft_contract}}', 'responsible_4calculation_contact_fn', $this->string(255)->after('responsible_4device_contact_e'));
        $this->addColumn('{{%draft_contract}}', 'responsible_4calculation_contact_p', $this->string(255)->after('responsible_4calculation_contact_fn'));
        $this->addColumn('{{%draft_contract}}', 'responsible_4calculation_contact_e', $this->string(255)->after('responsible_4calculation_contact_p'));
        $this->addColumn('{{%draft_contract}}', 'director_full_name', $this->string(255)->after('responsible_4calculation_contact_e'));
        $this->addColumn('{{%draft_contract}}', 'director_position', $this->string(255)->after('director_full_name'));
        $this->addColumn('{{%draft_contract}}', 'director_order', $this->string(255)->after('director_position'));
        $this->addColumn('{{%draft_contract}}', 'temp_data', $this->text());
        $this->addColumn('{{%draft_contract_change}}', 'director_full_name', $this->string(255)->after('contact_email'));
        $this->addColumn('{{%draft_contract_change}}', 'director_position', $this->string(255)->after('director_full_name'));
        $this->addColumn('{{%draft_contract_change}}', 'director_order', $this->string(255)->after('director_position'));
        $this->addColumn('{{%draft_termination}}', 'director_full_name', $this->string(255)->after('contact_email'));
        $this->addColumn('{{%draft_termination}}', 'director_position', $this->string(255)->after('director_full_name'));
        $this->addColumn('{{%draft_termination}}', 'director_order', $this->string(255)->after('director_position'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%draft_contract}}', 'restriction_notify_p', 'user_phone');
        $this->renameColumn('{{%draft_contract}}', 'restriction_notify_e', 'user_email');
        $this->dropColumn('{{%draft_contract}}', 'restriction_notify_fn');
        $this->dropColumn('{{%draft_contract}}', 'responsible_4device_contact_fn');
        $this->dropColumn('{{%draft_contract}}', 'responsible_4device_contact_p');
        $this->dropColumn('{{%draft_contract}}', 'responsible_4device_contact_e');
        $this->dropColumn('{{%draft_contract}}', 'responsible_4calculation_contact_fn');
        $this->dropColumn('{{%draft_contract}}', 'responsible_4calculation_contact_p');
        $this->dropColumn('{{%draft_contract}}', 'responsible_4calculation_contact_e');
        $this->dropColumn('{{%draft_contract}}', 'director_full_name');
        $this->dropColumn('{{%draft_contract}}', 'director_position');
        $this->dropColumn('{{%draft_contract}}', 'director_order');
        $this->dropColumn('{{%draft_contract}}', 'temp_data');
        $this->dropColumn('{{%draft_contract_change}}', 'director_full_name');
        $this->dropColumn('{{%draft_contract_change}}', 'director_position');
        $this->dropColumn('{{%draft_contract_change}}', 'director_order');
        $this->dropColumn('{{%draft_termination}}', 'director_full_name');
        $this->dropColumn('{{%draft_termination}}', 'director_position');
        $this->dropColumn('{{%draft_termination}}', 'director_order');
    }
}
