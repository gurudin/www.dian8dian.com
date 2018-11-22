<?php

use yii\db\Migration;

/**
 * Class m181121_041656_create_table_article
 */
class m181121_041656_create_table_article extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'fk_category_id' => $this->integer()->notNull()->comment('类别外键'),
            'title' => $this->string()->notNull()->comment('标题'),
            'title_search' => $this->string()->notNull()->comment('标题搜索拼音'),
            'cover' => $this->string()->comment('封面图片'),
            'remark' => $this->string()->comment('简介'),
            'content' => $this->text()->comment('内容'),
            'tags' => $this->string()->comment('标签'),
            'author' => $this->string('50')->comment('作者'),
            'source' => $this->string()->comment('来源地址'),
            'demo' => $this->string()->comment('demo地址'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('0:下线 1:上线 2:待审核'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
        ]);

        $this->createIndex('idx_fk_category_id', 'article', 'fk_category_id');
        $this->createIndex('idx_status', 'article', 'status');
        $this->createIndex('idx_fk_category_id_status', 'article', ['fk_category_id','status']);
    }

    public function down()
    {
        $this->dropTable('article');
    }
}
