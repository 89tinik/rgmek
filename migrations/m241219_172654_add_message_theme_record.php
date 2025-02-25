<?php

use yii\db\Migration;

/**
 * Handles adding a new record to the `message_themes` table.
 */
class m241219_172654_add_message_theme_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%message_themes}}', [
            'title' => 'Заявление на изменение цены контракта (договора) энергоснабжения',
            'content' => null,
            'hidden' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%message_themes}}', [
            'title' => 'Заявление на изменение цены контракта (договора) энергоснабжения',
            'hidden' => 1,
        ]);
    }
}