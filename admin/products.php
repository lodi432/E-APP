<?php
include_once "../konfiguracija.php";
include 'includes/head.php';
include 'includes/izbornik.php';


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




<?php include_once 'includes/scripts.php';?>
