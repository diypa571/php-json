<?php
session_start();
// Kontrollera om session isset().
$_SESSION['cart'] = $_SESSION['cart'] ?? [];


include("./backend/config.php"); // Klassen inkluderas
$objProducts = new Products(); // Ett objekt skapas
$inStock = $objProducts->getData()['inStock'];


if(isset($_POST['add']))
{
$objProducts->addCart();
}


?>


Kundvagn <?php echo count($_SESSION['cart']); ?>

<ul class="list-group list-group-flush">
<?php foreach ($_SESSION['cart'] as $key => $amount) {?>
   <li class="list-group-item">
    Product name:<strong> <?php echo $inStock[$key]['articleId'] . "</strong> |  amount:<strong> " . $amount . "</strong>"; ?>
<?php } ?>
</li>
</ul>
<a href="checkout.php" class="btn btn-success rounded-pill my-3">Till checkout</a>



<hr>



<?php foreach ($inStock as $key => $product){ ?>
<?php echo " Product ID " .  $product['articleId'] . " " . "| " . " Quantity " . $product['quantity'] .  " Price " . $product['price'] ?> <br>
<form method="post" action="">
<input type="hidden" name="productIndex" value="<?php echo $key ?>">
<button class="btn btn-primary my-4 rounded-pill" name="add" type="submit">Lägg i kundvagn</button>
</form>
<hr>
<?php } ?>

<?php
/*
Resultatet blir på följande sätt...
Product ID 1 | Quantity 4 Price 12
Product ID 2 | Quantity 2 Price 20
Product ID 3 | Quantity 0 Price 100
*/
 
 ?>
