<?php
	/*
		TRLN ROI Database Reporting Project, Simple Database Report
		Author: Joseph Leonardi
	*/
	try {
		require_once "includes/dbtools.php";
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
	<title>TRLN ROI Simple Report</title>
	<meta charset="utf-8">
	<link href="mystyles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<h1><img src="includes/trln.png" width=254 height=106 alt="TRLN"> ROI Simple Report by Joseph Leonardi</h1>
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
			<label for="minCost">Minimum TRLN Cost:</label>
			<input type="text" name="minCost" id="minCost"
				<?php
					if($_GET) {						
						echo "value='{$_GET['minCost']}'";
					}
				?>
				>
		  </p>
		  <p>
			<label for="maxCost">Maximum TRLN Cost:</label>
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
			<label for="sort">Group Results By:</label>
			<select name="sort" id="sort">
				<?php
					$sortby = ["Consortium", "Library"];
					create_menu($sortby, 'sort', "Consortium");
				?>
			</select>
		  </p>
		  <p>
			<input type="submit" name="start" id="start" value="Go">
		  </p>
		</form>
	
		<?php 
		if($_GET) {
			
			$startYear = check($_GET['startYear'], true, $db);
			$endYear = check($_GET['endYear'], true, $db);
			$minCost = check($_GET['minCost'], true, $db);
			$maxCost = check($_GET['maxCost'], true, $db);
			$provider = check($_GET['provider'], false, $db);
			$sort = check($_GET['sort'], false, $db);
			if($sort != "Consortium" && $sort != "Library") {
				die("Invalid group-by input. (Stop being sneaky and use the menu!)");
			}
			//echo($startYear . " " . $endYear . " " . $minCost . " " . $maxCost . " " . $provider . " " . $sort . "</br>");
			
			//Base MySQL query string
			$query = "SELECT * FROM totalsummary$sort";
			//Parameter input for query, select by year
			if($endYear - $startYear >= 0) {
				$query .= " WHERE TRLN_Year >= {$startYear} AND TRLN_Year <= {$endYear}";
				//Parameter input for query, select by provider
				if($provider != "All providers") {
					$query .= " AND SERSOL_ProvName = '{$provider}'";
				}
				//Parameter input for query, select by cost
				if($minCost == '' && $maxCost == '') {
					//do nothing
				} else if($minCost == '' && $maxCost != '') {
					$query .= " HAVING Total_TRLN_Cost <= {$maxCost}";
				} else if($minCost != '' && $maxCost == '') {
					$query .= " HAVING Total_TRLN_Cost >= {$minCost}";
				} else if($maxCost - $minCost >= 0) {
					$query .= " HAVING Total_TRLN_Cost >= {$minCost} AND Total_TRLN_Cost <= {$maxCost}";
				} else {
					die("Invalid cost range.");
				}
				$query .= " ORDER BY TRLN_Year DESC";
			} else {
				die("Invalid year range");
			}
			try {
				//print_r($query);
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
				<?php
					if(!$_GET) {
						
						die("Make a selection, then hit 'Go' to display the data");
					}
					if($sort == "Library") {
						echo "<th>TRLN_LibraryName</th>";
					}
				?>
				<th>TRLN_Year</th>
				<th>Total_TRLN_Cost</th>
				<th>Total_PUB_Price</th>
				<th>Discount_Total_<?php echo $sort; ?></th>
				<th>Discount_Percentage_<?php echo $sort; ?></th>
				<th>CostPerUse_<?php echo $sort; ?></th>
				<th>CostPerTitle_<?php echo $sort; ?></th>
				<th>UsePerTitle_<?php echo $sort; ?></th>
				<th>SERSOL_ProvName</th>
			</tr>
			<?php if($_GET) { while($row = $result->fetch_assoc()) { ?>
				<tr>
					<?php if($sort == "Library") {echo "<td>" . $row["TRLN_LibraryName"] . "</td>";} ?>
					<td><?php echo $row["TRLN_Year"]; ?></td>
					<td><?php echo $row["Total_TRLN_Cost"]; ?></td>
					<td><?php echo $row["Total_PUB_Price"]; ?></td>
					<td><?php echo $row["Discount_Total_$sort"]; ?></td>
					<td><?php echo $row["Discount_Percentage_$sort"]; ?></td>
					<td><?php echo $row["CostPerUse_$sort"]; ?></td>
					<td><?php echo $row["CostPerTitle_$sort"]; ?></td>
					<td><?php echo $row["UsePerTitle_$sort"]; ?></td>
					<td><?php echo $row["SERSOL_ProvName"]; ?></td>
				</tr>
			<?php } } ?>
		</table>
	</body>
</html>

<?php
	$db->close();
?>