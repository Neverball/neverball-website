<?php
$title = "Neverball - FAQ";
include("includes/header.php");
?>
</head>
<body>
	<div id="wrapper">
	<div id="banner"><h1>Neverball</h1></div>
	<div id="navigation">
	<ul id="primary">
		<li><a href="index.php">Home</a></li>
		<li><a href="screenshots.php">Screenshots</a>
		<li><a href="download.php">Download</a></li>
		<li><span>FAQ</span></li>
		<li><a href="changes.php">Changes</a></li>
		<li><a href="contributors.php">Contributors</a></li>
	</ul>
	</div>
	<div id="main">
		<div id="contents">
		<h2>FAQ</h2>
		<dl>
			<dt><q>My computer does not meet the stated minimum system requirements, can I play Neverball anyway?</q></dt>
				<dd><p>The biggest performance killer is reflection. Turn that off in the Options screen. This feature requires a stencil buffer, which is poorly supported on some hardware. So in the event that the game doesn't even run, set <code>reflection 0</code> in the neverballrc file.</p>
				<p>Next, disable Shadow in the Options screen or set <code>shadow 0</code> in the neverballrc file.</p>
				<p>If your video board has less than 16MB of VRAM, set Textures to Low in the Options, or <code>textures 2</code> in the neverballrc. This will eliminate texture thrashing.</p>
				<p>You can also try setting Geometry to Low. It does reduce the onscreen polygon count somewhat, but not a lot. It's more of a placebo option.</p></dd>
			<dt><q>I prefer the old camera settings.</q></dt>
				<dd><p>As documented in the README file, the camera may be returned to its pre-1.2.6 configuration by editing the following values in your neverballrc file:</p>
				<pre><code>view_fov 50
view_dp  400
view_dc  0
view_dz  600</code></pre></dd>
			<dt><q>The game takes control of my mouse. How can I make it let go so I can use another window?</q></dt>
				<dd><p>Press the spacebar to pause the game and toggle the pointer grab.</p></dd>
			<dt><q>Why don't you add an option to zoom the camera in and out?</q></dt>
				<dd><p>This is, without a doubt, the single most common feature suggestion suggested. The short answer is: "Because Super Monkey Ball doesn't have that feature."</p>
				<p>The long answer recognizes the fact that the design of Neverball is not motivated by precise conformance to Monkey Ball. The truth is that the camera zoom has a profound effect on gameplay.
				   It's much easier from far away. Allowing the player to zoom the camera removes the immersion that a 3rd person perspective provides, but that an overhead view does not. It undermines the latitude that a level designer has in shaping the feel and challenge of a level. In total, it changes the style of the game.</p></dd>
			<dt><q>How do I set the mouse sensitivity?</q></dt>
				<dd><p>Mouse sensitivity is set using the <code>mouse_sense</code> option in the neverballrc file. The default value is 300. This gives the number of screen pixels the mouse pointer must move to rotate the floor through its entire range. A smaller number means more sensitive.</p>
				<p>One word of caution: new players often feel that the mouse is too sensitive. It may seem so for early levels, but it can be very different for later levels.</p></dd>
			<dt><q>I see a bug! When the ball goes below a platform you can see the shadow ABOVE the ball!</q></dt>
				<dd><p>The developers know this. The shadow code is easier this way. Don't think of it as a shadow, think of it as a reference point that tells you where a bouncing ball will land.</p></dd>
			<dt><q>Are there plans for a multi-player mode?</q></dt>
				<dd><p>We're not planning to implement a multi-player mode at this time, as it causes some incoherence problems: 2 players controlling the same floor?</p></dd>
			<dt><q>I would like to create my own maps, where do I start?</q></dt>
				<dd><p>Have a look at the <a href="http://www.nevercorner.net/wiki/doku.php?id=level_creation" onClick="javascript: pageTracker._trackPageview('/outgoing/neverwiki');">level creation page</a> of the wiki. You should start with the <a href="http://www.nevercorner.net/wiki/doku.php?id=level_creation_howto" onClick="javascript: pageTracker._trackPageview('/outgoing/neverwiki');">Level creation HowTo</a>.</p></dd>
			<dt><q>What about adding mini-games just like in Super Monkey Ball?</q></dt>
				<dd><p>This is not our intent for the Neverball development (<a href="http://www.nevercorner.net/forum/viewtopic.php?id=80">related forum thread</a>).</p></dd>
		</dl>
		</div>
<?php
include("includes/footer.php");
?>
