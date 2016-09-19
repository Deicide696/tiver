<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modifier_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Modifier[] $modifiers
 */
class TypeModifier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_modifier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nivel',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifiers()
    {
        return $this->hasMany(Modifier::className(), ['type_modifier_id' => 'id']);
    }
}
