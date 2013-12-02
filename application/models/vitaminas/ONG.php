<?php
/* Vitamina: Regalas todos tus puntos a un adversario */

/*
	Parametros disponibles
		$target_id 				-> id del usuario objetivo
		$CI->bitauth->user_id 	-> id del usuario logueado
*/

//obtener datos del usuario logueado

		$own = $CI->bitauth->get_user_by_id($CI->bitauth->user_id);
		$own_puntos = $own->puntos;
		$own_racha = $own->racha;

//obtener datos del usuario target

		$target = $CI->bitauth->get_user_by_id($target_id);
		$target_puntos = $target->puntos;
		$target_racha =  $target->racha;

//manipular racha o puntos al gusto


//grabar los puntos del target y opcionalmente los del usuario registrado
		
	if ($own->user_id != $target_id){
		$CI->bitauth->update_user(
			$own->user_id,
			array('puntos'=> 0)
			);
		
		$CI->bitauth->update_user(
			$target_id,
			array('puntos'=> $target_puntos + $own_puntos)
			);
	}
		
