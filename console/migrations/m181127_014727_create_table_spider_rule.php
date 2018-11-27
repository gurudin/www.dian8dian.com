<?php

use yii\db\Migration;

/**
 * Class m181127_014727_create_table_spider_rule
 */
class m181127_014727_create_table_spider_rule extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('spider_rule', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('父类id'),
            'url_data' => $this->text()->comment('url规则 json:[{"url":"http://example.com","method":"get"},{"url":"http://example.com","method":"get"}]'),
            'rule' => $this->text()->comment('规则 json:{"mode":"api","title":{"value":"","type":"string"},"tags":{"value":"","type":"array"}}'),
            'status' => $this->tinyInteger(1)->notNull()->comment('状态 1:未爬取 2:已爬取添加到文章 3:已爬取添加到规则 4:规则错误'),
            'dara' => $this->text()->comment('爬取结果'),
        ]);

        $this->createIndex('idx_parent_id', 'spider_rule', 'parent_id');
        $this->createIndex('idx_status', 'spider_rule', 'status');
    }

    public function down()
    {
        $this->dropTable('spider_rule');
    }
}
