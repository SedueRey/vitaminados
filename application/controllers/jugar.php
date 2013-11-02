<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jugar extends CI_Controller {

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
        $this->load->add_package_path(APPPATH.'third_party/bitauth');
        $this->load->library('bitauth');

    }

    public function index(){
    	$num_games = 3;
    	$this->load->library('bitauth');


		//change this code after install
		$this->load->spark('twiggy/0.8.5');

		//checkeo del captcha 
		if ($_POST){

			$consecutivos = $this->input->post('consecutivos');

			$captchacorrecto = false;

			//segun el type lo comprobaremos de una forma u otra, estos check solo tienen
			//que darle un valor a captchacorrecto (true/false)
			switch($this->input->post('type')){
				case 'recaptcha':
					include('includes/recaptcha_check.php');
				break;
				case 'ayah':
					include('includes/ayah_check.php');
				break;
				default:
					// hackerrr!!
					$consecutivos = 0;
				break;
			}

			//acción por defecto al rellenar captchas
			if ($captchacorrecto) {
				//TODO contabilizar combos y adjudicar vitaminas nuevas
				$consecutivos++;
			} else {
				//seteamos a 0 el combo y no damos una mierda
				$consecutivos=0;
			}

		} else {
			//primera carga de la web
			$consecutivos = 0;
		}


		// selector de juego que aparecerá en el siguiente turno
		switch(0) {
			case 0:
				$type = 'recaptcha';
			break;
			case 1:
				$type = 'ayah';
			break;
			case 2:
				$type = 'recaptcha';
			break;
			//añadir aquí resto de juegos para que aparezcan

			default:
				$type = 'recaptcha';
			break;
		}

		switch ($type){
			case 'recaptcha':
				$data = array('consecutivos' => $consecutivos,'type' => 'recaptcha'); 
			break;

			case 'ayah': 
				$this->load->helper('ayah/ayah');
				$ayah = new AYAH();
				$data = array('consecutivos' => $consecutivos,'type' => 'ayah','ayah_game'=> $ayah->getPublisherHTML());
			break;

		}

		if( ! $this->bitauth->logged_in())
		{
		    $data['logueado'] = false;
		} else {
		    $data['logueado'] = true;
		    $data['fullname'] = $this->bitauth->fullname;
		}

		$this->twiggy->set($data);
		$this->twiggy->display('captchas/'.$type,$data);

		
    }

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */