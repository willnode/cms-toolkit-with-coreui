<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		// Front end static index
		$this->load->view('static/header');
		$this->load->view('static/home');
		$this->load->view('static/footer');
	}

	public function login($action = 'index')
	{
		$this->load->model('login_model', 'auth');
		if ($action == 'index') {
			if ($this->auth->authenticate()) {
				// User logged in using typical password
				redirect(issetor($this->input->get('redirect'), $this->session->role));
			} else {
				// Login page
				$this->load->view('static/header');
				$this->load->view('static/login');
				$this->load->view('static/footer');
			}
		} else if ($action == 'otp') {
			if ($this->auth->authenticate_with_token($this->input->get('token'))) {
				// User logged in using OTP hashed as password reset link
				redirect(issetor($this->input->get('redirect'), $this->session->role));
			} else {
				show_401();
			}
		}
	}

	public function forgot($action = 'show') {
		$this->load->model('login_model', 'auth');
		if ($action == 'show') {
			// Show "which account forgotten" form
			$this->load->view('static/header');
			$this->load->view('static/forgot');
			$this->load->view('static/footer');
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
				$this->load->view('static/header');
				$this->load->view('static/verify');
				$this->load->view('static/footer');
			} else {
				redirect('forgot/');
			}
		} else if ($action == 'otp') {
			// "Give me the OTP" handling
			if ($this->auth->authenticate_with_otp($this->input->post('pin'))) {
				redirect($this->session->role);
			} else {
				set_error("Wrong PIN");
				$this->forgot('verify');
			}
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
