<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupon_has_team".
 *
 * @property integer $id
 * @property integer $team_id
 * @property integer $coupon_id
 *
 * @property Coupon $coupon
 * @property Team $team
 */
class CouponHasTeam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon_has_team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id', 'coupon_id'], 'required'],
            [['team_id', 'coupon_id'], 'integer'],
            [['coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coupon::className(), 'targetAttribute' => ['coupon_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'team_id' => Yii::t('app', 'Team ID'),
            'coupon_id' => Yii::t('app', 'Coupon ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
