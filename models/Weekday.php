<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weekday".
 *
 * @property integer $id
 * @property string $weekdays
 */
class Weekday extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weekday';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weekdays'], 'required'],
            [['weekdays'], 'string', 'max' => 9]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weekdays' => 'Día',
        ];
    }
}
