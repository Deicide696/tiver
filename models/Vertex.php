<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vertex".
 *
 * @property integer $id
 * @property double $lat
 * @property double $lng
 * @property integer $zone_id
 *
 * @property Zone $zone
 */
class Vertex extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vertex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lat', 'lng'], 'number'],
            [['zone_id'], 'required'],
            [['zone_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'zone_id' => 'Zone ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zone::className(), ['id' => 'zone_id']);
    }
}
