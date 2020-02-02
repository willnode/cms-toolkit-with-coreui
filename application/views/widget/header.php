<!DOCTYPE HTML>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=TITLE?></title>
	<link rel="icon" href="<?=base_url('assets/logo.png')?>">
	<link rel="stylesheet" href="<?=base_url('vendors/font-awesome/css/font-awesome.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('vendors/coreui/css/style.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('vendors/coreui/icons/css/coreui-icons.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('vendors/bootstrap-table/bootstrap-table.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('assets/style.css')?>">
	<script src="<?=base_url('vendors/jquery/jquery.min.js')?>"></script>
	<script src="<?=base_url('vendors/bootstrap/bootstrap.min.js')?>"></script>
	<script src="<?=base_url('vendors/coreui/js/coreui.min.js')?>"></script>
	<script src="<?=base_url('vendors/bootstrap-table/bootstrap-table.min.js')?>"></script>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
	<header class="app-header navbar">
		<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="<?=base_url('')?>">
			<img class="navbar-brand-full" src="<?=base_url('assets/logo.png')?>" height="30" alt="Logo">
			<img class="navbar-brand-minimized" src="<?=base_url('assets/logo.png')?>" height="30" alt="Logo">
		</a>
		<button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
			<span class="navbar-toggler-icon"></span>
		</button>
		<ul class="nav navbar-nav ml-auto mr-2">
			<li class="nav-item dropdown">
				<a class="nav-link nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button">
					<span class="text-muted text-truncate d-none d-sm-block" style="max-width: 200px"><?=$name?></span>
					<img class="img-avatar" src="<?=base_url($avatar ? "uploads/avatar/$avatar" : 'assets/user.png')?>" alt="">
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<div class="dropdown-header text-center">
						<strong>Account</strong>
					</div>
					<a class="dropdown-item" href="<?=base_url($role)?>">
						<i class="fa fa-home"></i> Dashboard</a>
					<a class="dropdown-item" href="<?=base_url($role.'/profile/')?>">
						<i class="fa fa-user"></i> Profile</a>
					<a class="dropdown-item" href="<?=base_url('logout')?>">
						<i class="fa fa-lock"></i> Logout</a>
				</div>
			</li>
		</ul>
	</header>

	<div class="app-body">
		<?php $this->load->view("$role/sidebar", ['username' => $username])?>
		<main class="main">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
				<?php foreach ($breadcrumb as $item) : ?>
				<?php if (is_array($item)) : ?>
				<li class="breadcrumb-item">
					<a href="<?=base_url($item[0])?>"><?=$item[1]?></a>
				</li>
				<?php else : ?>
				<li class="breadcrumb-item active"><?=$item?></li>
				<?php endif ?>
				<?php endforeach ?>
			</ol>
			<div class="container-fluid">
				<div class="animated fadeIn">

					<?php if (isset($error)) : ?>
					<div class="alert alert-danger" role="alert">
						<?= $error ?>
					</div>
					<?php endif ?>
					<?php if (isset($message)) : ?>
					<div class="alert alert-primary alert-dismissible fade show" role="alert">
						<?= $message ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<?php endif ?>
