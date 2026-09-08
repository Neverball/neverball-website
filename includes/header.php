<?php
global $current_route;

$canonical_query = http_build_query(array_intersect_key($_GET, array_flip(['id', 'set'])));
$canonical_link = BASE_URL . ($current_route ? '/' . $current_route : '') . ($canonical_query ? '?' . $canonical_query : '');

$meta_description = $description ?? "Neverball is a free, open-source 3D skill and puzzle game. Tilt the floor to roll through obstacle courses. Play in your browser or download for desktop.";
$meta_title = $title ?? "Neverball";
$meta_image = BASE_URL . "/images/anim.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">

	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo $canonical_link; ?>">
	<meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>">
	<meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
	<meta property="og:image" content="<?php echo $meta_image; ?>">

	<!-- Twitter -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:url" content="<?php echo $canonical_link; ?>">
	<meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title); ?>">
	<meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description); ?>">
	<meta name="twitter:image" content="<?php echo $meta_image; ?>">

	<link rel="icon" href="/images/favicon-modern.svg">
	<link rel="canonical" href="<?php echo $canonical_link; ?>">

	<title><?php echo $title; ?></title>

	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="/css/neverstyle/default.css?v=<?php echo filemtime(BASE_DIR . '/css/neverstyle/default.css'); ?>" type="text/css" title="Neverstyle">
</head>
<body>
	<input type="checkbox" id="menu-toggle" class="hidden" autocomplete="off">

	<header>
		<div class="neverball-box" id="banner-wrapper">
			<h1 id="banner">
				<a href="/" class="neverball-text">
					Neverball
				</a>
			</h1>
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