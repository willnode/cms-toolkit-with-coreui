<div class="text-center">
	<p><img style="height:256px" alt="Avatar" src="<?=base_url($profile->avatar ? "uploads/avatar/$profile->avatar" : 'assets/user.png')?>"></p>
	<h1>Welcome, <?= $profile->name ?></h1>
	<p class="text-muted"><?= $profile->email ?></p>
	<?php control_buttons([[
		'href' => base_url('user/profile/'),
		'label' => 'Manage Profile',
		'icon' => 'fa fa-user mr-1',
	]]) ?>
</div>