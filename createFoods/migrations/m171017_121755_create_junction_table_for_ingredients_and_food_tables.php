<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ingredients_food`.
 * Has foreign keys to the tables:
 *
 * - `ingredients`
 * - `food`
 */
class m171017_121755_create_junction_table_for_ingredients_and_food_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ingredients_food', [
            'id' => $this->primaryKey(),
            'ingredients_id' => $this->integer(),
            'food_id' => $this->integer()
        ]);

        // creates index for column `ingredients_id`
        $this->createIndex(
            'idx-ingredients_food-ingredients_id',
            'ingredients_food',
            'ingredients_id'
        );

        // add foreign key for table `ingredients`
        $this->addForeignKey(
            'fk-ingredients_food-ingredients_id',
            'ingredients_food',
            'ingredients_id',
            'ingredients',
            'id',
            'CASCADE'
        );

        // creates index for column `food_id`
        $this->createIndex(
            'idx-ingredients_food-food_id',
            'ingredients_food',
            'food_id'
        );

        // add foreign key for table `food`
        $this->addForeignKey(
            'fk-ingredients_food-food_id',
            'ingredients_food',
            'food_id',
            'food',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `ingredients`
        $this->dropForeignKey(
            'fk-ingredients_food-ingredients_id',
            'ingredients_food'
        );

        // drops index for column `ingredients_id`
        $this->dropIndex(
            'idx-ingredients_food-ingredients_id',
            'ingredients_food'
        );

        // drops foreign key for table `food`
        $this->dropForeignKey(
            'fk-ingredients_food-food_id',
            'ingredients_food'
        );

        // drops index for column `food_id`
        $this->dropIndex(
            'idx-ingredients_food-food_id',
            'ingredients_food'
        );

        $this->dropTable('ingredients_food');
    }
}
