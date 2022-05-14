<?php
include("./backend/config.php"); // Klassen inkluderas
$objProducts = new Products(); // Ett objekt skapas
$inStock = $objProducts->getData()['inStock'];
 ?>
 

<?php foreach ($inStock as $key => $product): ?>
<?php echo " Product ID " .  $product['articleId'] . " " . "| " . " Quantity " . $product['quantity'] .  " Price " . $product['price'] ?> <br>
<?php endforeach; ?>

<?php
/*
Resultatet blir på följande sätt...
Product ID 1 | Quantity 4 Price 12
Product ID 2 | Quantity 2 Price 20
Product ID 3 | Quantity 0 Price 100
*/
 
 ?>
