<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ingredients`.
 */
class m171017_120433_create_ingredients_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ingredients', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ingredients');
    }
}
