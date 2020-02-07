<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Basic wrapper for ALL login roles shared functionality
 */
class CI_Basic_Role_Controller extends CI_Controller {

	/**
	 * To be overridden, role name applied
	 */
	const ROLE = '';

	/**
	 * At construct, do additional check if user logged in (and using proper role)
	 */
	public function __construct() {
		parent::__construct();
		if (check_role(static::ROLE)) {
			$this->current_id = $this->session->login_id;
		}
	}

	/**
	 * Basic functionality: if we are new, force to edit it's own
	 * profile first, or just show dashboard for returning users.
	 */
	public function index()
	{
		if ($this->session->password) {
			$this->dashboard();
		} else {
			// If logged in via PIN, it's mean it's password are obsolete, so need to reinput their new password
			set_message('Welcome. You\'ve logged in using PIN/Token link, so you\'re required to set your new password here.');
			redirect(static::ROLE."/profile/edit");
		}
	}

	/**
	 * load_view alias but appending ROLE to path dan data
	 *
	 */
	protected function view($view, $data = []) {
		$view = static::ROLE.'/'.$view;
		$data['role'] = static::ROLE;
		load_view($view, $data);
	}

	/**
	 * Basic dashboard
	 */
	public function dashboard()
	{
		$this->view('dashboard', [
			'profile' => $this->db->get_where('login', ['login_id' => $this->current_id])->row(),
		]);
	}

	/**
	 * Profile Backend Logic
	 */
	public function profile($action='edit')
	{
		if ($action == 'edit') {
			$this->view('profile', [
				'data' => $this->db->get_where('login', ["login_id" => $this->current_id])->row(),
			]);
		} else if ($action == 'update') {
			$require_password = $this->input->post('password') || empty($this->session->password);
			if (run_validation([
				['name', 'Name', 'required'],
				['email', 'Email', 'required|valid_email'],
				['password', 'Password', $require_password ? 'required' : ''],
				['passconf', 'Password Confirmation', $require_password ? 'matches[password]' : '']
			])) {
				$data = get_post_updates(['name', 'email', 'password']);
				if (control_file_upload($data, 'avatar', 'avatar', get_values_at('login', $this->current_id), 'jpg|jpeg|png|bmp')) {
					if(control_password_update($data)) {
						$data['otp'] = NULL;
					}
					$this->db->update('login', $data, ['login_id' => $this->current_id]);
					set_message('Saved successfully');
					redirect('login/');
				}
			}
			// Failed updates goes here.
			$this->profile();
		} else {
			show_404();
		}
	}


}