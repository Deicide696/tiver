<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "housing_type".
 *
 * @property integer $id
 * @property string $housing_type
 */
class TypeHousing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_housing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['housing_type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'housing_type' => 'Housing Type',
        ];
    }
}
