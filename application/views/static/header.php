<!DOCTYPE HTML>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="theme-color" content="#EE4323">
	<meta name="description" content="CMS Toolkit with CoreUI">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="<?=base_url('assets/logo.png')?>">
	<link rel="manifest" href="<?=base_url('manifest.json')?>">
 	<link rel="stylesheet" href="<?=base_url('vendors/font-awesome/css/font-awesome.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('vendors/coreui/css/style.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('vendors/coreui/icons/css/coreui-icons.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('assets/style.css')?>">
	<script src="<?=base_url('vendors/jquery/jquery.min.js')?>"></script>
	<script src="<?=base_url('vendors/bootstrap/bootstrap.min.js')?>"></script>
	<script src="<?=base_url('vendors/coreui/js/coreui.min.js')?>"></script>
	<script src="<?=base_url('assets/script.js')?>"></script>
  <title><?=TITLE?></title>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed">
<header class="app-header navbar">
  <a class="navbar-brand" href="<?=base_url()?>">
    <img class="navbar-brand-full" src="<?=base_url('assets/logo.png')?>" height="30" alt="Logo">
    <img class="navbar-brand-minimized" src="<?=base_url('assets/logo.png')?>" height="30" alt="Logo">
  </a>
  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item px-3">
      <a class="nav-link" href="<?=base_url('login')?>">Login</a>
    </li>
  </ul>
</header>

<div class="app-body">
  <main class="main py-4">
    <div class="container-fluid">
      <div class="animated fadeIn">
