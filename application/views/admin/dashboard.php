<div class="text-center">
	<p><img style="height:256px" src="<?=base_url($profile->avatar ? "uploads/avatar/$profile->avatar" : 'assets/user.png')?>"></p>
	<h1>Welcome, <?= $profile->name ?></h1>
	<p class="text-muted"><?= $profile->email ?></p>
</div>