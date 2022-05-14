
<?php
// Skapar en konstant för json filen
define("DATA_SOURCE", "demo_data.json");

// Skapas en function som returnerar data som konverteras till PHP array
function getData() {
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
}
