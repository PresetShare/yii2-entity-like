<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%entity_like}}`.
 */
class m220326_214442_create_entity_like_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%entity_like}}', [
            'id' => $this->primaryKey(),
            'entity_alias' => $this->string(48)->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(1)->notNull()
        ], Yii::$app->db->driverName == 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

        $this->createIndex('idx_entity_like_unique', '{{%entity_like}}', ['entity_alias', 'entity_id', 'user_id'], true);
        $this->createIndex('idx_entity_like_active', '{{%entity_like}}', ['active']);

        $this->createTable('{{%entity_like_counter}}', [
            'id' => $this->primaryKey(),
            'entity_alias' => $this->string(48)->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'value' => $this->integer()->notNull()->defaultValue(0),
        ], Yii::$app->db->driverName == 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

        $this->createIndex('idx_entity_like_uniq', '{{%entity_like_counter}}', ['entity_alias', 'entity_id'], true);
        $this->createIndex('idx_entity_like_counter_count', '{{%entity_like_counter}}', 'value');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%entity_like_counter}}');
        $this->dropTable('{{%entity_like}}');
    }
}
