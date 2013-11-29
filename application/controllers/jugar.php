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
        $this->load->spark('twiggy/0.8.5');

        if (!$this->bitauth->logged_in()){
        	$this->twiggy->display('jugar');
        	exit;
        }

        $this->load->model(array('vitamina','usuario'));

    }

    public function index(){
    	$num_games = 4;

		//change this code after install
		$this->load->spark('twiggy/0.8.5');

		//checkeo del captcha 
		if ($_POST){

			$captchacorrecto = false;

			//segun el type lo comprobaremos de una forma u otra, estos check solo tienen
			//que darle un valor a captchacorrecto (true/false)
			switch($this->input->post('type')){
				case 'simple':
					include('includes/simple_check.php');
				break;
				case 'recaptcha':
					include('includes/recaptcha_check.php');
				break;
				case 'ayah':
					include('includes/ayah_check.php');
				break;
				case 'securimage':
					include('includes/securimage_check.php');
				break;
				case 'keycaptcha':
					include('includes/keycaptcha_check.php');
				break;

				default:
					// hackerrr!!
					$this->usuario->reset_racha();
				break;
			}

			//acción por defecto al rellenar captchas
			if ($captchacorrecto) {
				//TODO contabilizar combos y adjudicar vitaminas nueva

				$this->usuario->aumenta_puntuacion();

				//aumentar puntuación según el combo

				//adjudicar vitaminas nuevas

				if ($this->bitauth->racha % 500 == 0){
					//megasupervitamina
					$this->vitamina->crear_nueva(1);

				} else if ($this->bitauth->racha % 50 == 0){
					//supervitamina
					$this->vitamina->crear_nueva(2);

				} else if ($this->bitauth->racha % 5 == 0){
					//vitamina
					$this->vitamina->crear_nueva(3);
				}

				

			} else {
				//seteamos a 0 el combo y no damos una mierda
				$this->usuario->reset_racha();
			}

		} else {
			//primera carga de la web
			
		}


		if ($this->bitauth->logged_in()){


			// selector de juego que aparecerá en el siguiente turno
			//TODO en lugar de poner un 0 para forzarlo hay que hacer un random o una secuencia
			switch($this->bitauth->racha % $num_games) {
			//switch(4) {
				case 0:
					$type = 'recaptcha';
				break;
				case 1:
					$type = 'securimage';
				break;
				case 2:
					$type = 'ayah';
				break;
				case 3:
					$type = 'simple';
				break;
				case 4:
					$type = 'keycaptcha';
				break;
				//añadir aquí resto de juegos para que aparezcan

				default:
					$type = 'recaptcha';
				break;
			}

			switch ($type){

				case 'simple':

					$rand1 = rand(1,$this->bitauth->racha);
					$rand2 = rand(1,$this->bitauth->racha);

					$this->session->set_userdata('simple_check',$rand1 + $rand2);

					$data = array('type' => 'simple','rand1'=>$rand1,'rand2'=>$rand2);
				break;

				case 'recaptcha':
					$data = array('type' => 'recaptcha'); 
				break;

				case 'ayah': 
					$this->load->helper('ayah/ayah');
					$ayah = new AYAH();
					$data = array('type' => 'ayah','ayah_game'=> $ayah->getPublisherHTML());
				break;

				case 'securimage':
					$data = array('type' => 'securimage');
				break;

				case 'keycaptcha':
					$this->load->helper('keycaptcha/keycaptcha');
					$kc_o = new KeyCAPTCHA_CLASS();
					$data = array('type' => 'keycaptcha','keycaptcha_game' => $kc_o->render_js());
				break;

			} 

			$data['logueado'] = $this->bitauth->logged_in();
			$data['user'] = ($data['logueado'])?$this->bitauth->get_user_by_id($this->bitauth->user_id):false;
			$data['vitaminas'] = $this->vitamina->get_vitaminas();
			$data['ranking'] = ($data['logueado'])?$this->usuario->get_rank($this->bitauth->user_id):'0';

			if ($this->bitauth->clan != '') {
				$data['clancolor'] = "#".substr(md5($this->bitauth->clan),0,6);
			} else {
				$data['clancolor'] = '#ffffff';
			}

			$fechafin = $this->usuario->get_fechafin();
			$data['fechafin'] = date_parse($fechafin[0]->fin);

			$data['ataques'] = $this->usuario->get_ataques($this->bitauth->user_id);


			$this->twiggy->set($data);
			$this->twiggy->display('captchas/'.$type,$data);


		} else {
			$type = '';
			$data = array('type' => '');
			$data['logueado'] = $this->bitauth->logged_in();
			$data['user'] = ($data['logueado'])?$this->bitauth->get_user_by_id($this->bitauth->user_id):false;
			$data['vitaminas'] = $this->vitamina->get_vitaminas();
			$data['ranking'] = ($data['logueado'])?$this->usuario->get_rank($this->bitauth->user_id):'0';
				$fechafin = $this->usuario->get_fechafin();
			$data['fechafin'] = date_parse($fechafin[0]->fin);
			$this->twiggy->set($data);
			$this->twiggy->display('jugar',$data);
		}
		


		

		
    }

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */