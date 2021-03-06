<?php
/* Vitamina: Intercambias los parámetros con el objetivo */

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
		$aux_equisde_lol_puntos = $own_puntos;
		$aux_equisde_lol_racha = $own_racha;

		$own_puntos = $target_puntos;
		$own_racha = $target_racha;

		$target_puntos = $aux_equisde_lol_puntos;
		$target_racha = $aux_equisde_lol_racha;

//grabar los puntos del target y opcionalmente los del usuario registrado
		$CI->bitauth->update_user(
					$target_id,
					array('racha'=>$target_racha,
						  'puntos'=>$target_puntos)
				);

			$CI->bitauth->update_user(
					$own->user_id,
					array('racha'=>$own_racha,
						  'puntos'=>$own_puntos)
				);
