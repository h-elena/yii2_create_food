<?php

namespace app\modules\createFoods\models;

use Yii;
use yii\db\ActiveRecord;


class Ingredients extends ActiveRecord
{
    const statusActive = 1;
    const statusDeactive = 0;

    public static function tableName() {
        return 'ingredients';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'Наименование'
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

    public function beforeSave($insert) {
        if (parent::beforeSave($insert) && !isset($this->active)) {
            $this->active = self::statusActive;
        }
        return true;
    }

    /**
     * Finds ingredients by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findById($id) {
        return self::findOne(['id' => $id]);
    }

    /**
     * Finds ingredients by the given name.
     *
     * @param string|integer $name the name to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findByName($name) {
        return self::findOne(['name' => $name]);
    }
}