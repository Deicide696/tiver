<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_service_history".
 *
 * @property integer $id
 * @property string $date
 * @property string $time
 * @property integer $user_id
 * @property string $address
 * @property string $name
 * @property string $last_name
 * @property integer $service_id
 * @property string $category
 */
class VwServiceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_service_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'service_id'], 'integer'],
            [['date', 'time'], 'safe'],
            [['user_id', 'address', 'name', 'last_name', 'service_id'], 'required'],
            [['address', 'name', 'last_name', 'category'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'time' => 'Time',
            'user_id' => 'User ID',
            'address' => 'Address',
            'name' => 'Name',
            'last_name' => 'Last Name',
            'service_id' => 'Service ID',
            'category' => 'Category',
        ];
    }
}
