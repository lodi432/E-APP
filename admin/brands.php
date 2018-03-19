<?php include_once "../konfiguracija.php";


//EDIT

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
  $edit_result = $izraz=$veza->prepare($sql2);
  $eBrand = $izraz->execute();
  $eBrand = $izraz->fetch();

}


   $errors = array();

  if (isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']);


     //ako je ostavljeno prazno
     if ($_POST['brand'] == ''){
       $errors[] .= 'You must enter a brand!';
     }


     //ako postoji u bazi
     $izraz=$veza->prepare("SELECT * FROM brand WHERE brand ='$brand'");
     if(isset($_GET['edit'])){
        $izraz=$veza->prepare("SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'");
     }
   	$izraz->execute(array("brand"=>$_POST['brand']));
   	$sifra = $izraz->fetchColumn();
   	if($sifra>0){
   		$errors["brand"]="Naziv postoji u bazi, odabrati drugi";
   	 }

     //prikazi greske
     if(!empty($errors)){
       echo display_errors($errors);
     }else{

     //dodavanje u bazu
     $izraz=$veza->prepare("INSERT INTO brand (brand) VALUES ('$brand')");
     if(isset($_GET['edit'])){
       $izraz=$veza->prepare("UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'");
     }
     $izraz->execute();
     header("Location: brands.php");


  }
   }




 ?>
 <!doctype html>
 <html class="no-js" lang="en" dir="ltr">

 <head>
        <?php include_once "includes/head.php"; ?>
        <style>
          table tbody tr td:nth-child(2),
          table tbody tr td:nth-child(3),
          table tbody tr td:nth-child(4){
            text-align: right;
          }
        </style>
</head>



<body>
  <div class="grid-container">
        <?php include_once "includes/izbornik.php";
        ?>

<div class="grid-x grid-padding-x">
  <div class="large-12 cell">
    <table>
    					<thead>
    						<tr>
    							<th>Naziv</th>
    							<th>Akcija</th>
    						</tr>
    					</thead>
  					<tbody>


<br>
                <form action = "brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
                          <div class="row">
                              <div class="large-12 columns">
                                <div class="row collapse">
                                  <div class="small-4 columns">
                                    <?php
                                    $brand_value = "";

                                     if(isset($_GET['edit'])){
                                      $brand_value =$eBrand['brand'];
                                    }else {
                                      if(isset($_POST['brand'])){
                                        $brand_value =sanitize($_POST['brand']);
                                      }
                                    } ?>
                                    <input type="text" name="brand" id="brand" value="<?=$brand_value;?>" >
                                  </div>
                                    <p style="margin-left: 10px;"><p>
                                  <div class="small-7 columns">

                                    <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add a');?> Brand" class="button postfix">
                                    <?php if(isset($_GET['edit'])): ?>
                                    <a href="brands.php" class="button custom">Cancel</a>
                                  <?php endif;?>
                                  </div>
                                </div>
                              </div>
                            </div>
                </form>


                <?php
					$izraz = $veza->prepare("SELECT * FROM brand ORDER BY brand ;");
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
					foreach ($rezultati as $red):
          ?>

						<tr>
							<td><?php echo $red->brand; ?></td>
						<td>
								<a href="?edit=<?php echo $red->id; ?>"><i class="far fa-edit fa-2x"></i></a>
								<!-- <?php if($red->brand==0): ?> -->
								<a href="brisanje.php?sifra=<?php echo $red->id; ?>"><i class="far fa-trash-alt fa-2x"></i></a>
								<?php endif; ?>
							</td>
						</tr>


					<?php endforeach; ?>

        </tbody>
      </table>
    </div>

  </div>

        <?php include_once 'includes/scripts.php';?>

      </body>
      </html>
