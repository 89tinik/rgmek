<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%messages}}`.
 */
class m240831_075318_add_user_columns_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%messages}}', 'update', $this->timestamp());
        $this->addColumn('{{%messages}}', 'phone', $this->string(255));
        $this->addColumn('{{%messages}}', 'email', $this->string(255)->notNull());
        $this->addColumn('{{%messages}}', 'contact_name', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%messages}}', 'update');
        $this->dropColumn('{{%messages}}', 'phone');
        $this->dropColumn('{{%messages}}', 'email');
        $this->dropColumn('{{%messages}}', 'contact_name');
    }
}
