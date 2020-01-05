<?php $role = $this->session->userdata('role')?>
<?php $username = $this->session->userdata('username')?>
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
  <ul class="nav navbar-nav d-md-down-none ml-auto">
    <li class="nav-item px-3">
      <a class="nav-link" href="<?=base_url($role)?>">Dashboard</a>
    </li>
    <li class="nav-item px-3">
      <a class="nav-link" href="<?=base_url('logout')?>">Logout</a>
    </li>
  </ul>
</header>

<div class="app-body">
  <?php $this->load->view("$role/sidebar", ['username' => $username])?>
  <main class="main py-4">
    <div class="container-fluid">
      <div class="animated fadeIn">