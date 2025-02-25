<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%messages}}`.
 */
class m240807_164556_add_new_columns_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%messages}}', 'new', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%messages}}', 'answer', $this->text());
        $this->addColumn('{{%messages}}', 'answer_files', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%messages}}', 'new');
        $this->dropColumn('{{%messages}}', 'answer');
        $this->dropColumn('{{%messages}}', 'answer_files');
    }
}
