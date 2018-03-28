<?php
include_once "../konfiguracija.php";
include 'includes/head.php';
include 'includes/izbornik.php';
//Restore Product
if(isset($_GET['restore'])) {
$id = sanitize($_GET['restore']);
$restorirana=$veza->prepare("UPDATE products SET deleted = 0 WHERE id='$id';");
$restorirana->execute();
header('Location: products.php');exit;
}
?>
<?php
$format = new NumberFormatter("en_US",NumberFormatter::CURRENCY);
$p_result =$veza->prepare( "SELECT * FROM `products` WHERE `deleted`=1;");
$p_result ->execute();
?>
<h2 class="text-center">Products</h2>
<div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed talbe-striped">
<thead>
  <th>Restore</th>
  <th>Product</th>
  <th>Price</th>
  <th>Parent ~ Category</th>
  <th>Featured</th>
  <th>Sold</th>
</thead>
<tbody>
   <?php while ($product = $p_result->fetch(PDO::FETCH_ASSOC)):
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
     <a href="archived.php?restore=<?php echo $product['id']; ?>" class ="button"><i class="fas fa-undo"></i></a>
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
<?php include 'includes/podnozje.php';
include_once 'includes/scripts.php';
?>
