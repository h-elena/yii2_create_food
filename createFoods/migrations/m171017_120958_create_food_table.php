<?php

use yii\db\Migration;

/**
 * Handles the creation of table `food`.
 */
class m171017_120958_create_food_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('food', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('food');
    }
}
