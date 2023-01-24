<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%baner}}`.
 */
class m230124_101438_create_baner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%baner}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string(255),
            'link' => $this->string(255),
            'disable' => $this->integer()->defaultValue(0),
            'sort' => $this->integer()->defaultValue(0),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%baner}}');
    }
}
