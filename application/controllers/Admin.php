<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if ($this->session->role !== 'admin') {
			redirect('login');
		} else {
			$this->current_id = $this->session->id_admin;
		}
	}

	public function index()
	{
		$this->dashboard();
	}

	public function dashboard()
	{
		load_view('admin/dashboard', [
			'profile' => $this->db->get_where('admin', ['id_admin' => $this->current_id])->row(),
		]);
	}

	public function user($action='list', $id=0)
	{
		if ($action == 'list') {
			load_view('admin/list/user');
		} else if ($action == 'get') {
			echo json_encode(ajax_table_driver('user', [], ['name_user', 'email_user']));
		} else if ($action == 'create') {
			load_view('admin/edit/user', [
				'data' => (object)[
					'id_user' => 0,
					'name_user' => '',
					'email_user' => '',
					'username' => '',
					'password' => '',
				]
			]);
		} else if ($action == 'edit') {
			load_view('admin/edit/user', [
				'data' => $this->db->from('user,login')
					->where("user.id_login=login.id_login")
					->where(["user.id_user" => $id])
					->get()->row(),
			]);
		} else if ($action == 'delete') {
			$id_login = get_id_login('user', $id);
			$this->db->delete('user', ['id_user' => $id]);
			$this->db->delete('login', ['id_login' => $id_login]);
			redirect('admin/user/');
		} else if ($action == 'update') {
			if (run_validation([
				['name_user', 'Name', 'required'],
				['email_user', 'Email', 'required|valid_email'],
				['username', 'Username', 'required|min_length[3]'],
				['password', 'Password', $id == 0 ? 'required' : '']
			])) {
				$data = get_post_updates(['name_user', 'email_user']);
				$login = get_post_updates(['username', 'password'], ['role' => 'user']);
				if (isset($login['password']))
					$login['password'] = password_hash($login['password'], PASSWORD_BCRYPT);
				if ($id == 0) {
					$this->db->insert('login', $login);
					$id_login = $this->db->insert_id();
					$data['id_login'] = $id_login;
					$this->db->insert('user', $data);
				} else {
					$this->db->update('user', $data, ['id_user' => $id]);
					$this->db->update('login', $login, ['id_login' => get_id_login('user', $id)]);
				}
				redirect('admin/user/');
			} else {
				$this->user($id == 0 ? 'create' : 'edit', $id);
			}
		}
	}
}
