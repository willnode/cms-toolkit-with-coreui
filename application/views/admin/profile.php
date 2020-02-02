<?= form_open_multipart("$role/profile/update/") ?>
<?php control_input(['name'=>'name', 'label'=>'Name', 'value'=>$data->name]) ?>
<?php control_input(['name'=>'email', 'label'=>'Email', 'value'=>$data->email]) ?>
<?php control_image(['name'=>'avatar', 'label'=>'Avatar', 'folder'=>'avatar', 'value'=>$data->avatar]) ?>
<hr>
<?php control_input(['disabled'=>TRUE, 'label'=>'Username', 'value'=>$data->username]) ?>
<?php control_input(['name'=>'password', 'label'=>'Password', 'value'=>'', 'type'=>'password']) ?>
<?php control_input(['name'=>'passconf', 'label'=>'Password Confirmation', 'value'=>'', 'type'=>'password']) ?>
<?php control_submit() ?>
<?= form_close() ?>