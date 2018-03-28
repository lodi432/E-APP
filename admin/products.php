
<?php
include_once "../konfiguracija.php";
include 'includes/head.php';
include 'includes/izbornik.php';
$dbpath='';
if(isset($_GET['add']) || isset($_GET['edit'])){
$brandQuery= $veza->prepare("SELECT * FROM brand ORDER BY brand;");
$brandQuery->execute();
$parentQuery =$veza->prepare("SELECT* FROM categories WHERE parent = 0 ORDER BY category;");
$parentQuery->execute();
$title = ((isset($_POST['title']) && $_POST['title'] !='')?sanitize($_POST['title']):'');
$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child']))&& !empty ($_POST['child'])?sanitize($_POST['child']): '');
$price = ((isset($_POST['price']) && $_POST['price'] !='')?sanitize($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] !='')?sanitize($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] !='')?sanitize($_POST['description']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] !='')?sanitize($_POST['sizes']):'');
$saved_image = '';




if(isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $productResults = $veza->prepare("SELECT * FROM products WHERE id = '$edit_id';");
    $productResults->execute();
      $product=$productResults->fetch(PDO::FETCH_ASSOC);
      if (isset($_GET['delete_image'])){
        $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image']; echo $image_url;
        unlink($image_url);
        $izrazDel= $veza->prepare("UPDATE products SET image = ''WHERE id = '$edit_id';");
        $izrazDel->execute();
        header('Location: products.php?edit='.$edit_id);

      }
      $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
      $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
      $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
      $parentIzraz = $veza->prepare ("SELECT * FROM categories WHERE id = '$category';");
      $parentIzraz->execute();
      $parentResult=$parentIzraz->fetch(PDO::FETCH_ASSOC);
      $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
      $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
      $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product['list_price']);
      $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
      $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
      $saved_image = (($product['image'] !='')?$product['image']:'');
      $dbpath = $saved_image;
     }

if($_POST){

$price = ((isset($_POST['price']) && $_POST['price'] !='')?sanitize($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] !='')?sanitize($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] !='')?sanitize($_POST['description']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] !='')?sanitize($_POST['sizes']):'');
$sizes=rtrim($sizes,',');
$saved_image = '' ;

  $erros= array();

  $required = array('title','brand','price','parent','child');
   foreach($required as $field){
     if($_POST[$field]==''){
       $errors[]= 'All fields With and Astrisk are required.';
       break;
     }
   }
   if(!empty($_FILES)){
     var_dump($_FILES);
     $photo = $_FILES['photo'];
    $name = $photo['name'];
    $nameArray = explode('.',$name);
    $fileName = $nameArray[0];
    $fileExt = $nameArray[1];
    $mime = explode('/',$photo['type']);
    $mimeType = $mime[0];
    $mimeExt = $mime[1];
    $tmpLoc = $photo['tmp_name'];
    $fileSize = $photo['size'];
    $allowed = array ('png','jpg','jpeg','gif');
    $uploadName = md5(microtime()).'.'.$fileExt;
    $uploadPath = BASEURL.'images/products/'.$uploadName;
    $dbpath = '/EcomApp/images/products/'.$uploadName;

        if ($mimeType != 'image') {
          $errors [] ='The file must be an image.' ;
           }
           if (!in_array($fileExt, $allowed)) {
             $errors[] = 'The file extension must be a png,jpg,jpeg, or gif.';
           }
           if($fileSize > 15000000){
             $errors[] = 'The file size must be under 15MB.';
           }
           if ($fileExt != $mimeExt && ($mimeExt == 'jpg' && $fileExt != 'jpg')) {
                $errors [] = 'File extension does not match the file.';
           }
        }
       if (!empty($errors)){
         echo display_errors($errors);
       } else {
     //upload file and instert into database
           move_uploaded_file($tmpLoc,$uploadPath);
           $insertSql=$veza->prepare("INSERT INTO products (`title`,`price`,`list_price`,`brand`,`categories`,`sizes`,`image`,`description`)
       VALUES ('$title','$price','$list_price','$brand','$category','$sizes','$dbpath','$description');");
      if(isset($_GET['edit'])){
        $insertSql = $veza->prepare("UPDATE products SET title = '$title',price='$price',list_price ='$list_price',
            brand= '$brand',categories='$category',sizes='$sizes',image='$dbpath', description= '$description'
            WHERE id='$edit_id';");
      }
       $insertSql->execute();
       header('Location: products.php');
   }
}
?>






<h2 class ="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Product</h2><hr>

<form action ="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data" >



  <div class="row">
   <div class="small-6 large-6 columns ">
     <label for="title">Title*:
        <input type="text" name="title" class="former" id="title" placeholder="large-12.columns" value="<?=$title;?>"/>
      </label>
    </div>
    <div class="small-6 large-6 columns">
       <label for="brand">Brand*: </label>
       <select class="form-group" id="brand" name="brand">
            <option value=""<?=(($brand=='')?' selected':'');?>></option>
            <?php  while ($_brand= $brandQuery->fetch(PDO::FETCH_ASSOC)): ?>
          <option value="<?=$_brand['id'];?>"<?=(($brand== $_brand ['id'])?' selected':'');?>><?=$_brand['brand'];?></option>
          <?php endwhile; ?>
       </select>
</div>



    <div class="small-6 large-6 columns ">
      <label for="parent">Parent Category*:
         <select class="form-group" id="parent" name="parent">
         <option value =""<?=(($parent== '')?' selected':'');?>></option>

         <?php while($_parent = $parentQuery->fetch(PDO::FETCH_ASSOC)): ?>
           <option value="<?=$_parent['id'];?>"<?=(($parent == $_parent['id'])?' selected':'');?>><?=$_parent['category'];?></option>

         <?php endwhile; ?>

       </label>
       </select>
     </div>
     <div class="small-6 large-6 columns">
       <label for="child">Child Category:*</label>
   			<select id="child" name="child" class="form-control">
   </select>

     </div>

       <div class="small-6 large-4 columns ">
       <label for="price">Price*: </label>
       <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
 </div>
 <div class="small-6 large-4 columns ">
 <label for="list_price">List Price: </label>
 <input type="text" id="list_price" name="price" class="form-control" value="<?=$list_price;?>">
</div>

<div class="small-6 large-4 columns ">
  <label>Quantity & Sizes*: </label>
 <button class="button" id="kol">Quantity & Sizes</button>



</div>
<div class="small-6 large-4 columns ">
<label for ="sizes">Sizes & Qty Preview</label>
<input type="text" class="form-control" name="size" id="sizes" value="<?=$sizes;?>"readonly>


</div>

<div class="small-12 large-4 columns ">
  <?php if($saved_image != ''):?>
    <div class="savedimg">
       <img src="<?=$saved_image;?>"alt="saved image"/><br><br>
        <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="button label alert">Delete Image</a>
    </div>

<?php else: ?>
   <label for="photo">Product Photo:</label>
 <input type="file" name="photo" id="photo" class="form-control">
<?php endif;?>
</div>

<div class="small-12 large-4 columns">
  <label for ="description">Description:</label>
  <textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>

</div>
<a href="products.php" class="button">Cancel</a>
<div class="small-6 large-3 columns ">
<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class="button  " >
</div>


<input type="hidden" name="qtyandsizes" id="qtyandsizes" />


</form>




<div class="reveal" id="sizesModal" data-reveal data-animation-in="fade-in" data-options="closeOnClick:false;closeOnEsc:false;"  data-animation-out="fade-out" >
<label for ="">Sizes & Qty</label>

  <div class="row">
  <?php for($i=1;$i<=12;$i++): ?>
       <div class="small-4 medium-4 columns ">
          <label for= "size_<?=$i;?>">Size:</label>
          <input type="text"  id="size_<?=$i;?>" value ="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">

       </div>
       <div class="small-2 medium-2 columns ">
        <label for= "qty_<?=$i;?>">Quantity:</label>
          <input type="number"  id="qty_<?=$i;?>" value ="" min="0">

       </div>
  <?php endfor; ?>
</div>

  <button class="close-button" data-close aria-label="Close reveal" type="button">



    <span aria-hidden="true">&times;</span>

</button>
  <button class="button"  id="saveChangesSizes"> Save Changes

   </button>

</div>





<?php }else{
$format = new NumberFormatter("en_US",NumberFormatter::CURRENCY);
$izraz = $veza->prepare("SELECT * FROM products WHERE deleted = 0;");
$presults= $izraz->execute();
if(isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured=(int)$_GET[featured];
  $izdvojeni = $veza->prepare("UPDATE products SET featured = '$featured' WHERE id='$id';");
  $izdvojeni->execute();
   header('Location: products.php');
}
?>


<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="button pull-right" id="">Add Product</a><div class="clearfix"></div>
<hr>





<table class="responsive-card-table unstriped">
  <thead>
    <tr>
      <th></th>
      <th>Product</th>
      <th>Price</th>
      <th>Category</th>
      <th>Featured</th>
      <th>Sold</th>
    </tr>
  </thead>
  <tbody>
     <?php   while ($product = $izraz->fetch(PDO::FETCH_ASSOC)):
             $childId = $product['categories'];
              $catSql = $veza->prepare("SELECT * FROM categories WHERE id='$childId';");
              $result= $catSql->execute();
              $child = $catSql->fetch(PDO::FETCH_ASSOC);
              $parentId = $child['parent'];
                $parentSql = $veza->prepare("SELECT * FROM categories WHERE id='$parentId';");
               $parentSql->execute();
                $parent =$parentSql->fetch(PDO::FETCH_ASSOC);
                $category = $parent['category'].'~'.$child['category'];
       ?>
           <tr>
              <td>
                   <a href="products.php?edit=<?=$product['id'];?>" class ="button tiny"><i class="fas fa-pen-square fa-2x"></i></a>
                   <a href="products.php?delete=<?=$product['id'];?>" class ="button tiny"><i class="fas fa-trash-alt fa-2x"></i></a>
              </td>
              <td><?=$product['title'];?></td>
              <td><?php echo $format->format ($product['price']); ?></td>
              <td><?php echo $category;?></td>
              <td><a href="products.php?featured=<?=(($product['featured']== 0)?'1':'0');?>&id=<?=$product['id'];?>"
                class ="button tiny"><i class="fas fa-<?=(($product['featured']==1)?'minus':'plus');?> fa-2x " ></i>
              </a>&nbsp <?=(($product['featured']==1)?'Featured Product': '');?></td>
              <td>0</td>

           </tr>


     <?php endwhile; ?>



  </tbody>
</table>


<?php  }?>
<?php
include_once 'includes/podnozje.php';
include_once 'includes/scripts.php';
?>
<script>
jQuery('document').ready(function(){
  get_child_options('<?=$category;?>');

});

$("#saveChangesSizes").click(function(){
	console.log("1");
	var velicine="";
	var niz=new Array();
	for(var i=1; i<=12;i++){
		if($("#size_" + i).val()!=""){
			niz.push({size: $("#size_" + i).val(), qty: $("#qty_" + i).val()});
			velicine+=$("#size_" + i).val() + ":" + $("#qty_" + i).val() + ",";
		}
	}
	console.log(niz);
	if(velicine.length>0){
		velicine=velicine.substring(0,velicine.length-1);
	}
	$("#qtyandsizes").val(JSON.stringify(niz));

	$("#sizes").val(velicine);
	 $("#sizesModal").foundation("close");
	 return false;
});




$("#kol").click(function(){
  $("#sizesModal").foundation("open");
  return false;
});
</script>
