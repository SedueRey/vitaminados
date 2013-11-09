<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
    {
        parent::__construct();
        $this->load->spark('twiggy/0.8.5');
        $CI = & get_instance();
		$CI->load->add_package_path(APPPATH.'third_party/bitauth/');
		$CI->load->library('bitauth');
		if (!$CI->bitauth->logged_in()) exit;
    }

    function load_vitamina_desc($id = 0){

    	$this->load->model('vitamina');

    	if ($id) {
    		echo $this->vitamina->get_vitamina_desc($id);
    	} else 
    		echo "descripcion no encontrada";

    }
   

   	function load_targets(){

   		$this->load->model('usuario');
   		$CI = & get_instance();
		$CI->load->add_package_path(APPPATH.'third_party/bitauth/');
		$CI->load->library('bitauth');

   		$usuarios = $this->usuario->get_targets();

   		$html = '<p>Tu</p>';

   		$html .= "<div class='target'>
   				<input type='hidden' name='id' value='".$CI->bitauth->user_id."'/>
   				<img class='target-img' src='http://1.gravatar.com/avatar/".$CI->bitauth->user_id."?s=50&d=monsterid&r=G' />
   				<span class='target-nick'>".recortar_texto($CI->bitauth->fullname,10)."</span>
   				<span class='target-puntos'>".$CI->bitauth->puntos."</span>
   				<span class='target-racha'>combo<br>x".$CI->bitauth->racha."</span>
   			</div>";

   		$html .= '<p>Los demás</p>';
   		
   		for ($i = 0 ; $i<14 ;$i++) 
   		foreach ($usuarios as $u) {
   			$html .= "<div class='target'>
   				<input type='hidden' name='id' value='".$u->user_id."'/>
   				<img class='target-img' src='http://1.gravatar.com/avatar/".$u->user_id."?s=50&d=monsterid&r=G' />
   				<span class='target-nick'>".recortar_texto($u->fullname,10)."</span>
   				<span class='target-puntos'>".$u->puntos."</span>
   				<span class='target-racha'>combo<br>x".$u->racha."</span>
   			</div>";
   		}



   		$html .= "<br clear='left'/><button class='md-cancel'>Cancelar</button>";

   		echo $html;

   	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */