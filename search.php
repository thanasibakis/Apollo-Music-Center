<?php include_once "setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/section.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/theme.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php include "header.php"; ?>
		<section>
			<h3>Results for "<?php echo $_GET["name"]; ?>"</h3>
			<?php
				$rows = sql("select id from items where name like '%" . $_GET["name"] . "%'");
				foreach($rows as $row)
				{
					$id = $row["id"];
					$item = new Item($id);
					create_data_vars($item);
					include "item_small.php";
				}
				
				if(count($rows) == 0)
				{
					echo "No results.";
				}
			?>
		</section>
	</body>
</html>