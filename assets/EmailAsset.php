<?php

namespace app\assets;

use Yii;
use yii\swiftmailer\Mailer;

class EmailAsset {

    protected $mailer;
    private $_transport = [];

    function __construct() {

        $this->mailer = Yii::$app->mailer;
    }

    public function setTransport($transport) {
        // if (!is_array($transport) && !is_object($transport)) {
        //     throw new InvalidConfigException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        // }
        $this->_transport = $transport;
    }

    public function createSwiftMailer() {

        $this->mailer->setTransport($this->_transport);
    }

    public function sendMail($from, $to, $subject, $layout, $arg, $fromName = 'Tiver') {
        $time = -microtime(true);
        try {
            Yii::error(json_encode(['mensaje' => "Envio de email " . $time . " " . $to . " Mailer: " . json_encode($this->mailer) . " Transport: " . json_encode($this->_transport)]));
            $email = $this->mailer->compose('@app/mail/' . $layout, $arg)
                    ->setFrom([$from => $fromName])
                    ->setTo($to)
                    ->setSubject($subject)
                    ->send();
            $time += microtime(true);
            Yii::error(json_encode(['mensaje' => "Envio de email " . $time . " " . $to . " Mailer: " . json_encode($email) . " Transport: " . json_encode($this->_transport)]));
            return true;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            //print $e->getMessage();
            return false;
        }
        return false;
    }

    public function sendMailText($from, $to, $subject, $text) {
        try {
            return $this->mailer->compose()
                            ->setFrom($from, $fromName)
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setTextBody($text)
                            ->send();
        } catch (\Exception $e) {
            Yii::trace($e->getMessage());
            return false;
        }
        return false;
    }

}
