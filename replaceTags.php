<?php
header("Content-Type:text/html; charset=utf-8");
$filePath = 'xuite_blog_export_2125846.txt';

$replace = array(
	'<p>' => '',
	'</p>' => "\n",
	'<br />' => "\n",
	'&nbsp;' => ' '
);

$file = fopen($filePath, "r");
$articles = array();
$i = 0;
$isBody = true;
while ( ! feof($file)) {
	$value = fgets($file);

	if (preg_match('/TITLE:.+\n/', $value)) {
		$i++;
		$isBody = true;
	} else if (preg_match('/COMMENT:\n*/', $value)) {
		$isBody = false;
	}

	$articles[$i][$isBody ? 'body' : 'comment'] .= $value;
}

$data = '';
foreach ($articles as $key => $article) {
	foreach ($replace as $search => $replacement) {
		$articles[$key]['comment'] = str_replace($search, $replacement, $articles[$key]['comment']);
	}
	$articles[$key]['comment'] = strip_tags($articles[$key]['comment']);
	$data .= $article['body'];
	$data .= $articles[$key]['comment'];
}
fclose($file);  

$file = fopen('replace_' . $filePath, "w");
fwrite($file, $data);
fclose($file);