<?php

	try {
		require_once "../includes/dbtools.php";
	} catch(Exception $e) {
		$error = $e->getMessage();
	}
	
	if(isset($error)) {
		echo "<p>$error</p>";
	}
?>

<?php 
	ini_set('display_errors', 'On'); 
	error_reporting(E_ALL);
?>
<!DOCTYPE HTML>

<html lang="en">
	<head>
	<title>Datatest</title>
	<meta charset="utf-8">
	<link href="mystyles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<form method="get" action="<?= $_SERVER['PHP_SELF']; ?>">
		  <p>
			<label for="startYear">Starting Year:</label>
			<select name="startYear" id="startYear">
				<?php
					$years = [2010, 2011, 2012, 2013, 2014, 2015, 2016];
					create_menu($years, 'startYear', 2010);			
				?>
			</select>
		  </p>
		  <p>
			<label for="endYear">Ending Year:</label>
			<select name="endYear" id="endYear">
				<?php
					create_menu($years, 'endYear', 2016);
				?>
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
				<?php
					$providers = ["All providers", "Springer-Verlag", "Wiley-BlackWell"];
					create_menu($providers, 'provider', "All providers");
				?>
			</select>
		  </p>
		  <p>
			<input type="submit" name="start" id="start" value="Go">
		  </p>
		</form>
	
		<?php 
		if($_GET) {
			//Base MySQL query string
			$query = "SELECT * FROM totalsummaryconsortium";
			//Parameter input for query, select by year
			if($_GET['endYear'] - $_GET['startYear'] >= 0) {
				$query .= " WHERE TRLN_Year >= {$_GET['startYear']} AND TRLN_Year <= {$_GET['endYear']}";
				//Parameter input for query, select by provider
				if($_GET['provider'] != "All providers") {
					$query .= " AND SERSOL_ProvName = '{$_GET['provider']}'";
				}
				//Parameter input for query, select by cost
				if($_GET['minCost'] == '') {
					if(is_numeric($_GET['maxCost'])) {
						$query .= " HAVING Total_TRLN_Cost <= {$_GET['maxCost']}";
					} else if($_GET['maxCost'] != '') {
						die("Invalid cost input. Range invalid or non-number.");
					}
				} else if($_GET['maxCost'] == '') {
					if(is_numeric($_GET['minCost'])) {
						$query .= " HAVING Total_TRLN_Cost >= {$_GET['minCost']}";
					} else {
						die("Invalid cost input. Range invalid or non-number.");
					}
				} else if((is_numeric($_GET['maxCost']) && is_numeric($_GET['minCost'])) && $_GET['maxCost'] - $_GET['minCost'] >= 0) {
					$query .= " HAVING Total_TRLN_Cost >= {$_GET['minCost']} AND Total_TRLN_Cost <= {$_GET['maxCost']}";
				} else {
					die("Invalid cost input. Range invalid or non-number.");
				}
				$query .= " ORDER BY TRLN_Year DESC";
			} else {
				die("Invalid year range");
			}
			try {
				$result = $db->query($query);
				if(!$result) {
					die("Database query failed");
				}
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
			if(isset($error)) {
				die("Database query failed");
			}	
		}
		?>
		<table>
			<tr>
				<th>TRLN_Year</th>
				<th>Total_TRLN_Cost</th>
				<th>Total_PUB_Price</th>
				<th>Discount_Total_Consortium</th>
				<th>Discount_Percentage_Consortium</th>
				<th>CostPerUse_Consortium</th>
				<th>CostPerTitle_Consortium</th>
				<th>UsePerTitle_Consortium</th>
				<th>SERSOL_ProvName</th>
			</tr>
			<?php if($_GET) { while($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row["TRLN_Year"]; ?></td>
					<td><?php echo $row["Total_TRLN_Cost"]; ?></td>
					<td><?php echo $row["Total_PUB_Price"]; ?></td>
					<td><?php echo $row["Discount_Total_Consortium"]; ?></td>
					<td><?php echo $row["Discount_Percentage_Consortium"]; ?></td>
					<td><?php echo $row["CostPerUse_Consortium"]; ?></td>
					<td><?php echo $row["CostPerTitle_Consortium"]; ?></td>
					<td><?php echo $row["UsePerTitle_Consortium"]; ?></td>
					<td><?php echo $row["SERSOL_ProvName"]; ?></td>
				</tr>
			<?php } } ?>
		</table>
	</body>
</html>

<?php
	$db->close();
?>