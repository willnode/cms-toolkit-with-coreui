<?= form_open("admin/user/update/$data->id_user") ?>
<?php control_input(['name'=>'name_user', 'label'=>'Name', 'value'=>$data->name_user]) ?>
<?php control_input(['name'=>'email_user', 'label'=>'Email', 'value'=>$data->email_user]) ?>
<hr>
<?php control_input(['name'=>'username', 'label'=>'Username', 'value'=>$data->username]) ?>
<?php control_input(['name'=>'password', 'label'=>'Password', 'value'=>'', 'type'=>'password']) ?>
<?php control_submit() ?>
<?= form_close() ?>