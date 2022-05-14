<?php
require "./backend/config.php";
$inStock = get_db()['inStock'];
 ?>


<?php foreach ($inStock as $key => $product): ?>
<?php echo " Product ID " .  $product['articleId'] . " " . "| " . " Quantity " . $product['quantity'] .  " Price " . $product['price'] ?> <br />
<?php endforeach; ?>
