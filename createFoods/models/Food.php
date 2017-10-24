<?php
namespace app\modules\createFoods\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\createFoods\models\Ingredients;

class Food extends ActiveRecord
{
    public $ingredientsCheckbox;

    public static function tableName() {
        return 'food';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'Наименование',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'match', 'pattern' => '#^[a-zа-яё\s\-_]{2,250}$#iu', 'message' => 'Не корректные символы'],
        ];
    }

    public function getIngredients(){
        return $this->hasMany(Ingredients::className(), ['id' => 'ingredients_id'])
            ->viaTable('ingredients_food', ['food_id' => 'id']);
    }

    /**
     * Finds food by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findById($id) {
        return self::findOne(['id' => $id]);
    }

    /**
     * Finds food by the given name.
     *
     * @param string|integer $name the name to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findByName($name) {
        return self::findOne(['name' => $name]);
    }

    /**
     * @return bool
     */
    public function saveAll(){
        if($this->validate() && $this->save()){
            foreach ($this->ingredientsCheckbox as $ingredient){
                $modelIngredient = Ingredients::findOne($ingredient);
                if(!empty($modelIngredient)){
                    $this->link('ingredients', $modelIngredient);
                }
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function updateLinks(){
        $ingredientsCheckbox = $this->ingredientsCheckbox;
        foreach ($this->ingredients as $ingredient){
            if($i = array_search($ingredient->id, $ingredientsCheckbox)){
                unset($ingredientsCheckbox[$i]);
            }
            else{
                $modelIngredient = Ingredients::findOne($ingredient->id);
                if(!empty($modelIngredient)){
                    $this->unlink('ingredients', $modelIngredient, true);
                }
            }
        }
        if(!empty($ingredientsCheckbox)) {
            foreach ($ingredientsCheckbox as $ingredient) {
                $modelIngredient = Ingredients::findOne($ingredient);
                if (!empty($modelIngredient)) {
                    $this->link('ingredients', $modelIngredient);
                }
            }
        }
        return true;
    }

    public function deleteFood(){
        if($food = self::findById((int)Yii::$app->request->get('id'))) {
            $modelIngredient = Ingredients::find((int)Yii::$app->request->get('id'));
            if(!empty($modelIngredient)){
                foreach ($modelIngredient as $ingredient) {
                    $this->unlink('ingredients', $ingredient, true);
                }
            }
            if($food->delete()){
                Yii::$app->session->setFlash('success', 'Блюдо удалено');
                return true;
            }

        }
        else{
            Yii::$app->session->setFlash('success', 'Такого блюда не существует.');
            return false;
        }
    }

    /**
     * @return bool|string
     */
    public function getIngredientsOfFood(){
        $ingredients = $this->ingredients;
        $composition = '';
        foreach ($ingredients as $ingredient){
            $composition .= ($ingredient['active'] == 1 ? '<span class="label label-success">'.$ingredient['name'].'</span>'
                    : '<span class="label label-danger">'.$ingredient['name'].'</span>').'<br>';
        }
        if(!empty($composition)){
            $composition = substr($composition, 0, -4);
        }
        return $composition;
    }

}