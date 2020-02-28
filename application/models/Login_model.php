<?php

class Login_model extends CI_Model {

	const TABLE = 'login';
	const ID = 'login_id';
	const USERNAMES = ['username', 'email'];
	const PASSWORD_HASH = 'password';
	const OTP = 'otp';


	public function get_data_authentication($username) {
		if (empty($username)) {
			return NULL;
		}
		$where = [];
		foreach (self::USERNAMES as $uname) {
			$where[] = "`$uname`=".$this->db->escape($username);
		}
		$login = $this->db->from(self::TABLE)->where(implode(' OR ', $where))->limit(1)->get()->result();
		return count($login) > 0 ? $login[0] : NULL;
	}


	/**
	 * Opinionated Login Authentication system (on function props of course)
	 */
	public function authenticate(
		$post_username = 'username', // POST param contain username
		$post_password = 'password' // POST param contain password
		) {

		if ($this->input->method() === 'post') {
			if (!run_validation([
				[$post_username, 'username', 'required'],
				[$post_password, 'password', 'required'],
			])) {
				return FALSE;
			} else {
				$username = $this->input->post($post_username);
				$password = $this->input->post($post_password);

				$user = $this->get_data_authentication($username);
				if (isset( $user ) && !empty($user->{self::PASSWORD_HASH}) && password_verify($password, $user->{self::PASSWORD_HASH})) {
					$this->do_real_sign_in($user);
					return TRUE;
				} else {
					set_error('Wrong username or password. Try again.');
					return FALSE;
				}
			}
		} else {
			$id = $this->session->userdata(self::ID);
			if (isset($id)) {
				$user = $this->db->get_where(self::TABLE, [self::ID => $id], 1)->row();
				$this->do_real_sign_in($user);
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	/**
	 * Opinionated Token Authentication link
	 */
	public function authenticate_with_token($token) {
		if (empty($token) OR !($token = base64_decode($token, TRUE)) OR count($token = explode(':', $token, 2)) !== 2) {
			return FALSE; // TOKEN empty, unparseable or not follow format
		}
		$username = $token[0];
		$otp_hash = $token[1];
		$user = $this->get_data_authentication($username);
		if (isset($user) AND password_verify($user->{self::OTP}, $otp_hash)) {
			$this->do_real_sign_in($user, TRUE);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Opinionated way to password forgot flow and send OTP keys
	 * (TODO: Minutes limitation should apply right? can be done with sessions or storage)
	 */
	public function authenticate_forgot_password($username) {
		$user = $this->get_data_authentication($username);
		if (isset($user)) {
			$this->generate_otp($user->{self::ID});
			$this->session->{self::OTP} = $username;
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Opinionated way to assume otp got expired?
	 */
	public function check_otp_relevant() {
		return !empty($this->session->{self::OTP});
	}

	/**
	 * Opinionated way to authenticate with OTP
	 */
	public function authenticate_with_otp($otp) {
		$user = $this->get_data_authentication($this->session->{self::OTP});
		if (isset($user) && $user->{self::OTP} === trim($otp)) {
			$this->do_real_sign_in($user, TRUE);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Actually grant sign in
	 */
	function do_real_sign_in(&$login_data, $via_token = FALSE) {
		if ($via_token) {
				// The current password is invalid (as they forgot)
				$this->clear_password($user->{self::ID});
				$login_data->{self::PASSWORD_HASH} = NULL;
		}
		foreach ($login_data as $var => $val) {
			$this->session->{$var} = $val;
		}
	}

	/**
	 * Generate new token or get one
	 */
	public function generate_otp($login_id, $force_regen = FALSE) {
		if ($force_regen OR empty ($exist = $this->db->get_where(self::TABLE, [self::ID => $login_id])->row()->{self::OTP})) {
			$otp = (version_compare(PHP_VERSION, '7.0.0') >= 0 ? 'random_int' : 'mt_rand')(111111, 999999);
			$this->db->update(self::TABLE, [self::OTP => $otp], [self::ID => $login_id]);
			return $otp;
		} else {
			return $exist;
		}
	}

	public function clear_otp($login_id) {
		$this->db->update(self::TABLE, [self::OTP => NULL], [self::ID => $login_id]);
	}


	public function clear_password($login_id) {
		$this->db->update(self::TABLE, [self::PASSWORD_HASH => NULL], [self::ID => $login_id]);
	}

}