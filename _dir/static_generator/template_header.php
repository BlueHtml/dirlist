<?php
if(!defined('GEN_INIT'))exit();
?><!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $cdnpublic?>twitter-bootstrap/4.6.1/css/bootstrap.min.css">
    <link rel='stylesheet' href='<?php echo $static_path; ?>css/style.css?v=1003'>
</head>
<body>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white border-bottom" id="navbar">
	<div class="container big-nav">
		<a class="navbar-brand" href="<?php echo $root_path; ?>index.html">
		    <img src="<?php echo $static_path; ?>images/logo.png" width="180" height="40" class="d-inline-block align-top mr-2" alt="">
		</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
				    <a class="nav-link" href="<?php echo $root_path; ?>index.html">首页</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
