<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message_themes}}`.
 */
class m241216_165039_add_hidden_column_to_message_themes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%message_themes}}', 'hidden', $this->integer()->defaultValue(0));
        $this->insert('{{%message_themes}}', [
            'title' => 'Заявление на заключение контракта (договора) энергоснабжения на следующий период',
            'content' => null,
            'hidden' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%message_themes}}', 'hidden');
    }
}