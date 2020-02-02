<?= form_open("admin/user/update/$data->login_id", [], $data->login_id === 0 ? ['otp_invoke'=>'y'] : []) ?>
<?php control_input(['name'=>'username', 'label'=>'Username', 'value'=>$data->username]) ?>
<?php control_input(['name'=>'name', 'label'=>'Name', 'value'=>$data->name]) ?>
<?php control_input(['name'=>'email', 'label'=>'Email', 'value'=>$data->email]) ?>
<?php control_submit() ?>
<?php if($data->login_id) {
	?>
	<hr>
	<h2>OTP</h2>
	<p>You can use OTP to send PIN or Private Link to let user who have the right to access this account logging in</p>
	<?php
	control_div(['label'=>'OTP Pin', 'value'=>$data->otp]);
	control_div(['label'=>'', 'class'=>'form-control h-auto', 'value' => function() use ($data) {
	if (!$data->otp) {
		control_buttons([['name'=>'otp_invoke', 'icon'=>'fa fa-key', 'label'=>'Create PIN', 'style'=>'btn btn-outline-primary']]);
	} else {
		control_buttons([
			['type'=>'copy', 'icon'=>'fa fa-key', 'label'=>'Copy PIN', 'style'=>'btn btn-outline-primary', 'value'=>$data->otp],
			['type'=>'copy', 'icon'=>'fa fa-link', 'label'=>'Copy Token Link', 'style'=>'btn btn-outline-success',
				'value'=>base_url('login/otp?token='.urlencode(base64_encode($data->username.':'.password_hash($data->otp, PASSWORD_BCRYPT))))],
			['name'=>'otp_revoke', 'icon'=>'fa fa-trash', 'label'=>'Revoke OTP', 'style'=>'btn btn-outline-danger', 'confirm'=>'Are you sure?']]);
	}
}], TRUE); } ?>
<?= form_close() ?>
