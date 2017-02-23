<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_identification".
 *
 * @property integer $id
 * @property string $description
 *
 * @property User[] $users
 */
class TypeIdentification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_identification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Type Identification'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['FK_id_type_identification' => 'id']);
    }
}
