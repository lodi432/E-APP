
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/EcomApp/konfiguracija.php';



$parentID = (int)$_POST['parentID'];
$selected = sanitize($_POST['selected']);
$childQuery= $veza->prepare("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category;");
$childQuery->execute();
ob_start(); ?>


<option value =""></option>
<?php while ($child = $childQuery->fetch(PDO::FETCH_ASSOC)): ?>
 <option value="<?=$child['id'];?>" <?=(($selected == $child['id'])?' selected':'');?>><?=$child['category'];?></option>

  <?endwhile; ?>
<?php echo ob_get_clean();?>
