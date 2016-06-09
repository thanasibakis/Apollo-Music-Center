<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/index.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include 'html_include/header.html' ?>
		<section>
			<h3>Featured</h3>
			<div class="item_scroll">
				<table>
					<tr>
						<td><?php include 'html_include/item_small.html' ?></td>
						<td><?php include 'html_include/item_small.html' ?></td>
						<td><?php include 'html_include/item_small.html' ?></td>
						<td><?php include 'html_include/item_small.html' ?></td>
						<td><?php include 'html_include/item_small.html' ?></td>
					</tr>
				</table>
			</div>
		</section>
		<section>
			<h3>Recently Viewed</h3>
			<div class="item_scroll">
				<table>
					<tr>
						<td><?php include 'html_include/item_small.html' ?></td>
						<td><?php include 'html_include/item_small.html' ?></td>
					</tr>
				</table>
			</div>
		</section>
	</body>
</html>