<?php
$title = "Neverball - Contributors";
include("includes/header.php");
?>
</head>
<body>
	<div id="wrapper">
	<div id="banner"><h1>Neverball</h1></div>

<?php include("includes/nav.php"); ?>

	<div id="main">
		<div id="contents">
		<h2>Neverball Contributions</h2>
		<dl class="add-colons"><?php
		if((file_exists("includes/authors.php") && !file_exists("AUTHORS")) || (file_exists("includes/authors.php") && file_exists("AUTHORS") && (filemtime("AUTHORS") == filemtime("includes/authors.php")))){
			include("includes/authors.php");
		}else if(!file_exists("includes/authors.php") && !file_exists("AUTHORS")){
			echo "\n\t\t<dd>Failed to read AUTHORS data!</dd>\n";
		}else{
			function file_get_contents_utf8($fn){
			        $content = file_get_contents($fn);
			        return mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
			}
			function regex_add_parens($addTo){
			        $addTo[0] = preg_replace("/\nA:\s/", " (", $addTo[0]);
					$addTo[0] = preg_replace("/\n/", "", $addTo[0]);
			        return $addTo[0].")\n";
			}
			function regex_add_close_dt($addTo){
			        $addTo[0] = preg_replace("/\n/", "", $addTo[0]);
			        return $addTo[0]."</dt>\n";
			}
			function regex_add_close_li($addTo){
			        $addTo[0] =  preg_replace("/\n/", "", $addTo[0]);
			        return $addTo[0]."</li>\n";
			}
			function regex_add_parens_and_format($addTo){
			        $addTo[0] = preg_replace("/A:\s/", "\t\t\t</ul></dd>\n\t\t\t<dt>", $addTo[0]);
					$addTo[0] = preg_replace("/\n/", "", $addTo[0]);
			        return $addTo[0]."</dt>\n\t\t\t<dd><ul>\n";
			}
			$authorsFile = file_get_contents_utf8("AUTHORS");
			$authorsFile = preg_replace("/(#.*\n|E:.*\n|P:.*\n)/", "", $authorsFile); //Remove comments, emails and PGP keys
			$authorsFile = preg_replace("/\n*\n/", "\n", $authorsFile); //Remove un-necessary newlines
			$authorsFile = preg_replace("/N:\s/", "\t\t\t<dt>", $authorsFile); //Add <dt> tags to beginning of names
			$authorsFile = preg_replace_callback("/<dt>.*\nA:\s.*\n/", "regex_add_parens", $authorsFile); //Add () around forum handles
			$authorsFile = preg_replace_callback("/<dt>.*\n/", "regex_add_close_dt", $authorsFile); //Add ending </dt> tag to end of names
			$authorsFile = preg_replace("/(W: |D: )/", "\t\t\t\t<li>", $authorsFile); //Add <li> tags to beginning of websites and descriptions
			$authorsFile = preg_replace_callback("/<li>.*\n/", "regex_add_close_li", $authorsFile); //Add <li> tags to end of emails, websites and descriptions
			$authorsFile = preg_replace("/<\/dt>\n\t\t\t\t<li>/", "</dt>\n\t\t\t<dd><ul>\n\t\t\t\t<li>", $authorsFile); //Insert <dd> and <ul> tags
			$authorsFile = preg_replace("/<\/li>\n\t\t\t<dt>/", "</li>\n\t\t\t</ul></dd>\n\t\t\t<dt>", $authorsFile); //Insert </ul> and </dd> tags
			$authorsFile = preg_replace_callback("/A:\s.*\n/", "regex_add_parens_and_format", $authorsFile); //Format authors forum names without proper real names
			$authorsFile = $authorsFile."\n\t\t\t</ul></dd>\n";
			$authorsFileWrite = "<?php\nif(basename(\$_SERVER[\"SCRIPT_NAME\"]) == \"authors.php\"){\n\tdie();\n}\n?>\n".$authorsFile;
			$fh = fopen('includes/authors.php', 'w') or die("Failed to open authors.php for writing!");
			fwrite($fh, $authorsFileWrite);
			fclose($fh);
			chmod("includes/authors.php", 0666);
			touch("includes/authors.php", filemtime("AUTHORS"));
			echo $authorsFile;
		}?>
		</dl>
		<br>
		<h2>Website Contributions</h2>
		<dl class="add-colons">
			<dt>Ben Anderson</dt>
			<dd><ul>
				<li>Website content, maintenance and php programming</li>
				<li>Various images</li>
			</ul></dd>
			<dt>Josh Bush</dt>
			<dd><ul>
				<li>CSS layout and related images</li>
			</ul></dd>
			<dt>Pasi Kallinen</dt>
			<dd><ul>
				<li>Website parsing code for CHANGES file</li>
			</ul></dd>
			<dt>Jānis Rūcis</dt>
			<dd><ul>
				<li>Content layout</li>
				<li>Various random stuff</li>
			</ul></dd>
			<dt><a href="http://tango.freedesktop.org/">Tango Desktop Project</a></dt>
			<dd><ul>
				<li>Various icons</li>
			</ul></dd>
		</dl>
		</div>
<?php
include("includes/footer.php");
?>
