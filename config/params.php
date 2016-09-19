<?php

return [
    'adminEmail' => 'admin@example.com',
		'iconEnabled' => '<span class="glyphicon glyphicon-ok"></span>',
		'iconDisabled' => '<span class="glyphicon glyphicon-remove"></span>',
		
		//tipo de notificaciones que se envían
		
		'notification_type_assigned_expert_immediate'=>1,
		'notification_type_assgigned_expert'=>2,
		'notification_type_edited_expert'=>3,
		'notification_type_canceled_expert'=>4,
		'notification_type_chat_expert'=>5,
		
		'notification_type_checkout_user'=>1,
		'notification_type_canceled_user'=>2,
		'notification_type_chat_user'=>3,
		
		//fb login
		'fb_app_id' => '802666713197368',
		'fb_app_secret' => '6950db3ab678df4da5592fc074e2a40c',
		
		//GCM notificaitons
		'gcm_api_key_user' => "AIzaSyDpDVRxDjsEjJd9ftODUIitRmfC4Ob4B5w",
		'gcm_api_key_especialista' => "AIzaSyDVqTTy77E4Cd-0m-OCgLY_Ay296rREGdI",
		'gcm_url'=>"https://gcm-http.googleapis.com/gcm/send",
		
		//One Signal notifications
		'os_id_user'=>'02fc2d67-0e8a-4cce-b8c5-b8665011a490',
		'os_api_key_user'=>'NjEzNDRmNjktZmY1Yy00NzZjLTgzYTQtOTdhOTEzNDMyNGRm',
		'os_id_expert'=>'2a7d2123-3fc8-418b-b443-2fb6576cffc7',
		'os_api_key_expert'=>'ZTA0OWU5YjYtMWVjOS00M2NhLWFkOWYtYTY5MDVlNTA1YjZm',
		
		//pusher
		'pusher_app_id'=>'196247',
		'pusher_app_key'=>'0eb365205726b8671b22',
		'pusher_app_secret'=>'74135ea3fe855fbec602',
		'pusher_channel_vip'=>'serv_inmediato',
		'pusher_channel_chat'=>'serv_chat',
		'pusher_event_location'=>'experto_inmediato_posicion',
		
		//iterator service
		'seconds_wait'=>60,//tiempo para esperar la respuesta de un servicio agendado
		'seconds_wait_inmediato'=>(45),//tiempo para esperar la respuesta de un servicio inmediato
		//'path_scripts'=>'/Applications/MAMP/htdocs/tiver',//local - ruta para ejecutar scripts
		'path_scripts'=>'/var/www/html/tiver',//prd
		
		//
		'walk_time'=>30//minutos que demoraría un especialista en llegar de un servicio a otro, se usa para calcular la disponibilidad del experto
	
	
		
];
