<?php

use yii\db\Migration;

/**
 * Class m181119_093538_create_table_category
 */
class m181119_093538_create_table_category extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('父类ID'),
            'category' => $this->string(50)->notNull()->comment('类别名称'),
            'pic' => $this->string()->defaultValue('')->comment('类别图片'),
            'remark' => $this->string()->comment('描述'),
            'search_text' => $this->string()->comment('搜索拼音'),
        ]);

        $this->createIndex('idx_parent_id', 'category', 'parent_id');
    }

    public function down()
    {
        $this->dropTable('category');
    }
}
