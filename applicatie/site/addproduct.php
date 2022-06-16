<!DOCTYPE html>
<html class="h-100 w-100" lang="en">

<head>
	<?php
	// hier wordt de head aangemaakt dit is een vaste structuur voor elke pagina.
	require_once '../app/applicatiefuncties.php';
	echo getHead();
	?>
	<!-- Plaats hier eventuele extra stylesheets, scripts -->
	<link href="css/addproductcss/addproduct.css" rel="stylesheet">
	<script src="js/addproduct.js"></script>
</head>


<body class="h-100 w-100" onload="resetSelection(), makeRubrics()">
	<header>
		<?php
		require_once '../app/applicatiefuncties.php';
		redirectBlocked();

		debugMessages('off');
		echo navbar();
		if (isset($_GET['error'])) {
			echo errorType($_GET['error']);
		}
		?>
		<script>
			<?php
			$php_array = getRubrics();
			$js_array = json_encode($php_array);
			echo "var rubricArray = " . $js_array . ";\n";
			?>
		</script>
	</header>
	<!-- Begin content van de page -->

	<div class="addproductbody">
		<div class="container h-100 px-1 mx-auto d-flex flex-column">
			<div class="row justify-content-center h-100">
				<div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center h-100 card">
					<h5 class="text-center mb-4">Plaats een product</h5>
					<form class="form-card" action="../app/addproduct.php" method="post" enctype="multipart/form-data">
						<div class="container-fluid h-100 d-flex flex-column">
							<div class="row justify-content-between text-left">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3">
										Titel*
									</label>
									<input type="text" placeholder="Titel" maxlength="150" value="<?php echo isset($_SESSION['title']) ? $_SESSION['title'] : '' ?>" name='title' required>
								</div>
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3 ">
										Beschrijving*
									</label>
									<textarea class="description" placeholder="Beschrijving" maxlength="8000" name='description' required></textarea>
								</div>
							</div>
							<div class="row justify-content-between text-left">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3">
										Verzendinstructies
									</label>
									<textarea class="instructions" placeholder="Verzendinstructies" maxlength="100"  name='shippinginstructions'></textarea>
								</div>
							</div>
							<div class="row justify-content-between mt-5 text-left">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3">
										Startprijs in euro's*
									</label>
									<input type="number" value="<?php echo isset($_SESSION['startingprice']) ? $_SESSION['startingprice'] : '' ?>" name="startingprice" min="0.0" max="9999999.99" step="0.05" placeholder="€" required>
								</div>
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3">
										Looptijd*
									</label>
									<select name="timelisted">
										<option value="1">1 dag</option>
										<option value="3">3 dagen</option>
										<option value="5">5 dagen</option>
										<option value="7">7 dagen</option>
										<option value="10">10 dagen</option>

									</select>
								</div>
							</div>
							<div class="row mt-5 text-left">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3">
										Verzendkosten*
									</label>
									<input type="number" value="<?php echo isset($_SESSION['shippingcosts']) ? $_SESSION['shippingcosts'] : '' ?>" name="shippingcosts" min="0.0" max="999999.99" step="0.05" placeholder="€" required>
								</div>
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3">
										Betalingswijze*
									</label>
									<select name="paymentmethod">
										<option value="IDEAL">iDeal</option>
										<option value="Creditcard">Creditcard</option>
										<option value="Cash">Contant</option>
									</select>
								</div>
							</div>
							<div class="row mt-5 text-left">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-group col-sm-12 flex-column d-flex">Land*
										<select class="form-select form-select-lg sm-1" aria-label=".form-select-lg example" id="country" name="country">
											<?=
											registerDropdown('country');
											?>
										</select>
									</label>
								</div>
								<div class="form-group col-sm-6 flex-column d-flex ">
									<label class="form-control-label px-3">
										Plaatsnaam*
									</label>
									<input type="text" id="city" value="<?php echo isset($_SESSION['city']) ? $_SESSION['city'] : '' ?>" name="city" maxlength="30" placeholder="Plaatsnaam" onblur="validate(5)" required>
								</div>
							</div>
							<div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-3 mt-5 text-left">
								<div class="form-group col flex-column d-flex">
									<select id="category1" onchange="makeSubmenu(this.value, 1)" name="category1">


									</select>
								</div>
								<div class="form-group col flex-column d-flex" id="categoryDiv2">
									<select id="category2" onchange="makeSubmenu(this.value, 2)" name="category2">

									</select>
								</div>
								<div class="form-group col flex-column d-flex" id="categoryDiv3">
									<select id="category3" onchange="makeSubmenu(this.value, 3)" name="category3">

									</select>
								</div>
								<div class="form-group col flex-column d-flex" id="categoryDiv4">
									<select id="category4" onchange="makeSubmenu(this.value, 4)" name="category4">
									</select>
								</div>
								<div class="form-group col flex-column d-flex" id="categoryDiv5">
									<select id="category5" onchange="makeSubmenu(this.value, 5)" name="category5">

									</select>
								</div>
							</div>
							<div class="col-sm-1">
							</div>
							<div class="row justify-content-center mt-5 text-left">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-control-label px-3" data-toggle="tooltip" title="Tot maximaal 4 plaatjes">
										Upload plaatje*
									</label>
									<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
									<input class="bg-secondary" type="file" accept="image/*" name="image[]" id="formFile" onchange="preview()" multiple="multiple" required />
								</div>
							</div>
							<div class="row justify-content-center mt-5 text-left">
								<div class="form-group col-sm-6 flex-column d-flex">

									<div id="carouselExampleIndicators" class="carousel slide carousel-dark" data-bs-ride="carousel">

										<div id="indicator" class="carousel-indicators">

										</div>

										<div id="img-container" class="carousel-inner">

										</div>

										<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
											<span class="carousel-control-prev-icon" aria-hidden="true"></span>
											<span class="visually-hidden">Previous</span>
										</button>
										<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
											<span class="carousel-control-next-icon" aria-hidden="true"></span>
											<span class="visually-hidden">Next</span>
										</button>

									</div>
								</div>
							</div>
							<div class="row justify-content-center mt-5 text-left">
								<div class="form-group col-sm-6 flex-column d-flex align-items-center">
									<button type="submit" name="submit" class="btn btn-secondary">Plaats product</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Einde content van de page-->

	<?=
	footer();
	?>

	<script>
		<?php
		$php_array = getRubrics();
		$js_array = json_encode($php_array);
		echo "var rubricArray = " . $js_array . ";\n";
		?>

		$(function() {
			$('[data-toggle="tooltip"]').tooltip()
		})
	</script>

</body>

</html>