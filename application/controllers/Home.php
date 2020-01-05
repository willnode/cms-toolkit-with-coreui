<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$this->load->view('static/header');
		$this->load->view('static/home');
		$this->load->view('static/footer');
	}

	public function login()
	{
		if (isset($this->session->role)) {
			redirect($this->session->role);
		}

		if ($this->input->method() == 'post') {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$login = $this->db->get_where('login', ['username' => $username]);
			$result = $login->result();
			if (count( $result ) > 0 && password_verify($password, $result[0]->password)) {
				$user = $result[0];
				$this->session->username = $user->username;
				$this->session->role = $user->role;
				$this->session->{"id_$user->role"} =
					$this->db->get_where($user->role,
					['id_login'=>$user->id_login])
					->row()->{"id_$user->role"};
				redirect($user->role);
			} else {
				redirect("login");
			}
		} else {
			$this->load->view('static/header');
			$this->load->view('static/login');
			$this->load->view('static/footer');
		}
	}

	public function logout()
	{
		session_destroy();
		redirect("login");
	}

	public function hash($password)
	{
		echo password_hash($password, PASSWORD_DEFAULT);
	}
}
