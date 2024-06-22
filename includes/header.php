<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="icon" href="/images/favicon-modern.svg">

	<title><?php echo $title; ?></title>

	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="css/neverstyle/default.css?v=3" type="text/css" title="Neverstyle">
</head>
<body>
	<input type="checkbox" id="menu-toggle" class="hidden" autocomplete="off">

	<header>
		<div class="neverball-box" id="banner-wrapper">
			<a href="/">
				<h1 id="banner" class="neverball-text">Neverball</h1>
			</a>
		</div>

		<div class="menu-toggle">
			<label for="menu-toggle" class="neverball-button" style="padding: 10px;">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8 8" width="20" height="20">
					<path d="M 0 1 L 8 1 M 0 4 L 8 4 M 0 7 L 8 7" stroke="#fff" stroke-width="1.6" fill="#000000"/>
				</svg>
			</label>
		</div>

		<?php include("includes/nav.php"); ?>
	</header>