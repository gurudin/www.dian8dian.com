<?php

use yii\db\Migration;

/**
 * Class m181130_032225_create_tags
 */
class m181130_032225_create_tags extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('tags', [
            'id' => $this->primaryKey(),
            'fk_category_id' => $this->integer()->notNull()->comment('类别外键'),
            'title' => $this->string(50)->notNull()->comment('标题'),
            'alias' => $this->string(150)->notNull()->comment('别名'),
            'recommend' => $this->tinyInteger(0)->notNull()->comment('状态 0:不推荐 1:推荐'),
        ]);

        $this->createIndex('idx_fk_category_id_title', 'tags', ['fk_category_id','title']);
        $this->createIndex('idx_recommend', 'tags', 'recommend');
    }

    public function down()
    {
        $this->dropTable('tags');
    }
}
