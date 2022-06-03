<?php
function entry_404()
{
	header("HTTP/1.1 404 Not Found");
	header( "refresh:20;url=/" );
	echo '<article class="entry">';
	echo '<h2>404 - page not found</h2>';
	echo '<h3>:(</h3>';
	echo '<p><a href="/">Click to redirect to main page</a></p>';
	echo '</article>';
	exit;
}

if(isset($_GET["pid"]))
{
	$in = strtolower(str_replace(' ', '_', $_GET["pid"]));
	if(preg_match("/^\/[0-9]+\/([a-zA-Z0-9_]+\/?)?$/", $in)!==1)entry_404();
	if(preg_match("/^\/[0-9]+\//", $in, $matches)!==1)entry_404();
	if(preg_match("/[0-9]+/", $matches[0], $matches)!==1)entry_404();
	$matches=$matches[0];
	require_once(__DIR__."/../sql.php");
	$pdo=db_connect();
	if(!$pdo) entry_404();
	try
	{
		$out = $pdo->query("SELECT *,TO_CHAR(datetime, 'dd-mm-yyyy HH24:ii') AS datetime FROM entry WHERE id = $matches");
	}
	catch(PDOException $e)
	{
		error_log('SQL error: ' . $e->getMessage());
		error_print_se();
	}
	foreach($out as $line)
	{
		if($line["type"]=="p")
		{
			echo '<article class="entry">';
			echo '<h2>'.$line["title"].'</h2>';
			echo '<h3>'.$line["lang"].'</h3>';
			echo '<p>'.$line["text"].'</p>';
			echo '</article>';
		}
		else if($line["type"]=="b")
		{
			echo '<article class="entry">';
			echo '<h2>'.$line["title"].'</h2>';
			echo '<h3>'.$line["datetime"].'</h3>';
			echo '<p>'.$line["text"].'</p>';
			echo '</article>';
		}
	}
}
?>