<?php

return [
    'adminEmail' => 'admin@example.com',
    'iconEnabled' => '<div style="text-align: center"><span class="glyphicon glyphicon-ok"></span></div>',
    'iconDisabled' => '<div style="text-align: center"><span class="glyphicon glyphicon-remove"></span></div>',
    //tipo de notificaciones que se envían
    'notification_type_assigned_expert_immediate' => 1,
    'notification_type_assgigned_expert' => 2,
    'notification_type_edited_expert' => 3,
    'notification_type_canceled_expert' => 4,
    'notification_type_chat_expert' => 5,
    'notification_type_checkout_user' => 1,
    'notification_type_canceled_user' => 2,
    'notification_type_chat_user' => 3,
    //fb login
    'fb_app_id' => '802666713197368',
    'fb_app_secret' => '6950db3ab678df4da5592fc074e2a40c',
    //GCM notificaitons
    /* 'gcm_api_key_user' => "AIzaSyDpDVRxDjsEjJd9ftODUIitRmfC4Ob4B5w",
      'gcm_api_key_especialista' => "AIzaSyDVqTTy77E4Cd-0m-OCgLY_Ay296rREGdI",
      'gcm_url'=>"https://gcm-http.googleapis.com/gcm/send", */

    //FCM notificaitons
//    'gcm_api_key_user' => "AIzaSyAB8v_a_XZ-2odgqna8RkY4QQJSzTCcivA",
//    'gcm_api_key_especialista' => "AIzaSyBptNyXz82401LJReQjLqpTVWCa7FjeJes",
//    'fcm_api_key_user' => "AAAAmKn_jqY:APA91bF4zb2eGErXnP-LCFEh79TwRPQA_VtSG9xCvUjA9NwmECGU1ZPEph2-mTZ0-hpXsIHCeMavOxORPEZS2hQ9SUTdPkMPRzW8uPjqSII_a81ybGZ_gElZv-NYeUAsB0Wq1CKz71A88PyufHXzDKMemqUR-fC0bQ",
    'fcm_url' => "https://fcm.googleapis.com/fcm/send",
    'fcm_api_key_especialista' => "AAAAxz4q-fM:APA91bGslBO6y0tf1cR9c5bhUqgBCHDLpcbM0wCzNNOfQmGdllBp07rsghKtAbWQS-nR7YRVcl5qHsIwcQfDuqpRFOnBGf7m8XWZcAoupE9diMpircDO1n8EzJ4SCQ652FBYNZseAtIznxH-LeWMptxJgtACNbRRhg",
    'send_firebase' => TRUE,
    //One Signal notifications
    'os_id_user' => '02fc2d67-0e8a-4cce-b8c5-b8665011a490',
    'os_api_key_user' => 'NjEzNDRmNjktZmY1Yy00NzZjLTgzYTQtOTdhOTEzNDMyNGRm',
    'os_id_expert' => '2a7d2123-3fc8-418b-b443-2fb6576cffc7',
    'os_api_key_expert' => 'ZTA0OWU5YjYtMWVjOS00M2NhLWFkOWYtYTY5MDVlNTA1YjZm',
    //pusher
    'pusher_app_id' => '196247',
    'pusher_app_key' => '0eb365205726b8671b22',
    'pusher_app_secret' => '74135ea3fe855fbec602',
    'pusher_channel_vip' => 'serv_inmediato',
    'pusher_channel_chat' => 'serv_chat',
    'pusher_event_location' => 'experto_inmediato_posicion',
    //iterator service
    'seconds_wait' => 180, //tiempo para esperar la respuesta de un servicio agendado
    'seconds_wait_inmediato' => (60), //tiempo para esperar la respuesta de un servicio inmediato
    //'path_scripts'=>'/Applications/MAMP/htdocs/tiver',//local - ruta para ejecutar scripts
   // 'path_scripts' => '/var/www/html/tiver', //prd
    //
		'walk_time' => 30, //minutos que demoraría un especialista en llegar de un servicio a otro, se usa para calcular la disponibilidad del experto
    'tax_percent' => 0.032, //Corresponde al 20% (comision) del valor del servicio * 16% (IVA) -> 0.2*0.16
    //Sendgrid
    'sengrid_user' => 'Tiver',
    'sendgrid_pass' => 'Pichinde2015',
    'sendgrid_from_name' => 'Tiver',
    'sendgrid_from' => 'tiver@zugartek.com',
    'sendgrid_template_welcome' => 'ad73c4b5-d671-405f-9331-e93c8f8f4c11',
    'sendgrid_template_pass' => '470a50dd-646e-46c4-93a3-44eb317cdbc6',
    'sendgrid_template_mora' => '195a9946-1eb6-42a4-8d46-09d330a2801c',
    'sendgrid_template_compraok' => '021f7dd6-1397-4c8a-9686-084e458140be',
    'sendgrid_template_cancelado' => 'eb04b559-330e-494b-90d2-664524cdedeb',
    'vw_actual_service' => "SELECT
        `a`.`id` AS `id`,
        `a`.`date` AS `date`,
        `a`.`time` AS `time`,
        `a`.`user_id` AS `user_id`,
        `a`.`address` AS `address`,if((`a`.`state` = 1),`b`.`name`,'') AS `name`,if((`a`.`state` = 1),`b`.`last_name`,'') AS `last_name`,
        `a`.`service_id` AS `service_id`,
        `a`.`state` AS `status`,
        `a`.`expert_id` AS `expert_id`,
        `b`.`phone` AS `phone`,
        `a`.`lat` AS `lat`,
        `a`.`lng` AS `lng`,
        `a`.`comment` AS `comment`,(select `assigned_service_has_modifier`.`modifier_id`
         FROM `assigned_service_has_modifier` where (`assigned_service_has_modifier`.`assigned_service_id` = `a`.`id`)) AS `modifier_id`,(select `cat`.`id` from (`category_service` `cat` join `service` `ser`) where ((`ser`.`category_service_id` = `cat`.`id`) and (`ser`.`id` = `a`.`service_id`))) AS `category_id` from (`assigned_service` `a` join `expert` `b`) where (`a`.`expert_id` = `b`.`id`) 
         and ((`a`.`user_id` = :user_id )
         or (`a`.`id` = :id ))
        order by `a`.`date` desc,`a`.`time` desc,`a`.`created_date` desc",
    'vw_actual_service_expert' => "SELECT
        `a`.`id` AS `id`,
        `a`.`date` AS `date`,
        `a`.`time` AS `time`,
        `a`.`user_id` AS `user_id`,
        `a`.`address` AS `address`,
        `b`.`first_name` AS `name`,
        `b`.`last_name` AS `last_name`,
        `a`.`service_id` AS `service_id`,
        `a`.`state` AS `status`,
        `a`.`expert_id` AS `expert_id`,
        `a`.`comment` AS `comment`,
        `a`.`lat` AS `lat`,
        `a`.`lng` AS `lng`,
        `b`.`phone` AS `phone`,(select `assigned_service_has_modifier`.`modifier_id`
        FROM `assigned_service_has_modifier` where (`assigned_service_has_modifier`.`assigned_service_id` = `a`.`id`)) AS `modifier_id`,(select `cat`.`id` from (`category_service` `cat` join `service` `ser`) where ((`ser`.`category_service_id` = `cat`.`id`) and (`ser`.`id` = `a`.`service_id`))) AS `category_id` from (`assigned_service` `a` join `user` `b`) where (`a`.`user_id` = `b`.`id`) 
        and ((`a`.`user_id` = :user_id)
         or (`a`.`state` = :status));
        order by `a`.`date` desc,`a`.`time` desc,`a`.`created_date` desc",
    'vw_service_history' => "SELECT
        `a`.`id` AS `id`,
        `a`.`date` AS `date`,
        `a`.`time` AS `time`,
        `a`.`user_id` AS `user_id`,
        `a`.`address` AS `address`,
        `b`.`name` AS `name`,
        `b`.`last_name` AS `last_name`,
        `a`.`service_id` AS `service_id`,
        `a`.`state` AS `status`,
        `a`.`expert_id` AS `expert_id`,
        `b`.`phone` AS `phone`,
        `a`.`lat` AS `lat`,
        `a`.`lng` AS `lng`,
        `a`.`comment` AS `comment`,(select `service_history_has_modifier`.`modifier_id`
         FROM `service_history_has_modifier` where (`service_history_has_modifier`.`service_history_id` = `a`.`id`)) AS `modifier_id`,(select `cat`.`id` from (`category_service` `cat` join `service` `ser`) where ((`ser`.`category_service_id` = `cat`.`id`) and (`ser`.`id` = `a`.`service_id`))) AS `category_id` from (`service_history` `a` join `expert` `b`) where (`a`.`expert_id` = `b`.`id`) 
         and ((`a`.`user_id` = :user_id)
         or (`a`.`id` = :id)
         or (`a`.`state` = :status))
        order by `a`.`date` desc,`a`.`time` desc",
    'vw_service_history_expert' => "SELECT
        `a`.`id` AS `id`,
        `a`.`date` AS `date`,
        `a`.`time` AS `time`,
        `a`.`user_id` AS `user_id`,
        `a`.`address` AS `address`,
        `b`.`first_name` AS `name`,
        `b`.`last_name` AS `last_name`,
        `a`.`service_id` AS `service_id`,
        `a`.`state` AS `status`,
        `a`.`expert_id` AS `expert_id`,
        `a`.`comment` AS `comment`,
        `a`.`lat` AS `lat`,
        `a`.`lng` AS `lng`,
        `b`.`phone` AS `phone`,(select `assigned_service_has_modifier`.`modifier_id`
        FROM `assigned_service_has_modifier` where (`assigned_service_has_modifier`.`assigned_service_id` = `a`.`id`)) AS `modifier_id`,(select `cat`.`id` from (`category_service` `cat` join `service` `ser`) where ((`ser`.`category_service_id` = `cat`.`id`) and (`ser`.`id` = `a`.`service_id`))) AS `category_id` from (`assigned_service` `a` join `user` `b`) where (`a`.`user_id` = `b`.`id`) 
        and ((`a`.`expert_id` = :expert_id)
        or (`a`.`state` = :status))
        order by `a`.`date` desc,`a`.`time` desc,`a`.`created_date` desc",
    
    ];
