<?php
       include_once "../konfiguracija.php";
       include 'includes/head.php';
       include 'includes/izbornik.php';


       $izraz = $veza->prepare("SELECT * FROM categories WHERE parent = 0;");

       $rezulti= $izraz->execute();


$errors = array();
$category='';
$post_parent='';



//Edit Kategorije
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $edit_id=sanitize($edit_id);

  $edit_sql= $veza->prepare ("SELECT * FROM categories WHERE id = :edit_id");
  $edit_sql->execute(array('edit_id' => $_GET['edit']));
   $edit_category = $edit_sql->fetch(PDO::FETCH_ASSOC);

}

//Brisanje kategorije

if(isset($_GET['delete']) && !empty($_GET['delete'])){

//Brisanje kategorije ako je Parent obrisan
  $sql= $veza->prepare ("SELECT * FROM categories WHERE id = :delete_id");
   $sql->execute(array('delete_id' => $_GET['delete']));
  $category = $sql->fetch(PDO::FETCH_ASSOC);
  if($category['parent'] == 0){

    $sql= $veza->prepare("DELETE FROM categories WHERE parent = :delete_id");
	$sql->execute(array('delete_id' => $_GET['delete']));
  }



  $dsql=$veza->prepare("DELETE FROM categories WHERE id = :delete_id");
	$dsql->execute(array('delete_id' => $_GET['delete']));
  header("location: categories.php");


}




//Procesiranje forme
if (isset($_POST) && !empty($_POST)){
  $post_parent = sanitize($_POST['parent']);
  $category =sanitize($_POST['category']);


  $sqlform ="SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
  if(isset($_GET['edit'])){
    $id = $edit_category['id'];
    $sqlform = "SELECT * FROM categories WHERE category ='$category' AND parent ='$post_parent' AND id != '$id'";
  }

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
$updatesql =$veza->prepare("INSERT INTO categories (category,parent) VALUES ('$category','$post_parent')");
if(isset($_GET['edit'])){
$updatesql =$veza->prepare("UPDATE categories SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'");
}
$updatesql->execute();
header('Location: categories.php');

}
}
$category_value='';
$parent_value=0;
if(isset($_GET['edit'])){
  $category_value = $edit_category['category'];
  $parent_value = $edit_category['parent'];
}else {
  if(isset($_POST)){
    $category_value = $category;
    $parent_value= $post_parent;
  }
}

 ?>





<!---FORMA -->
<br><br><br>
<div class="grid-x grid-padding-x">
<div class="large-12 columns">
  <div class="row collapse">
    <div class="small-4 columns">
<form action ="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post" enctype="multipart/form-data">
  <legend><?=((isset($_GET['edit']))?'Edit':'Add A');?> Category</legend>
  <div id="errors"></div>
<!-- <label for="parent">Parent</label> -->
<select class="form-control" name="parent" id="parent">
  <option style="color: gray;" value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>Parent</option>
  <?php while ($parent = $izraz->fetch(PDO::FETCH_ASSOC)): ?>
           <option value="<?=$parent['id'];?>"<?=(($parent_value ==$parent['id'])?' selected="selected"':'');?>><?=$parent['category'];?></option>
  <?php endwhile?>
</select>
<input type="text" class="form-control" id="category" name="category" placeholder="Category" value="<?=$category_value;?>" />
<input type ="submit" value ="<?=((isset($_GET['edit']))?'Edit':'Add');?> Category" class="button">
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
