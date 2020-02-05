
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card-group">
		<div class="card p-4">
			<div class="card-body">
			<?= form_open() ?>
			<h1>Login</h1>
			<p class="text-muted">Sign In to your account</p>

			<?php if (isset($this->session->error)) : ?>
				<div class="alert alert-danger" role="alert">
				<?= $this->session->error ?>
			</div>
			<?php endif ?>
			<?php control_error('username') ?>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
				<span class="input-group-text">
					<i class="icon-user"></i>
				</span>
				</div>
				<input class="form-control" type="text" name="username" placeholder="Username">
			</div>
			<?php control_error('password') ?>
			<div class="input-group mb-4">
				<div class="input-group-prepend">
				<span class="input-group-text">
					<i class="icon-lock-locked"></i>
				</span>
				</div>
				<input class="form-control" type="password" name="password" placeholder="Password">
			</div>
			<div class="row">
				<div class="col-6">
				<input type="submit" value="Login" class="btn btn-primary px-4">
				<a href="<?=base_url('forgot/')?>" class="btn btn-link px-4">Forgot password?</a>
				</div>
			</div>
			</form>
			</div>
		</div>
		</div>
	</div>
</div>