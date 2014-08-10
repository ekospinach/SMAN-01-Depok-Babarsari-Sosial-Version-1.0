<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/controllers/base/base.php';

class siswa extends base{

	//membuat construktor
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index(){
		echo 'Error 403 : Forbidden Access';
	}

	//untuk login siswa
	public function login(){
		$this->load->library('form_validation');//form validation untuk siswa
		//validasi 
		$this->form_validation->set_rules('login-siswa-nis', 'Login-siswa-nis', 'required|trim|xss_clean|callback_validate_credentials');
		$this->form_validation->set_rules('login-siswa-password', 'Login-siswa-password', 'required|md5|trim|xss_clean');
		//check valid
		if($this->form_validation->run()) {
			//ambil data
			$nis = $this->input->post('login-siswa-nis');
			$password = $this->input->post('login-siswa-password');
			//cek apakah nis dan password sesuai
			$userdata = $this->m_siswa->can_log_in($nis, $password);
			if(!empty($userdata)) { //jika siswa ditemukan
				//set session
				$sessionData = $userdata;
				$sessionData['siswa_logged_in'] = 1;
				//activate session
				$this->session->set_userdata($sessionData);
				//alert login berhasil
				echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Login Sebagai Siswa Berhasil');
					window.location.href='".site_url('siswa/timeline')."';
				</SCRIPT>");
			} else { //jika siswa tidak ditemukan
				echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Terjadi kesalahan, silahkan ulangi lagi');
					window.location.href='".site_url()."';
				</SCRIPT>");
			}
		} else { //jika form validasi tidak jalan
			$data['title'] = 'Error Login';
			$this->defaultdisplay('publik/home', $data);//menampilkan publik home dan error
		}
	}

	//validasi login siswa
	public function validate_credentials(){
		$NIS = $this->input->post('login-siswa-nis'); 
		$password = md5($this->input->post('login-siswa-password'));
		//struktur kendali untuk cek bisa login atau tidak
		if ($this->m_siswa->can_log_in($NIS, $password)){
			return true;
		} else {
			//memberikan pesan jika login tidak berhasil
			$this->form_validation->set_message('validate_credentials', 'NIS/Password salah');
			return false;
		}
	}

	//UPDATE PROFILE
	public function updateprofile(){
		$alamat = $this->input->post('txtAlamat');
		$moto = $this->input->post('txtMoto');
		$img = $_FILES['fileAvatar'];
		//GET PICTURE
		$this->load->library('form_validation');//CALL FORM VALIDATION LIBRARY

	}

	//UPDATE PASSWORD
	public function updatepassword(){
		$pass = $this->session->userdata('password');
		$txtrecentpass =md5($this->input->post('txtrecentpass'));
		$txtnewpass1 = $this->input->post('txtnewpass1');
		$txtnewpass2 = $this->input->post('txtnewpass2');
		if($pass == $txtrecentpass){ //current password match
			$this->load->library('form_validation');//CI LIBRARY : FROM VALIDATION
			$this->form_validation->set_rules('txtnewpass1','Password Baru','required');
			$this->form_validation->set_rules('txtnewpass2','Ulangi Password Baru','required|matches[txtnewpass1]');
			if($this->form_validation->run()){ //SESUAI RULES
				$this->m_siswa->updatepassword($txtnewpass1);//QUERY UPDATE PASS
				echo ("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('Password Berhasil Diubah');
				window.location.href='".site_url('siswa/edit_profile')."';
				</SCRIPT>");
			} else { //TIDAK SESUAI RULES
				$data['title'] = 'Error Ubah Password | ';
				$this->defaultdisplay('siswa/editprofile', $data);
			}			
		}else{
			echo ("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('Password Lama Tidak Cocok');
				window.location.href='".site_url('siswa/edit_profile')."';
			</SCRIPT>");
		}
		
	}

}