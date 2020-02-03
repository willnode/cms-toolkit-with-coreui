<?php

/**
 * http://cwestblog.com/2015/11/06/php-problem-with-issetor/
 */
function issetor(&$var, $default = false) {
    return isset($var) ? $var : $default;
}

/**
 * CodeIgniter Form validation for short, dirty, quick config
 */
function run_validation($config = []) {
	$ci = &get_instance();
	$ci->load->library('form_validation');
	foreach ($config as $conf) {
		$ci->form_validation->set_rules($conf[0], $conf[1], $conf[2]);
	}
	return $ci->form_validation->run();
}

/**
 * Quick way to get POST values in assosicate array
 */
function get_post_updates($vars = [], $default = []) {
	$ci = &get_instance();
	$updates = $default;
	foreach ($vars as $var) {
		if ($val = $ci->input->post($var)) {
			$updates[$var] = $val;
		}
	}
	return $updates;
}

/**
 * Handle file removal easily
 */
function control_file_delete($folder, $existing_value = '')
{
	$existing_file = "./uploads/$folder/$existing_value";
	if (is_file($existing_file)) {
		unlink($existing_file);
	}
}

/**
 * Handle file upload on POST, and also delete existing file in previous data (so no orphan files)
 */
function control_file_upload(&$updates, $name, $folder, $existing_value = '', $types = '*')
{
	$ci = &get_instance();
    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
        if (!is_dir("./uploads/$folder/")) {
            mkdir("./uploads/$folder/", 0777, true);
        }
        $ci->upload->initialize([
            'upload_path' => "./uploads/$folder/",
            'allowed_types' => $types
        ]);
        if ($ci->upload->do_upload($name)) {
			$updates[$name] = $ci->upload->file_name;
			control_file_delete($folder, $existing_value);
			return TRUE;
		} else {
			set_error($ci->upload->display_errors('', '<br>'));
			return FALSE;
		}
    } elseif ($ci->input->post($name.'_delete')) {
		$updates[$name] = '';
		control_file_delete($folder, $existing_value);
		return TRUE;
	}
	return TRUE;
}

/**
 * Modify POST data in assoc array to hash the PASSWORD field
 */
function control_password_update(&$updates, $field = 'password') {
	if (!empty($updates[$field])) {
		$updates['password'] = password_hash($updates['password'], PASSWORD_BCRYPT);
		return TRUE;
	}
	return FALSE;
}

/**
 * Show error message to front-end
 */
function set_error($str) {
	if (!empty($str)) {
		get_instance()->session->set_flashdata('error', $str);
		return TRUE;
	}
	return FALSE;
}

/**
 * Show info message to front-end
 */
function set_message($str) {
	if (!empty($str)) {
		get_instance()->session->set_flashdata('message', $str);
		return TRUE;
	}
	return FALSE;
}

/**
 * Allow custom DB error handling
 */
function catch_db_error() {
	get_instance()->db->db_debug = FALSE;
	error_reporting(0);
}

/**
 * Check if last DB query throws some error
 */
function check_db_error() {
	return set_error(get_instance()->db->error()['message']);
}

/**
 * Update or insert depending on ID, and update that's id to LAST_INSERT_ID
 * or Show the error if it fails
 */
function insert_or_update($table, &$data, &$id, $id_column = NULL) {
	catch_db_error();
	if ($id == 0) { // ID 0 means we do insert
		get_instance()->db->insert($table, $data);
		if (check_db_error()) {
			return FALSE;
		}
		$id = get_instance()->db->insert_id();
	} else {
		$id_column = $id_column ?: $table."_id";
		get_instance()->db->limit(1)->update($table, $data, [$id_column => $id]);
		if (check_db_error()) {
			return FALSE;
		}
	}
	return TRUE;
}

/**
 * Check loggen-on user role are match, or show login page or 401 error
 */
function check_role($role) {
	$ci = &get_instance();
	if ($ci->session->role === $role) {
		return TRUE;
	} elseif ($ci->session->login_id === NULL) {
		redirect_to_login();
	} else {
		redirect($ci->session->role);
	}
}

/**
 * Like show_404, but for 401
 */
function show_401() {
	header("HTTP/1.1 401 Unauthorized");
	exit;
}

/**
 * Return JSON of PHP data
 */
function load_json($data) {
	header('Content-Type: application/json');
	echo json_encode($data);
}

/**
 * Return HTML view based on PHP data
 */
function load_view($mainview, $data = []) {
	$ci = &get_instance();
	if (isset($_GET['debug']) && ENVIRONMENT !== 'production') {
		load_json($data);
	} else {
		{
			$partial = ''; // The breadcrumb is autogenerated via view path
			foreach (explode('/', $mainview) as $index => $value) {
				$partial .= $value.'/';
				if ($partial === $mainview.'/') {
					$breadcrumb[] = ucfirst($value);
				} else {
					$breadcrumb[] = [$partial, ucfirst($value)];
				}
			}
			$headdata['breadcrumb'] = $breadcrumb;
		}
		$ci->load->view('widget/header', array_merge((array)($_SESSION), $headdata));
		$ci->load->view($mainview, $data);
		$ci->load->view('widget/footer');
		$ci->session->unset_userdata(['message', 'error']);
	}
}

/**
 * For inherited table profile, we need to get ID login from ID of specific user role
 */
function get_id_login($table, $id_in_table) {
	$ci = &get_instance();
	return $ci->db->get_where($table, [$table."_id" => $id_in_table])->row()->login_id;
}


/**
 * The Generic Database Model that's fully compatible with Bootstrap-Table AJAX
 */
function ajax_table_driver($table, $filter = [], $searchable_columns = [], $select = '*') {
	$ci = &get_instance();
	$cursor = $ci->db->select($select)->from($table)->where($filter);
	$totalNotFiltered = $cursor->count_all_results('', FALSE);
	$search = $ci->input->get('search');
	$limit = $ci->input->get('limit');
	$offset = $ci->input->get('offset');
	if ($search && count($searchable_columns) > 0) {
		$cursor->group_start();
		foreach ($searchable_columns as $col) {
			$cursor->or_like($col, $search);
		}
		$cursor->group_end();
		$cursor->offset($ci->input->get('offset'));
		$total = $cursor->count_all_results('', FALSE);
	} else {
		$total = $totalNotFiltered;
	}

	if ($limit) $cursor->limit($limit);
	if ($offset) $cursor->offset($offset);

	return [
		'total' => $total,
		'totalNotFiltered' => $totalNotFiltered,
		'rows' => $cursor->get()->result()
	];
}

/**
 * Redirect to login page with back redirect on current page after logged on
 */
function redirect_to_login() {
	redirect('login?redirect='.urlencode(current_url()));
}

