<?= form_open_multipart("$role/profile/update/") ?>
<?php control_input(['name'=>'name', 'label'=>'Name', 'value'=>$data->name, 'required'=>true]) ?>
<?php control_input(['name'=>'email', 'label'=>'Email', 'value'=>$data->email, 'type'=>'email', 'required'=>true]) ?>
<?php control_image(['name'=>'avatar', 'label'=>'Avatar', 'folder'=>'avatar', 'value'=>$data->avatar, 'accept'=>".gif,.jpg,.jpeg,.png,.bmp"]) ?>
<hr>
<?php control_input(['disabled'=>TRUE, 'label'=>'Username', 'value'=>$data->username]) ?>
<?php control_input(['name'=>'password', 'label'=>'Password', 'value'=>'', 'type'=>'password', 'autocomplete'=>'new-password']) ?>
<?php control_input(['name'=>'passconf', 'label'=>'Password Confirmation', 'value'=>'', 'type'=>'password']) ?>
<?php control_submit() ?>
<?= form_close() ?>