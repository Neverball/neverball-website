<?php

require_once("Parsedown.php");

/*
 * Parse AUTHORS.txt
 */
function cache_parse_authors($orig)
{
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
	$authorsFile = file_get_contents_utf8($orig);
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

	return $authorsFile;
}

/*
 * Parse CHANGES.txt
 */
function cache_parse_changes($orig)
{
	$cnf = file($orig);
	$ret = "\n";
	$state = 0;

	foreach($cnf as $line){
		if(preg_match('/^-+$/', $line)){
			if($state == 2){
				$ret .= "</li>\n\t\t";
			}
			if($state == 1 || $state == 2){
				$ret .= "</ul>\n";
			}
			$state = 0;
		}else if(preg_match('/^(New in .+)$/', $line, $matches) && ($state == 0)){
			$ret .= "\t\t<h3>".htmlentities($matches[1])."</h3>\n\t\t";
			$ret .= "<ul>\n\t\t";
			$state = 1;
		}else if(preg_match('/^\* ([^ ].+)$/', $line, $matches) && ($state == 1 || $state == 2)){
			if($state == 2){
				$ret .= "</li>\n\t\t";
			}
			$ret .= "\t<li>".htmlentities($matches[1]);
			$state = 2;
		}else if(preg_match('/^  ([^ ].+)$/', $line, $matches) && ($state == 2)){
			$ret .= " ".htmlentities($matches[1]);
			$state = 2;
		}
	}
	if($state == 2){
		$ret .= "</li>\n\t\t";
	}
	if($state == 1 || $state == 2){
		$ret .= "</ul>\n\t\t";
	}
	return $ret;
}

function cache_parse_markdown($orig)
{
	$Parsedown = new Parsedown();

	$content = file_get_contents($orig);
	$content = $Parsedown->text($content);

	// Now we have two problems.

	$content = preg_replace('*<(/?)h5>*', '<\1h6>', $content);
	$content = preg_replace('*<(/?)h4>*', '<\1h5>', $content);
	$content = preg_replace('*<(/?)h3>*', '<\1h4>', $content);
	$content = preg_replace('*<(/?)h2>*', '<\1h3>', $content);
	$content = preg_replace('*<(/?)h1>*', '<\1h2>', $content);

	return $content;

}

function cache_parse($orig)
{
	if (strcasecmp(basename($orig, '.txt'), 'authors') == 0)
		return cache_parse_authors($orig);
	else if (strcasecmp(basename($orig, '.txt'), 'changes') == 0)
		return cache_parse_changes($orig);
	else if (strcmp(pathinfo($orig, PATHINFO_EXTENSION), 'md') == 0)
		return cache_parse_markdown($orig);
	return "\n";
}

/*
 * Confirm that the cache file is usable.
 */
function cache_valid($orig, $cache)
{
	return (file_exists($cache) && (!file_exists($orig) || filemtime($orig) == filemtime($cache)));
}

/*
 * Parse and cache a file. Return the cache file path or false on error.
 */
function cache_file($orig, $cache)
{
	if (cache_valid($orig, $cache))
		return $cache;

	if (!file_exists($orig))
		return false;

	$content = cache_parse($orig);

	$fh = fopen($cache, 'w');

	if ($fh !== false) {
		fwrite($fh, $content);
		fclose($fh);
		chmod($cache, 0666);
		touch($cache, filemtime($orig));
		return $cache;
	}

	return false;
}
?>