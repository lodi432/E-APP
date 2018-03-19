<?php
       include_once "../konfiguracija.php";
       include 'includes/head.php';
       include 'includes/izbornik.php';


       $izraz = $veza->prepare("SELECT * FROM categories WHERE parent = 0;");

       $rezulti= $izraz->execute();




?>


<div class="row">

  <div class="columns" >
    <h2>Categories</h2>
    <p><code>Table</code></p>
    <table class="stack" >
      <thead>
        <tr>
          <th width="300">Category</th>
          <th>Parent</th>
          <!-- <th width="150">Table Header</th>
          <th width="150">Table Header</th> -->
        </tr>
      </thead>
      <tbody >
        <?php

        while ($parent = $izraz->fetch(PDO::FETCH_ASSOC)):
          $parent_id = (int) $parent['id'];
    $sql2 = "SELECT * FROM categories WHERE parent='$parent_id'";
    $izraz2 = $veza->prepare($sql2);
    $cresult = $izraz2->execute();

        ?>



        <tr>
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

               <?php endwhile; ?>




        <?php endwhile;?>

      </tbody>
    </table>
  </div>
</div>
<?php include_once 'includes/scripts.php';?>
