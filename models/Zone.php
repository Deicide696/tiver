<?php

namespace app\models;

use Yii;
use app\classes\Point;

//GMAPS


/**
 * This is the model class for table "zone".
 *
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 *
 * @property Expert[] $experts
 * @property Vertex[] $vertices
 * @property City $city
 */
class Zone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['city_id'], 'required'],
        [['city_id'], 'integer'],
        [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        'id' => 'ID',
        'name' => 'Zona',       
        'city_id' => 'Ciudad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExperts()
    {
        return $this->hasMany(Expert::className(), ['zone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVertex()
    {
        return $this->hasMany(Vertex::className(), ['zone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }


    //
    //Verificar punto en zona

    public static function getZone($lat,$lng)
    {
        $zones=Zone::find()->all();

        foreach ($zones as $zone) {
         $pol= Vertex::find()->select(['lat','lng'])->where(['zone_id'=>$zone->id])->asArray()->all();
         foreach ($pol as $vertex ) {
            $polygon[]=new Point($vertex['lat'],$vertex['lng']);

        }

        if(Point::pointInPolygon(new Point($lat,$lng), $polygon))
        {
            Yii::info('Se creo un nuevo punto en pointInPolygon, y el id de la  zona es: '.$zone->id, 'add-user-address');
            return $zone->id;
        } else {
            Yii::error('Ocurrio un error la crear nuevo punto en pointInPolygon.', 'add-user-address');
        }
    }
    return false;


}

}
