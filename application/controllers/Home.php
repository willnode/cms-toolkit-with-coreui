<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	protected function view($view, $data = []) {
		$this->load->view('static/header');
		$this->load->view("static/$view", $data);
		$this->load->view('static/footer');
		$this->session->unset_userdata(['message', 'error']);
	}

	protected function redirect_to_panel() {
		redirect(issetor($this->input->get('redirect'), $this->session->role));
	}

	public function index()
	{
		// Front end static index
		$this->view('home');
	}

	public function login($action = 'index')
	{
		$this->load->model('login_model', 'auth');
		if ($action == 'index') {
			if ($this->auth->authenticate()) {
				// User logged in using typical password
				$this->redirect_to_panel();
			} else {
				// Login page
				$this->view('login');
			}
		} else if ($action == 'otp') {
			if ($this->auth->authenticate_with_token($this->input->get('token'))) {
				// User logged in using OTP hashed as password reset link
				$this->redirect_to_panel();
			} else {
				show_401();
			}
		}
	}

	public function forgot($action = 'show') {
		$this->load->model('login_model', 'auth');
		if ($action == 'show') {
			// Show "which account forgotten" form
			$this->view('forgot');
		} else if ($action == 'send') {
			// "which account forgotten" handling
			if ($this->auth->authenticate_forgot_password($this->input->post('username'))) {
				redirect('forgot/verify');
			} else {
				set_error("Can't find user account");
				$this->forgot();
			}
		} else if ($action == 'verify') {
			if ($this->auth->check_otp_relevant()) {
				// Show "Give me the OTP" form
				$this->view('verify');
			} else {
				redirect('forgot/');
			}
		} else if ($action == 'otp') {
			// "Give me the OTP" handling
			if ($this->auth->authenticate_with_otp($this->input->post('pin'))) {
				$this->redirect_to_panel();
			} else {
				set_error("Wrong PIN");
				$this->forgot('verify');
			}
		}
	}

	public function logout()
	{
		// Clear sessions
		session_destroy();
		redirect("login");
	}

	public function hash($password)
	{
		// Built-in hash helper
		echo password_hash($password, PASSWORD_DEFAULT);
	}
}
