<?php

require_once("Parsedown.php");

/*
 * Parse AUTHORS.txt
 */
function cache_parse_authors($orig)
{
	$content = "";
	$lines = file($orig, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$state = 0;

	foreach ($lines as $line) {
		if (preg_match('/^N: ([^\(]+)/', $line, $matches) === 1) {
			if ($state === 1) {
				$content .= "</ul></dd>\n";
				$state = 0;
			}
			if ($state === 0) {
				$content .= "<dt>" . htmlspecialchars(trim($matches[1])) . "</dt>\n<dd><ul>\n";
				$state = 1;
			}
		} else if (preg_match('/^D: (.*)$/', $line, $matches) === 1) {
			if ($state === 1) {
				$content .= "<li>" . htmlspecialchars(trim($matches[1])) . "</li>\n";
			}
		}
	}

	if ($state === 1)
		$content .= "</ul></dd>\n";

	return $content;
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