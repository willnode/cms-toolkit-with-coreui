<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if ($this->session->role !== 'user') {
			redirect('login');
		} else {
			$this->current_id = $this->session->id_user;
		}
	}

	public function index()
	{
		$this->dashboard();
	}

	public function dashboard()
	{
		load_view('user/dashboard', [
			'profile' => $this->db->get_where('user', ['id_user' => $this->current_id])->row(),
		]);
	}

	public function profile($action='edit')
	{
		if ($action == 'edit') {
			load_view('user/profile', [
				'data' => $this->db->from('user,login')
				->where("user.id_login=login.id_login")
				->where(["user.id_user" => $this->current_id])
				->get()->row(),
			]);
		} else if ($action == 'update') {
			if (run_validation([
				['name_user', 'Name', 'required'],
				['email_user', 'Email', 'required|valid_email'],
				['password', 'Password', $this->input->post('password') ? 'required' : ''],
				['passconf', 'Password Confirmation', $this->input->post('password') ? 'matches[password]' : '']
			])) {
				$data = get_post_updates(['name_user', 'email_user']);
				control_file_upload($data, 'avatar_user', 'avatar',
					$this->db->get_where('user', ['id_user' => $this->current_id])->row()->avatar_user,
					'jpg|jpeg|png|bmp');
				$login = get_post_updates(['password']);
				$this->db->update('user', $data, ['id_user' => $this->current_id]);
				if (isset($login['password'])) {
					$login['password'] = password_hash($login['password'], PASSWORD_BCRYPT);
					$this->db->update('login', $login, ['id_login' => get_id_login('user', $this->current_id)]);
				}
				redirect('user/profile/');
			} else {
				$this->profile();
			}
		}
	}

}
