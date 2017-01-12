<?php

namespace app\commands;

use yii\console\Controller;
use Yii;
use app\models\AssignedService;
use app\models\Zone;
use app\models\Expert;
use yii\db\Expression;
use app\models\User;
use app\models\LogAssignedService;

class TasksController extends Controller {

    public function actionCheckService($idService, $date, $time) {

        $services = AssignedService::findOne([
                    "id" => $idService,
                    "date" => $date,
                    "time" => $time
                ]);

        $num_intent = $services->getNumAttempts();

        print PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL;
        print "$idService -> $date $time -> $num_intent" . PHP_EOL;

        if ($services != null) {
            if ($services->state == 0) {
                //Insertar LOG de omitido, primero se valida si no fue rechazado
                $model_l = LogAssignedService::find()->where(['assigned_service_id' => $services->id, 'expert_id' => $services->expert_id, 'rejected' => '1', 'date' => $date, 'time' => $time])->one();

                if ($model_l == null) {
                    $model_log = new LogAssignedService();
                    $model_log->assigned = "0";
                    $model_log->rejected = "0";
                    $model_log->missed = "1";
                    $model_log->date = $date;
                    $model_log->time = $time;
                    $model_log->accepted = "0";
                    $model_log->attempt = $services->getNumAttempts();
                    $model_log->assigned_service_id = $services->id;
                    $model_log->expert_id = $services->expert_id;
                    $model_log->save();
                }
                if ($num_intent > 50) {
                    print "Demasiados intentos" . PHP_EOL;

                    $services->delete();

                    $tokens = User::findOne(["id" => $services->user_id])->getPushTokens();

                    // print_r($tokens);
                    $data = [
                        "ticker" => "Servicio cancelado",
                        'type' => Yii::$app->params ['notification_type_canceled_user']
                    ];
                    if ($tokens != null) {
                        Yii::$app->PushNotifier->sendNotificationUserOS("Servicio cancelado", "No encontramos ningún especialista disponible y se ha cancelado el servicio", $data, $tokens);
                    }
                } else {

                    print "Servicio $idService no fue aceptado por el especialista " . $services->getExpertName() . PHP_EOL; // El servicio no ha sido aceptado
                    // Validamos la zona de la dirección
                    $zone = Zone::getZone($services->lat, $services->lng);
                    if (!$zone) {
                        print "Esta dirección se encuentra fuera de la zona de cobertura" . PHP_EOL;
                    } else {
                        $day = date('N', strtotime($services->date));
                        // Buscamos especialistas disponibles para la fecha y hora del servicio
                        $experts = Expert::find()->where(" expert.id<>'$services->expert_id' and  enable='1' and zone_id='$zone' and (schedule.weekday_id='$day' and '$services->time' between schedule.start_time and schedule.finish_time) and (expert_has_service.service_id='$services->service_id')")->joinwith('schedule')->joinwith('assignedService')->joinwith('expertHasService')->orderBy(new Expression('rand()'))->all();

                        foreach ($experts as $expert) {

                            $disponible = $expert->validateDateTime($services->date, $services->time, $services->getDuration());
                            //Validamos que no haya rechazado el servicio previamente
                            $model_l = LogAssignedService::find()->where(['assigned_service_id' => $services->id, 'expert_id' => $expert->id, 'rejected' => '1', 'date' => $date, 'time' => $time])->one();

                            if ($model_l != null) {
                                $disponible = false;
                            }
                            // print "-disp->".$disponible;
                            // print "Experto disponible $expert->id $disponible" . PHP_EOL;
                            if ($disponible) { // Si está disponible
                                $expert_id = $expert->id;
                                break;
                            }
                            // en este punto, el especialista ya está ocupado para ese dia y a esa hora, se valida al siguiente especialista
                        }
                        // No hay más especialistas disponibles
                        if (!isset($expert_id)) {
                            //Cancelamos el servicio


                            $tokens = User::findOne(["id" => $services->user_id])->getPushTokens();

                            $user = User::findOne(["id" => $services->user_id]);
                            $value = $services->getPrice();

                            try {
                                //Enviar mail de pago en mora
                                $sendGrid = new \SendGrid(Yii::$app->params ['sengrid_user'], Yii::$app->params ['sendgrid_pass']);
                                $email = new \SendGrid\Email ();
                                $email->setFrom(Yii::$app->params ['sendgrid_from'])
                                        ->setFromName(Yii::$app->params ['sendgrid_from_name'])
                                        ->addTo($user->email)
                                        ->setSubject(' ')
                                        ->setHtml(' ')
                                        ->setHtml(' ')
                                        ->addSubstitution('{{ username }}', [$user->first_name])
                                        ->addSubstitution('{{ buydate }}', [$services->date])
                                        ->addSubstitution('{{ useraddress }}', [$services->address])
                                        ->addSubstitution('{{ item.servname }}', [$value])
                                        ->addSubstitution('{{ item.servmodif }}', [$value])
                                        ->addSubstitution('{{ item.prodprecio }}', [$value])
                                        ->addSubstitution('{{ item.servesp }}', [$value])
                                        ->addSubstitution('{{ total }}', [$value])
//                                        ->addSubstitution('{{ dateTime }}', [$user->first_name])
//                                        ->addSubstitution('{{ codePlace }}', [$services->date])
//                                        ->addSubstitution('{{ namePlace }}', [$services->address])
//                                        ->addSubstitution('{{ cardType }}', [$value])
//                                        ->addSubstitution('{{ accountType }}', [$value])
//                                        ->addSubstitution('{{ cardNumber }}', [$value])
//                                        ->addSubstitution('{{ feeNumber }}', [$value])
//                                        ->addSubstitution('{{ paymentReference }}', [$value])
//                                        ->addSubstitution('{{ receiptNumber }}', [$value])
//                                        ->addSubstitution('{{ authorizationNumber }}', [$value])
//                                        ->addSubstitution('{{ replyCode }}', [$value])
//                                        ->addSubstitution('{{ description }}', [$value])
//                                        ->addSubstitution('{{ total }}', [$value])
                                        
                                        ->addFilter('templates', 'template_id', Yii::$app->params ['sendgrid_template_cancelado']);
                                $resp = $sendGrid->send($email);
                            } catch (\Exception $e) {
                                Yii::error($e->getMessage());
                                echo "Error al enviar mail" . $e->getMessage() . PHP_EOL;
                            }

                            $services->delete();
                            // print_r($tokens);
                            $data = [
                                "ticker" => "Servicio cancelado",
                                'type' => Yii::$app->params ['notification_type_canceled_user']
                            ];
                            if ($tokens != null) {
                                Yii::$app->PushNotifier->sendNotificationUserOS("Servicio cancelado", "No encontramos ningún especialista disponible y se ha cancelado el servicio", $data, $tokens);
                            }
                            print "No hay especialistas disponibles" . PHP_EOL;
                        } else {
                            // Guardamos la edición
                            $services->expert_id = $expert_id;

                            // Guardamos los cambios
                            if (!$services->save()) {
                                print json_encode($services->getErrors()) . PHP_EOL;
                            } else {
                                // print_r($tokens);
                                // //////////
                                $model_user = User::find()->where([
                                            'id' => $services->user_id
                                        ])->one();

                                $tokens = Expert::findOne(["id" => $expert_id])->getPushTokens();

                                $data = [
                                    "ticker" => "Tienes trabajo",
                                    'time' => $services->time,
                                    'date' => $services->date,
                                    'address' => $services->address,
                                    'lat' => $services->lat,
                                    'id_serv' => $services->id,
                                    'lng' => $services->lng,
                                    'name_user' => $model_user->first_name,
                                    'lastname_user' => $model_user->last_name,
                                    'id_service' => $services->service_id,
                                    'id_modifier' => $services->getModifierId(),
                                    'comments' => $services->comment,
                                    'timestamp' => date("U"),
                                    'time_wait' => Yii::$app->params ['seconds_wait'],
                                    'type' => Yii::$app->params ['notification_type_assgigned_expert']
                                ];
                                if ($tokens != null) {
                                    Yii::$app->PushNotifier->sendNotificationExpertOS("Nuevo servicio", "Tienes un nuevo servicio", $data, $tokens);
                                }

                                $url = Yii::getAlias('@webroot');
                                $script = 'php ' . $url . '/./yii tasks/check-service "' . $idService . '" "' . $date . '" "' . $time . '"';
                                $log = $url . "/logs/$idService.txt";
                                exec("(sleep " . Yii::$app->params ['seconds_wait'] . "; $script >> $log) > /dev/null 2>&1 &");
                                //Insertar LOG de asignación
                                $model_log = new LogAssignedService();
                                $model_log->assigned = "1";
                                $model_log->rejected = "0";
                                $model_log->missed = "0";
                                $model_log->date = $date;
                                $model_log->time = $time;
                                $model_log->accepted = "0";
                                $model_log->attempt = $services->getNumAttempts() + 1;
                                $model_log->assigned_service_id = $services->id;
                                $model_log->expert_id = $services->expert_id;
                                $model_log->save();
                                ///

                                print "Servicio $idService asignado al especialista " . $services->getExpertName() . PHP_EOL; // El servicio no ha sido aceptado
                            }
                        }
                    }
                }
            } else {
                print "Servicio $idService ya fue aceptado" . PHP_EOL;
            }
        } else {
            print "Servicio $idService no existe" . PHP_EOL;
        }
        // Yii::$app->PushNotifier->sendNotificationUserOS ( "Servicio finalizado", "El servicio ha sido cobrado", $data, $tokens );
    }

}
