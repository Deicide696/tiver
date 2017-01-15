<?php

namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;
use app\assets\EmailAsset;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'app\models\User',
                'filter' => ['enable' => User::STATUS_ACTIVE],
                'message' => 'No hay ningún usuario con este correo electrónico.'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Correo electrónico',
        ];
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail() {
        
        $user = User::findOne([
                    'enable' => User::STATUS_ACTIVE,
                    'email' => $this->email,
        ]);
        if ($user) {
            $tokenType = TypeToken::findOne(['name' => 'remember-password']);
            $token = LogToken::findOne(['FK_id_token_type' => $tokenType->id, 'FK_id_user' => $user->id, 'enable' => 1]);
            
            if (!$token || !LogToken::isTokenValid($token->token)) {
                if ($token) {
                    $token->status = 0;
                    $token->save();
                }
                $token = new LogToken();
                $arrayLog = [
                    'LogToken' => [
                        'token' => Yii::$app->security->generateRandomString() . '_' . time(),
                        'connection_ip' => Yii::$app->request->userIP,
                        'status' => 1,
                        'FK_id_token_type' => $tokenType->id,
                        'FK_id_user' => $user->id,
                        'created_date' => date('Y-m-d H:i:s'),
                        'updated_date' => date('Y-m-d H:i:s')
                    ]
                ];
                if (!$token->load($arrayLog) || !$token->save()) {
                    return false;
                }
            }

            $assetEmail = new EmailAsset();
            if ($user->email != '') {
                
                $subs = ['{{ username }}' => $user->first_name,
                        //'{{ token }}' => $token->token
                ];
                $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
                $email = new \SendGrid\Email ();
                $email
                        ->setFrom(Yii::$app->params ['sendgrid_from'])
                        ->setFromName(Yii::$app->params ['sendgrid_from_name'])
                        ->addTo($user->email)
                        ->setSubject(' ')
                        ->setHtml(' ')
                        ->setHtml(' ')
                        ->addSubstitution('{{ username }}', [$user->first_name])
                        ->addSubstitution('{{ token }}', [$token->token])->addFilter('templates', 'enabled', 1)
                        ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_pass']);
                $resp = $sendGrid->send($email);
                return true;
            }
        }
        return false;
    }

}
