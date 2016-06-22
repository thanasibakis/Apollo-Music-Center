<?php
	include_once "include/setup.php";
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php $name = htmlentities($_GET["name"]); ?>
		<?php include "include/header.php"; ?>
		<section>
			<h3>Results for "<?php echo $name; ?>"</h3>
			<div class="centered">
				<?php
					$rows = sql_procedure("GetIDbyName", array($name), 's');
					foreach($rows as $row)
					{
						$id = $row["id"];
						$item = new Item($id);
						create_data_vars($item);
						include "include/item_small.php";
					}
				?>
			</div>
			<?php if(count($rows) == 0) { echo "No results."; } ?>
		</section>
	</body>
</html>