<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_actual_service".
 *
 * @property integer $id
 * @property string $date
 * @property string $time
 * @property integer $user_id
 * @property string $address
 * @property string $name
 * @property string $last_name
 * @property integer $service_id
 * @property integer $status
 * @property integer $expert_id
 * @property integer $phone
 * @property double $lat
 * @property double $lng
 * @property string $comment
 * @property integer $modifier_id
 * @property string $category_id
 */
class VwActualService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_actual_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'service_id', 'status', 'expert_id', 'phone', 'modifier_id', 'category_id'], 'integer'],
            [['date', 'time', 'user_id', 'address', 'name', 'last_name', 'service_id', 'expert_id', 'phone', 'lat', 'lng'], 'required'],
            [['date', 'time'], 'safe'],
            [['lat', 'lng'], 'number'],
            [['address', 'name', 'last_name'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 255]
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
            'status' => 'Status',
            'expert_id' => 'Expert ID',
            'phone' => 'Phone',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'comment' => 'Comment',
            'modifier_id' => 'Modifier ID',
            'category_id' => 'Category ID',
        ];
    }
}
