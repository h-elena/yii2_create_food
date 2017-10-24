<?php

use yii\db\Migration;

/**
 * Handles adding position to table `ingredients`.
 */
class m171017_171811_add_position_column_to_ingredients_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('ingredients', 'active', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('ingredients', 'active');
    }
}
