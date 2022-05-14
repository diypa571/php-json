<?php
Class Products
{

private $dataFile ='demo_data.json';

// Konstruktor funktion
public function __Construct()
{
	 return define("DATA_SOURCE", $this->dataFile);
}


//  Klass metod som returnerar data som en PHP Array
public function getData() {
	// Kontrollera om filen existerar
	if (!file_exists(__DIR__ . "/" . DATA_SOURCE)) {
	// Filen existerar inte, skapa och skriva i filen
	$dataInit = [
	'inStock' => [],
	];

	//  Funktionen file_put_contents för att skriva
	file_put_contents(__DIR__ . "/" . DATA_SOURCE, json_encode($dataInit, JSON_PRETTY_PRINT));
	}
	// Här returneras  data i json format
	return json_decode(file_get_contents(__DIR__ . "/" . DATA_SOURCE), true);
} // Slut av getData Funktionen




public function addCart() {
  // Lägg varan i kundvagn
	   $cartElem = & $_SESSION['cart'][$_POST['productIndex']];
	   if (!isset($cartElem)) {
	     $cartElem = 0;
	   }

	  $cartElem++;

}



public function deleteCart() {
	unset($_SESSION['cart'][$_POST['remove']]);
}



}


?>
