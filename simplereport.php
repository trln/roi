<?php

	//Info for roi_user test account
	$dbhost = "localhost";
	$dbuser = "roi_user";
	$dbpass = "FlyingBottleStickyNote";
	$dbname = "roi";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() .
			" ( " . mysqli_connect_errno() . ")"
		);
	}
?>

<?php ini_set('display_errors', '1'); ?>
<!DOCTYPE HTML>

<html lang="en">
	<head>
	<title>Datatest</title>
	<meta charset="utf-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<form method="get" action="<?= $_SERVER['PHP_SELF']; ?>">
		  <p>
			<label for="startYear">Starting Year:</label>
			<select name="startYear" id="startYear">
				<option value="2010">2010</option>
				<option value="2011"
					<?php
						if($_GET['startYear'] == 2011) {
							echo "selected";
						}
					?>
					>2011</option>
				<option value="2012"
					<?php
						if($_GET['startYear'] == 2012) {
							echo "selected";
						}
					?>
					>2012</option>
				<option value="2013"
					<?php
						if($_GET['startYear'] == 2013) {
							echo "selected";
						}
					?>
					>2013</option>
				<option value="2014"
					<?php
						if($_GET['startYear'] == 2014) {
							echo "selected";
						}
					?>
					>2014</option>
				<option value="2015"
					<?php
						if($_GET['startYear'] == 2015) {
							echo "selected";
						}
					?>
					>2015</option>
				<option value="2016"
					<?php
						if($_GET['startYear'] == 2016) {
							echo "selected";
						}
					?>
					>2016</option>
				<option value="2017"
					<?php
						if($_GET['startYear'] == 2017) {
							echo "selected";
						}
					?>
					>2017</option>
			</select>
		  </p>
		  <p>
			<label for="endYear">Ending Year:</label>
			<select name="endYear" id="endYear">
				<option value="2010"
					<?php
						if($_GET['endYear'] == 2010) {
							echo "selected";
						}
					?>
					>2010</option>
				<option value="2011"
					<?php
						if($_GET['endYear'] == 2011) {
							echo "selected";
						}
					?>
					>2011</option>
				<option value="2012"
					<?php
						if($_GET['endYear'] == 2012) {
							echo "selected";
						}
					?>
					>2012</option>
				<option value="2013"
					<?php
						if($_GET['endYear'] == 2013) {
							echo "selected";
						}
					?>
					>2013</option>
				<option value="2014"
					<?php
						if($_GET['endYear'] == 2014) {
							echo "selected";
						}
					?>
					>2014</option>
				<option value="2015"
					<?php
						if($_GET['endYear'] == 2015) {
							echo "selected";
						}
					?>
					>2015</option>
				<option value="2016"
					<?php
						if($_GET['endYear'] == 2016) {
							echo "selected";
						}
					?>
					>2016</option>
				<option value="2017"
					<?php
						if(!$_GET || $_GET['endYear'] == 2017) {
							echo "selected";
						}
					?>
					>2017</option>
			</select>
		  </p>
		  <p>
			<label for="minCost">Minimum Cost:</label>
			<input type="text" name="minCost" id="minCost"
				<?php
					if($_GET) {						
						echo "value='{$_GET['minCost']}'";
					}
				?>
				>
		  </p>
		  <p>
			<label for="maxCost">Maximum Cost:</label>
			<input type="text" name="maxCost" id="maxCost"
				<?php
					if($_GET) {						
						echo "value='{$_GET['maxCost']}'";
					}
				?>
				>
		  </p>
		  <p>
			<label for="provider">Select Provider:</label>
			<select name="provider" id="provider">
				<option value="all">All providers</option>
				<option value="Springer-Verlag"
					<?php
						if($_GET['provider'] == 'Springer-Verlag') {
							echo "selected";
						}
					?>
					>Springer-Verlag</option>
				<option value="Wiley-Blackwell"
					<?php
						if($_GET['provider'] == 'Wiley-Blackwell') {
							echo "selected";
						}
					?>
					>Wiley-Blackwell</option>
			</select>
		  </p>
		  <p>
			<input type="submit" name="start" id="start" value="Go">
		  </p>
		</form>
	
		<?php 
			print_r($_GET);
			echo "</br>";
			//Base MySQL query string
			$query = "SELECT Sum(TRUNCATE(TRLN_Cost, 2)) AS 'Total TRLN Cost', TRLN_Year AS 'TRLN Year', Provs.SERSOL_ProvName 'Provider Name' FROM loc_trln_costs AS Costs
			JOIN loc_sersol_databases AS Dbs
				ON Costs.SERSOL_DBCode = Dbs.SERSOL_DBCode
			JOIN loc_sersol_providers As Provs
				ON Dbs.SERSOL_ProvID = Provs.SERSOL_ProvID";
			//Parameter input for query, select by year
			if($_GET['endYear'] - $_GET['startYear'] >= 0) {
				$query .= " WHERE TRLN_Year >= {$_GET['startYear']} AND TRLN_Year <= {$_GET['endYear']}";
				//Parameter input for query, select by provider
				if($_GET['provider'] != "all") {
					$query .= " AND Provs.SERSOL_ProvName = '{$_GET['provider']}'";
				}
				$query .= " GROUP BY TRLN_Year, Provs.SERSOL_ProvName";
				//Parameter input for query, select by cost
				if($_GET['minCost'] == '') {
					if(is_numeric($_GET['maxCost'])) {
						$query .= " HAVING Sum(TRLN_Cost) <= {$_GET['maxCost']}";
					} else if($_GET['maxCost'] != '') {
						die("Invalid cost input. Range invalid or non-number.");
					}
				} else if($_GET['maxCost'] == '') {
					if(is_numeric($_GET['minCost'])) {
						$query .= " HAVING Sum(TRLN_Cost) >= {$_GET['minCost']}";
					} else {
						die("Invalid cost input. Range invalid or non-number.");
					}
				} else if((is_numeric($_GET['maxCost']) && is_numeric($_GET['maxCost'])) && $_GET['maxCost'] - $_GET['minCost'] >= 0) {
					$query .= " HAVING Sum(TRLN_Cost) >= {$_GET['minCost']} AND Sum(TRLN_Cost) <= {$_GET['maxCost']}";
				} else {
					die("Invalid cost input. Range invalid or non-number.");
				}
			} else {
				die("Invalid year range");
			}
			$result = mysqli_query($connection, $query);
			if(!$result) {
				die("Database query failed");
			}
		?>
		<?php
			while($row = mysqli_fetch_assoc($result)) {
				echo "| " . $row["Total TRLN Cost"] . "\t| " 
				. $row["TRLN Year"] . "\t| "
				. $row["Provider Name"] . "\t|" . "</br>";
				echo "<hr>";
			}
		?>
		<?php 
			mysqli_free_result($result);
		?>
	</body>
</html>

<?php
	mysqli_close($connection);
?>