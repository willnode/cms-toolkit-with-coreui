<?= form_open_multipart("user/profile/update/") ?>
<?php control_input(['name'=>'name_user', 'label'=>'Name', 'value'=>$data->name_user]) ?>
<?php control_input(['name'=>'email_user', 'label'=>'Email', 'value'=>$data->email_user]) ?>
<?php control_image(['name'=>'avatar_user', 'label'=>'Avatar', 'folder'=>'avatar', 'value'=>$data->avatar_user]) ?>
<hr>
<?php control_input(['disabled'=>'y', 'label'=>'Username', 'value'=>$data->username]) ?>
<?php control_input(['name'=>'password', 'label'=>'Password', 'value'=>'', 'type'=>'password']) ?>
<?php control_input(['name'=>'passconf', 'label'=>'Password Confirmation', 'value'=>'', 'type'=>'password']) ?>
<?php control_submit() ?>
<?= form_close() ?>