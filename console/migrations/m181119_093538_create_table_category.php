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
            'remark' => $this->string()->comment('描述'),
            'search_text' => $this->string()->comment('搜索拼音'),
        ]);
    }

    public function down()
    {
        $this->dropTable('hq_scene');
    }
}
