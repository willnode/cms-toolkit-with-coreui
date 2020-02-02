<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'Basic.php';

class Admin extends CI_Basic_Role_Controller {

	const ROLE = 'admin';

	public function user($action='manage', $id=0)
	{
		if ($action == 'manage') {
			$this->view('user/manage');
		} else if ($action == 'get') {
			load_json(ajax_table_driver('login', ['role'=>'user'], ['username', 'name', 'email']));
		} else if ($action == 'create') {
			$this->view('user/edit', [
				'data' => (object)[
					'login_id' => 0,
					'username' => '',
					'email' => '',
					'password' => '',
					'name' => '',
					'avatar' => '',
					'otp' => '',
				]
			]);
		} else if ($action == 'edit') {
			$this->view('user/edit', [
				'data' => $this->db->get_where('login', ['login_id' => $id])->row(),
			]);
		} else if ($action == 'delete') {
			if ($this->db->delete('login', ['login_id' => $id])) {
				set_message('Deleted successfully');
				redirect('admin/user/');
			} else {
				show_401();
			}
		} else if ($action == 'update') {
			if (run_validation([
				['name', 'Name', 'required|alpha_numeric_spaces'],
				['email', 'Email', 'required|valid_email'],
				['username', 'Username', 'required|min_length[3]|alpha_numeric'],
			])) {
				$data = get_post_updates(['name', 'email', 'username']);
				if(!insert_or_update('login', $data, $id)) {
					$this->user($id == 0 ? 'create' : 'edit', $id);
				} else {
					$otps = get_post_updates(['otp_invoke', 'otp_revoke']);
					if (empty($otps)) {
						set_message('Saved successfully');
						redirect('admin/user/');
					} else {
						$this->load->model('login_model', 'auth');
						if (isset($otps['otp_invoke'])) $this->auth->generate_otp($id);
						if (isset($otps['otp_revoke'])) $this->auth->clear_otp($id);
						redirect('admin/user/edit/'.$id);
					}
				}
			} else {
				$this->user($id == 0 ? 'create' : 'edit', $id);
			}
		}
	}
}
