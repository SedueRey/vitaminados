<?php
/* Vitamina: Vuelve a empezar */

/*
	Parametros disponibles
		$target_id 				-> id del usuario objetivo
		$CI->bitauth->user_id 	-> id del usuario logueado
*/

//obtener datos del usuario logueado

		$own = $CI->bitauth->get_user_by_id($CI->bitauth->user_id);
		$own_puntos = $me->puntos;
		$own_racha = $me->racha;

//obtener datos del usuario target

		$target = $CI->bitauth->get_user_by_id($target_id);
		$target_puntos = 0;
		$target_racha =  0;

//manipular racha o puntos al gusto


//grabar los puntos del target y opcionalmente los del usuario registrado
		$CI->bitauth->update_user(
			$target_id,
			array('racha'=>$target_racha,
				'puntos'=>$target_puntos)
			);
		