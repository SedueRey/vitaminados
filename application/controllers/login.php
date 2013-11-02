<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->spark('twiggy/0.8.5');
        $this->load->library('email');
        $this->load->add_package_path(APPPATH.'third_party/bitauth');
        $this->load->library('bitauth');
    }

    public function index(){


    	if ($this->input->post('email')){
    		
	    	$config['charset'] = 'iso-8859-1';
			$config['mailtype'] = 'html';
			$this->email->initialize($config);

			$this->email->from('no-reply@vitaminados.es', 'Vitaminados');
			$this->email->to($this->input->post('email')); 
			

			//TODO
				/* aquí hay que ver si el correo enviado existe
				en la base de datos.

				si existe , generamos un token nuevo y le enviamos el link

				si no existe, creamos el usuario, ver si se puede traer el 
				nick y clan desde la mlp y creamos el token igualmente*/

			$email = $this->db->escape($this->input->post('email'));
			$search = $this->db->query("select * from bitauth_users where username = $email");
			if (!$search->num_rows()){
				//obtener datos de la mlp para registrarlo o registrarlo sin nick
				$user = array(
				    'username' => $this->input->post('email'),
				    'password' => 'mipeneesmipassword'
				);	
				$new_user = $this->bitauth->add_user($user);
			} else {
				$new_user = $search->result();
				$new_user = $new_user[0];
			}
			//obtener link de login

			$code = $this->bitauth->forgot_password($new_user->user_id);

			$this->email->subject('Link de acceso');
			$this->email->message('Pincha en el enlace para entrar en la web a jugar: <a href="vitaminados.local/jugar">Enlace</a>');	

			echo "<a href='/login/in/$code'>$code</a>"; exit;

			/*if ($this->email->send()){
				$this->twiggy->set(array('enviado'=>true));	
				$this->twiggy->display('info/enviado');
			} else {
				$this->twiggy->set(array('enviado'=>false));	
	    		$this->twiggy->display('info/enviado');
			}*/
    	} else {
			$this->twiggy->set(array('enviado'=>false));	
	    	$this->twiggy->display('/jugar');
    		
    	}
    	
		
    }

    public function in($token=''){

    	$query = $this->db->where('forgot_code', $token)->get('bitauth_users');
		if($query->num_rows())
		{
			//el usuario existe y procedemos a borrarle el token y a loguearlo
			$user = $query->row();
			
			if($this->bitauth->login($user->username, NULL, FALSE, FALSE, $token))
			{
				//funcionó el login
				redirect('/jugar');
			} else {
				redirect('/jugar');
			}
		} else 
		//no valido mando al login
		redirect('/');

    	exit;
    	redirect('/jugar');

    }

    public function setNick(){

    	if ($this->input->post('nick')){
    		if($this->bitauth->logged_in()){
    			$this->bitauth->update_user($this->bitauth->user_id,array('fullname'=>$this->input->post('nick')));
    		}
    	} 
    	redirect('/jugar');
    }

    public function out(){
    	$this->bitauth->logout();
    	redirect('/jugar');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */