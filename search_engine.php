<?php
if(isset($_GET["search"]))
{
	$in = strtolower(str_replace(' ', '_', $_GET["search"]));
	if(preg_match("/^[a-z0-9_]+$/", $in)!==1)
	{
		echo '<article class="post">';
		echo '	<a>';
		echo '		<h2>Search issue</h2>';
		echo '		<h3>:(</h3>';
		echo '		<p>I can use olny letters, numbers and space!</p>';
		echo '	</a>';
		echo '</article>';
		return;
	}
	require_once(__DIR__."/../sql.php");
	$pdo=db_connect();
	if(!$pdo) {error_print_se();return;}
	try
	{
		$out = $pdo->query("SELECT *,TO_CHAR(datetime, 'dd-mm-yyyy HH24:ii') AS datetime FROM entry WHERE REPLACE(LOWER(title),' ','_') LIKE '%$in%'");
	}
	catch(PDOException $e)
	{
		error_log('SQL error: ' . $e->getMessage());
		error_print_se();
		return;
	}
	function error_print_se()
	{
		echo '<article class="post">';
		echo '	<a>';
		echo '		<h2>Database error</h2>';
		echo '		<h3>:(</h3>';
		echo '		<p>I have a problem</p>';
		echo '	</a>';
		echo '</article>';
	}
	$points=[];
	$data=[];
	foreach($out as $line)
	{
		$points[$line["id"]]=substr_count($line["title"], $in)*100+substr_count($line["lang"], $in)*20+substr_count($line["text"], $in)*5;
		if($line["type"]=="b")$points[$line["id"]]=$points[$line["id"]]/2;//projects have more points
		$data[$line["id"]]=$line;
	}
	arsort($points);
	if(count($points)==0)
	{
		echo '<article class="post">';
		echo '	<a>';
		echo '		<h2>No results</h2>';
		echo '		<h3>:(</h3>';
		echo '		<p>I can not find it</p>';
		echo '	</a>';
		echo '</article>';
	}
	else
	{
		foreach ($points as $id => $p)
		{
			if($data[$id]["type"]=="p")
			{
				echo '<article class="project">';
				echo '<a href="/'.$data[$id]["id"].'/'.strtolower(str_replace(' ', '_', $data[$id]["title"])).'/">';
				echo '<h2>'.$data[$id]["title"].'</h2>';
				echo '<h3>'.$data[$id]["lang"].'</h3>';
				echo '<p>'.$data[$id]["text"].'</p>';
				echo '</a>';
				if($data[$id]["link"]!=""||$data[$id]["git"]!="")
				{
					echo '<div class="link">';
					if($data[$id]["link"]!="")echo '<a target="_blank" href="'.$data[$id]["link"].'">LINK</a><br>';
					if($data[$id]["git"]!="")echo '<a target="_blank" href="'.$data[$id]["git"].'">GIT</a><br>';
					echo '</div>';
				}
				echo '</article>';
			}
			else if($data[$id]["type"]=="b")
			{
				echo '<article class="post">';
				echo '<a href="/'.$data[$id]["id"].'/'.strtolower(str_replace(' ', '_', $data[$id]["title"])).'/">';
				echo '<h2>'.$data[$id]["title"].'</h2>';
				echo '<h3>'.$data[$id]["datetime"].'</h3>';
				echo '<p>'.$data[$id]["text"].'</p>';
				echo '</a>';
				echo '</article>';
			}
		}
	}
}
?>