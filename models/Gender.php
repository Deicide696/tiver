<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gender".
 *
 * @property integer $id
 * @property string $gender
 *
 * @property Expert[] $experts
 * @property User[] $users
 */
class Gender extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gender';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gender' => 'Género',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExperts()
    {
        return $this->hasMany(Expert::className(), ['gender_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['FK_id_gender' => 'id']);
    }
}
