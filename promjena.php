<?php include_once '../konfiguracija.php';








?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once 'includes/head.php'; ?>
  </head>
  <body>
    <div class="grid-container">
    	<!-- <?php include_once 'includes/zaglavlje.php'; ?> -->
      	<?php include_once 'includes/izbornik.php'; ?>
      	<a href="index.php"><i style="color: red;" class="fas fa-chevron-circle-left fa-2x"></i></a>
      	<div class="grid-x grid-padding-x">
			<div class="large-4 large-offset-4 cell centered">
				<form class="log-in-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
				  <h4 class="text-center">Unesite podatke za novi smjer</h4>

				  <?php if(!isset($greska["naziv"])): ?>
				  <label>Naziv
				    <input  type="text" id="naziv" name="naziv" placeholder="Programiranje"
				    value="<?php echo isset($_POST["naziv"]) ? $_POST["naziv"] : ""; ?>">
				  </label>
				  <?php else: ?>
				   <label class="is-invalid-label">
				    Naziv
				    <input type="text"  id="naziv" name="naziv" class="is-invalid-input"  aria-invalid aria-describedby="uuid"
				    value="<?php echo isset($_POST["naziv"]) ? $_POST["naziv"] : ""; ?>" >
				    <span class="form-error is-visible" id="uuid"><?php echo $greska["naziv"]; ?></span>
				  </label>
				  <?php endif; ?>



				  <?php if(!isset($greska["cijena"])): ?>
				  <label>Cijena
				    <input type="number" step="0.01" id="cijena" name="cijena" placeholder="5800"
				    value="<?php echo isset($_POST["cijena"]) ? $_POST["cijena"] : ""; ?>">
				  </label>
				  <?php else: ?>
				  <label class="is-invalid-label">
				    Cijena
				    <input type="number" step="0.01" id="cijena" name="cijena" class="is-invalid-input"  aria-invalid aria-describedby="uuid"
				    value="<?php echo isset($_POST["cijena"]) ? $_POST["cijena"] : ""; ?>" >
				    <span class="form-error is-visible" id="uuid"><?php echo $greska["cijena"]; ?></span>
				  </label>
				  <?php endif; ?>


				  <?php if(!isset($greska["upisnina"])): ?>
				  <label>Upisnina
				    <input type="number" step="0.01" id="upisnina" name="upisnina" placeholder="5800"
				    value="<?php echo isset($_POST["upisnina"]) ? $_POST["upisnina"] : ""; ?>">
				  </label>
				  <?php else: ?>
				  <label class="is-invalid-label">
				    Upisnina
				    <input type="number" step="0.01" id="upisnina" name="upisnina" class="is-invalid-input"  aria-invalid aria-describedby="uuid"
				    value="<?php echo isset($_POST["upisnina"]) ? $_POST["upisnina"] : ""; ?>" >
				    <span class="form-error is-visible" id="uuid"><?php echo $greska["upisnina"]; ?></span>
				  </label>
				  <?php endif; ?>



				  <?php if(!isset($greska["brojsati"])): ?>
				  <label>Broj sati
				    <input type="number" id="brojsati" name="brojsati" placeholder="100"
				    value="<?php echo isset($_POST["brojsati"]) ? $_POST["brojsati"] : ""; ?>">
				  </label>
				  <?php else: ?>
				  <label class="is-invalid-label">
				    Broj sati
				    <input type="number" id="brojsati" name="brojsati" class="is-invalid-input"  aria-invalid aria-describedby="uuid"
				    value="<?php echo isset($_POST["brojsati"]) ? $_POST["brojsati"] : ""; ?>" >
				    <span class="form-error is-visible" id="uuid"><?php echo $greska["brojsati"]; ?></span>
				  </label>
				  <?php endif; ?>



				  <input type="hidden" name="sifra" value="<?php echo $_POST["sifra"]; ?>"></input>
				  <p><input type="submit" class="button expanded" value="Promjeni smjer"></input></p>
				</form>

			</div>
		</div>
		<!-- <?php include_once '../../include/podnozje.php'; ?> -->


    </div>

    <?php include_once 'includes/scripts.php'; ?>
    <script>
    <?php if(isset($greska["naziv"])):?>
    		setTimeout(function(){ $("#naziv").focus(); },1000);
    <?php elseif(isset($greska["cijena"])):?>
	    		setTimeout(function(){ $("#cijena").focus(); },1000);
	<?php elseif(isset($greska["upisnina"])):?>
	    		setTimeout(function(){ $("#upisnina").focus(); },1000);
	<?php elseif(isset($greska["brojsati"])):?>
	    		setTimeout(function(){ $("#brojsati").focus(); },1000);
	<?php endif; ?>
    </script>
  </body>
</html>
