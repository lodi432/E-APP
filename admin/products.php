<?php
include_once "../konfiguracija.php";
include 'includes/head.php';
include 'includes/izbornik.php';


$format = new NumberFormatter("en_US",NumberFormatter::CURRENCY);
$izraz = $veza->prepare("SELECT * FROM products WHERE deleted = 0;");
$presults= $izraz->execute();



?>


<h2 class="text-center">Products</h2> <hr>





<table class="responsive-card-table unstriped">
  <thead>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Category</th>
      <th>Feature</th>
      <th>Sold</th>
    </tr>
  </thead>
  <tbody>
     <?php   while ($product = $izraz->fetch(PDO::FETCH_ASSOC)): ?>
           <tr>
              <td>
                   <a href="products.php?edit=<?=$product['id'];?>" class ="button tiny"><i class="fas fa-pen-square fa-2x"></i></a>
                   <a href="products.php?delete=<?=$product['id'];?>" class ="button tiny"><i class="fas fa-trash-alt fa-2x"></i></a>
              </td>
              <td><?=$product['title'];?></td>
              <td><?php echo $format->format ($product['price']); ?></td>
              <td></td>
              <td><a href="products.php?featured=<?=(($product['featured']== 0)?'1':'0');?>&id=<?=$product['id'];?>" class="fas fa-<?=(($product['featured']==1)?'minus':'plus');?> fa-2x" ></a></td>
              <td></td>
           </tr>
     <?php endwhile; ?>
  </tbody>
</table>




<?php include_once 'includes/scripts.php';?>
