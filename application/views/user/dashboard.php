<div class="text-center">
	<p><img style="height:256px" src="<?=base_url($profile->avatar_user ? "uploads/avatar/$profile->avatar_user" : 'assets/logo.png')?>"></p>
	<h1>Welcome, <?= $profile->name_user ?></h1>
	<p class="text-muted"><?= $profile->email_user ?></p>
</div>