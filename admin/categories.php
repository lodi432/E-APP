<?php
       include_once "../konfiguracija.php";
       include 'includes/head.php';
       include 'includes/izbornik.php';


       $izraz = $veza->prepare("SELECT * FROM categories WHERE parent = 0;");

       $rezulti= $izraz->execute();


$errors = array();
//Procesiranje forme
if (isset($_POST) && !empty($_POST)){
  $parent = sanitize($_POST['parent']);
  $category =sanitize($_POST['category']);


  $sqlform ="SELECT * FROM categories WHERE category = '$category' AND parent = '$parent'";

  $izraz3 = $veza->prepare($sqlform);
$fresult =$izraz3->execute();
$count = $izraz3->fetch();



// var_dump($category);

  if($category == ''){
   $errors[] .= "Kategorija ne smije ostati prazna !";
  }


  //Ako postoji u bazi podataka
  if($count>0){
    $errors[] .= $category. ' postoji. Molim vas odaberite drugu kategoriju.';
  }


//Prikaži greške ili napravi UPDATE
if(!empty($errors)){
  echo display_errors($errors);?>
  <script>
  JQuery('document').ready(function(){
    jQuery('#errors').html('<?=$display; ?>');
  });
  </script>
<?php   }else{
//Update na bazu
$updatesql =$veza->prepare("INSERT INTO categories (category,parent) VALUES ('$category','$parent')");
$updatesql->execute();
header('Location: categories.php');

}
}

 ?>





<!---FORMA -->
<br><br><br>
<div class="grid-x grid-padding-x">
<div class="large-12 columns">
  <div class="row collapse">
    <div class="small-4 columns">
<form action ="categories.php" method="post" enctype="multipart/form-data">
  <legend>Add a Category</legend>
  <div id="errors"></div>
<!-- <label for="parent">Parent</label> -->
<select class="form-control" name="parent" id="parent">
  <option style="color: gray;" value="0">Parent</option>
  <?php while ($parent = $izraz->fetch(PDO::FETCH_ASSOC)): ?>
           <option value="<?=$parent['id'];?>"><?=$parent['category'];?></option>
  <?php endwhile?>
</select>
<input type="text" class="form-control" id="category" name="category" placeholder="Category" />
<input type ="submit" value ="Add Category" class="button">
</form>

</div>
</div>

</div>
</div>











<div class="row">

  <div class="columns" >
    <h2></h2>
    <p><code>Kategorije</code></p>

    <div class="large-12 columns">
    <table class="stack" >
      <thead>
        <tr >
          <th width="300" >Category</th>
          <th>Parent</th>
          <!-- <th width="150">Table Header</th>
          <th width="150">Table Header</th> -->
        </tr>
      </thead>
      <tbody >
        <?php
               $izraz = $veza->prepare("SELECT * FROM categories WHERE parent = 0;");
               $rezulti= $izraz->execute();

        while ($parent = $izraz->fetch(PDO::FETCH_ASSOC)):
          $parent_id = (int) $parent['id'];


    $sql2 = "SELECT * FROM categories WHERE parent='$parent_id'";
    $izraz2 = $veza->prepare($sql2);
    $cresult = $izraz2->execute();

        ?>



        <tr class="CSScategory">
          <td><?=$parent['category'];?></td>
          <td>Parent</td>
          <td>
            <a href="categories.php?edit=<?=$parent['id'];?>" class ="button tiny"><i class="far fa-edit fa-2x"></i></a>
            <a href="categories.php?delete=<?=$parent['id'];?>" class ="button tiny"><i class="far fa-trash-alt fa-2x"></i></a>
          </td>
        </tr>

        <?php while($child = $izraz2->fetch(PDO::FETCH_ASSOC)): ?>
                 <tr>
                   <td><?=$child['category'];?></td>
                   <td><?=$parent['category'];?></td>
                   <td>
                     <a href="categories.php?edit=<?=$child['id'];?>" class ="button tiny"><i class="far fa-edit fa-2x"></i></a>
                     <a href="categories.php?delete=<?=$child['id'];?>" class ="button tiny"><i class="far fa-trash-alt fa-2x"></i></a>
                   </td>

                 </tr>
            </div>
          </div>
        </div>
               <?php endwhile; ?>




        <?php endwhile;?>

      </tbody>
    </table>
  </div>
</div>
<?php include_once 'includes/scripts.php';?>
