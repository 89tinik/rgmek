<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%theme}}`.
 */
class m230125_091943_create_theme_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%theme}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'sort' => $this->integer()->defaultValue(0),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%theme}}');
    }
}
