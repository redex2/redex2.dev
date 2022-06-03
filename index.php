<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf8">
		<link rel="stylesheet" href="index.css">
		<title>Redex2's projects</title>
		<link rel="icon" type="image/png" href="https://redex2.dev/icon.png">
	</head>
	<body>
		<div id="margin">
			<header>
				<a href="https://redex2.dev/"><img src="https://redex2.dev/icon.png"></a>
				<nav>
					<a href="https://account.redex2.dev/">LOGIN</a><br>
					<a href="https://account.redex2.dev/create">CREATE</a><br>
				</nav>
				<div id="header-center">
					<h1><a href="https://redex2.dev/">Redex2's projects</a></h1><br>
					<form method="get"><input name="search" type="search" id="search" value="<?php
					if(isset($_GET["search"])&&$_GET["search"]!="")echo $_GET["search"];
					?>" placeholder="type to search..."></form>
				</div>
			</header>
			<div id="content" <?php if(isset($_GET["search"])&&$_GET["search"]!="")echo 'style="display:none;"';?> >
				<main id="project-list" <?php if(isset($_GET["pid"])&&$_GET["pid"]!="")echo 'style="display:none;"';?>>
					<?php
					require_once(__DIR__."/../sql.php");
					$pdo=db_connect();
					if(!$pdo) error_print(0);
					try
					{
						$out = $pdo->query("SELECT id, title, lang, text, git, link FROM entry WHERE type = 'p' ORDER BY id");
					}
					catch(PDOException $e)
					{
						error_log('SQL error: ' . $e->getMessage());
						error_print(1);
					}
					function error_print($num)
					{
						echo '<article class="project">';
						echo '	<a href="">';
						echo '		<h2>Database error</h2>';
						echo '		<h3>:(</h3>';
						echo '		<p>I want to show you projects, but something went wrong<br>Code:'.$num.'</p>';
						echo '	</a>';
						echo '</article>';
						exit;
					}
					foreach($out as $entry)
					{
						echo '<article class="project">';
						echo '	<a href="/'.$entry["id"].'/'.strtolower(str_replace(' ', '_', $entry["title"])).'/">';
						echo '		<h2>'.$entry["title"].'</h2>';
						echo '		<h3>'.$entry["lang"].'</h3>';
						echo '		<p>'.$entry["text"].'</p>';
						echo '	</a>';
						if($entry["link"]!=""||$entry["git"]!="")
						{
							echo '<div class="link">';
							if($entry["link"]!="")echo '<a target="_blank" href="'.$entry["link"].'">LINK</a><br>';
							if($entry["git"]!="")echo '<a target="_blank" href="'.$entry["git"].'">GIT</a><br>';
							echo '</div>';
						}
						echo '</article>';
					}
					$out->closeCursor();
					?>
				</main>
				<main id="entry" <?php if(!isset($_GET["pid"])||$_GET["pid"]=="")echo 'style="display:none;"';?> >
					<?php if(isset($_GET["pid"])&&$_GET["pid"]!="") require_once("entry.php"); ?>
				</main>
				<aside>
					<?php
					if(!$pdo) error_print_blog(0);
					try
					{
						$out = $pdo->query("SELECT id, title, TO_CHAR(datetime, 'dd-mm-yyyy HH24:ii') AS datetime, text FROM entry WHERE type = 'b' ORDER BY id LIMIT 5");
					}
					catch(PDOException $e)
					{
						error_log('SQL error: ' . $e->getMessage());
						error_print_blog(1);
					}
					function error_print_blog($num)
					{
						echo '<article class="post">';
						echo '	<a href="">';
						echo '		<h2>Database error</h2>';
						echo '		<h3>:(</h3>';
						echo '		<p>I want to show you news, but something went wrong<br>Code:'.$num.'</p>';
						echo '	</a>';
						echo '</article>';
						exit;
					}
					foreach($out as $entry)
					{
						echo '<article class="post">';
						echo '	<a href="/'.$entry["id"].'/'.strtolower(str_replace(' ', '_', $entry["title"])).'/">';
						echo '		<h2>'.$entry["title"].'</h2>';
						echo '		<h3>'.$entry["datetime"].'</h3>';
						echo '		<p>'.$entry["text"].'</p>';
						echo '	</a>';
						echo '</article>';
					}
					$out->closeCursor();
					?>
				</aside>
			</div>
			<div id="search-result" <?php if(!isset($_GET["search"])||$_GET["search"]=="")echo 'style="display:none;"';?> >
				<main id="search-content">
					<?php if(isset($_GET["search"])&&$_GET["search"]!="") require_once("search_engine.php"); ?>
				</main>
			</div>
			<footer>
				GitHub: <a href="https://github.com/redex2">github.com/redex2</a><br>
				E-mail: <a href="mailto:redex2@redex2.dev">redex2@redex2.dev</a><br>
				PGP: <a href="https://pgp.redex2.dev">pgp.redex2.dev</a>
			</footer>
		</div>
		<script src="index.js"></script>
	</body>
</html>