<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_service_history_expert".
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
 * @property string $phone
 * @property integer $modifier_id
 * @property string $category_id
 */
class VwServiceHistoryExpert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_service_history_expert';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'service_id', 'status', 'expert_id', 'phone', 'modifier_id', 'category_id'], 'integer'],
            [['date', 'time'], 'safe'],
            [['user_id', 'address', 'name', 'service_id', 'expert_id'], 'required'],
            [['address', 'name', 'last_name'], 'string', 'max' => 100]
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
            'modifier_id' => 'Modifier ID',
            'category_id' => 'Category ID',
        ];
    }
}
