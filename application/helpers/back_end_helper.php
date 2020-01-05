<?php

function issetor(&$var, $default = false) {
    return isset($var) ? $var : $default;
}

function run_validation($config = []) {
	$ci = &get_instance();
	$ci->load->library('form_validation');
	foreach ($config as $conf) {
		$ci->form_validation->set_rules($conf[0], $conf[1], $conf[2]);
	}
	return $ci->form_validation->run();
}

function get_post_updates($vars = [], $default = []) {
	$ci = &get_instance();
	$updates = $default;
	foreach ($vars as $var) {
		if ($ci->input->post($var)) {
			$updates[$var] = $ci->input->post($var);
		}
	}
	return $updates;
}

function control_file_delete($folder, $existing_value = '')
{
	$existing_file = "./uploads/$folder/$existing_value";
	if (is_file($existing_file)) {
		unlink($existing_file);
	}
}

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
		}
    } elseif ($ci->input->post($name.'_delete')) {
		$updates[$name] = '';
		control_file_delete($folder, $existing_value);
	}
}

function load_view($mainview, $data = []) {
	$ci = &get_instance();
	$ci->load->view('widget/header');
	$ci->load->view($mainview, $data);
	$ci->load->view('widget/footer');
}

function get_id_login($table, $id_in_table) {
	$ci = &get_instance();
	return $ci->db->get_where($table, ["id_$table" => $id_in_table])->row()->id_login;
}

function ajax_table_driver($table, $filter = [], $searchable_columns = []) {
	$ci = &get_instance();
	$cursor = $ci->db->from($table)->where($filter);
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
