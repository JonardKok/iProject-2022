<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	require_once '../app/applicatiefuncties.php';
	echo getHead();
	debugMessages('off');
	generateSafetyCode($_SESSION['user'], 'beheerder');
	?>
	<link rel="stylesheet" href="css/usermanagementcss/usermanagement.css">
</head>

<body onload="makeRubrics()">
	<header>
		<?php
		echo navbar();
		if (isset($_GET['error'])) {
			echo errorType($_GET['error']);
		}
		if (isset($_GET['succes'])) {
			echo succesType($_GET['succes']);
		}
		?>
		<script>
			<?php
			$phpArray = getRubrics();
			$jsArray = json_encode($phpArray);
			echo "var rubricArray = " . $jsArray . ";\n";
			?>
		</script>
	</header>
	<div class="container my-5">
		<div class="row justify-content-center">
			<h2>Gebruikersbeheer</h2>
			<div class="card w-75 ">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">Naam</th>
							<th scope="col">E-mail</th>
							<th scope="col">Verkoper</th>
							<th scope="col">Status</th>
							<th scope="col">
								<form class="d-flex" action="../app/searchuser.php" method="post">
									<input class="search-input" type="text" name="searchuser" placeholder="Search...">
									<button class="btn btn-light search-btn" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</form>
							</th>
						</tr>
					</thead>
					<tbody>
						<?= getUsersHTML($_GET['searchuser']) ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?= footer() ?>
</body>

</html>