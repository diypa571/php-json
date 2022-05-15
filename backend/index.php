<?php
/*
 * Author: Diyar Parwana
 * Date: 15 May, 2022
 */

class TheStack
{
    private $database = [
        "stock" => [],
        "orders" => []
    ];
    private $database_file = "db.json";
    private $log_file = "state.log";

    public function appendLog(string $line)
    {
        file_put_contents($this->log_file, trim($line)."\n", FILE_APPEND);
    }

    public function logOrder(int $product_id, int $quantity) : string
    {
        return "purchase(Product $product_id => $quantity)";
    }

    public function logStock() : string
    {
        $stocks = "";

        foreach ($this->database["stock"] as $stock) {
            if ($stock["quantity"] < 0) {
                $stocks .=  "0".$stock["quantity"] . ", ";
            }
            else {
                  $stocks .=  $stock["quantity"] . ", ";
            }
        }

        $stocks = rtrim($stocks, ", ");

        if ($stocks === "") $stocks = "EMPTY";
        return "inStock(". $stocks .")";
    }

    public function showDatabase()
    {
        print_r($this->database);
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function saveDatabase()
    {
        return file_put_contents($this->database_file, json_encode($this->database));
    }

    public function initDatabase(array $default)
    {
        if (is_array($default) && isset($default["stock"]) && isset($default["orders"])) {
            // Update current database
            $this->database = $default;
        }
    }

    public function readDatabase()
    {
        // Check the file exists
        if (!file_exists($this->database_file)) return null;

        // Read the file
        $data = file_get_contents($this->database_file);
        $obj = json_decode($data, true);

        if (isset($obj["stock"]) && isset($obj["orders"])) {
            // Update current database
            $this->database = $obj;
        }
    }

    public function getStockIndex(int $product_id) :  int
    {
        foreach($this->database["stock"] as $stock_index => $stock) {
            if ($stock["articleId"] == $product_id) {
                return $stock_index; // So, auto-break and end of the function!
            }
        }
        return null; // When we dont have the item
    }

    public function getStockQuantity(int $product_id) : int
    {
        $index = $this->getStockIndex($product_id);
        if ($index !== null) {
            return $this->database["stock"][$index]["quantity"];
        }
        return 0; // We not have this product inSTOCK!
    }

    private function updateStockQuantity(int $product_id, int $new_quantity)
    {
        $index = $this->getStockIndex($product_id);
        if ($index === null) return null;

        $this->database["stock"][$index]["quantity"] = $new_quantity;
    }

    private function appendStockQuantity(int $product_id, int $append_quantity)
    {
        $index = $this->getStockIndex($product_id);
        if ($index === null) return null;

        $balance = $this->getStockQuantity($product_id);

        $this->database["stock"][$index]["quantity"] = $balance + $append_quantity;
    }

    public function submitOrder(array $pairs) : array
    {
        $order_id = count($this->database["orders"]);
        if ($order_id === 0) $order_id = 1;

        $order_products = [];
        foreach ($pairs as $pair) {
            // Only check if and only if $pair is an array and has excatly two value.
            if (is_array($pair) && count($pair) === 2) {
                // Only check if and only if `quantity` is positive
                if ($pair[1] > 0) {
                    $product_id = (int) $pair[0];
                    $quantity = (int) $pair[1];

                    $balance = $this->getStockQuantity($product_id);
                    $has_error = false;

                    // It's not possible to buy more then the balance (for all products/stock)
                    if ($quantity > $balance) {
                        // continue; // Skip
                        $has_error = true;
                    }

                    // Rule D
                    if ($product_id === 4) {
                  $this->appendStockQuantity($product_id, $quantity);
                    }

                    // Rule C
                    else if ($product_id === 3) {
                        $this->updateStockQuantity($product_id, $balance - $quantity);

                        if ($balance < 20) {
                            $this->appendStockQuantity($product_id, 20);
                        }
                    }

                    // Rule B
                    else if ($product_id === 2) {
                        $this->updateStockQuantity($product_id, $balance - $quantity);

                        if ($balance < 10) {
                            $this->appendStockQuantity($product_id, 3);
                        }
                    }

                    // Rule A
                    else if ($product_id === 1) {
                        $this->updateStockQuantity($product_id, $balance - $quantity);
                    }

                    if ($has_error === false) {
                        $this->appendLog($this->logOrder($product_id, $quantity));

                        $order_products[] = [
                            "articleId" => $product_id, // Cast to int, not float and not string code
                            "quantity" => $quantity // Cast to int, not float
                        ];
                    }
                }
            }
        }

        if(count($order_products) > 0) {
            $new_order = [
                "orderingId" => $order_id,
                "products" => $order_products
            ];
            $this->database["orders"][] = $new_order;
        }

        return [
            "order_id" => count($order_products) > 0 ? $order_id : null,
            "good" => count($order_products),
            "bad" => count($pairs) - count($order_products)
        ];
    }
}

$TheStack = new TheStack();
$TheStack->readDatabase();

// Router
//// Set default route
if (!isset($_GET["route"])) $_GET["route"] = "data";

if ($_GET["route"] === "data") {
    $TheStack->appendLog("--------- CALL DATA ROUTE ---------");
    $TheStack->appendLog($TheStack->logStock());

    print json_encode([
        "status"=>0,
        "data" => $TheStack->getDatabase()
    ]);
}
else if ($_GET["route"] === "purchase") {
    $TheStack->appendLog("--------- CALL purchase ROUTE ---------");
    $TheStack->appendLog($TheStack->logStock());

    $data = file_get_contents('php://input');
    $obj = json_decode($data, true);

    if (is_array($obj)) {
        $res = $TheStack->submitOrder($obj);

        if ($res["good"] === 0) {
            $TheStack->appendLog("purchase: no good orders!");
            print json_encode([
                "status" => 0
            ]);
        } else {
            $TheStack->appendLog(" ***Order ". $res["order_id"]  ." ****");

            print json_encode([
                "status" => 1,
            ]);

            $TheStack->appendLog($TheStack->logStock());
        }
    }
    else {
        $TheStack->appendLog("purchase: probably bad request body/format, no good orders!");
        print json_encode([
            "status"=>0
        ]);
    }
}
else {
    $TheStack->appendLog("--------- CALL BAD ROUTE ---------");
    http_response_code(404);
    print json_encode([
        "status" => 404
    ]);
}

// Test (Development)
// $TheStack->readDatabase();
// $TheStack->showDatabase();
// $TheStack->submitOrder([

// ]);
// $TheStack->showDatabase();
// $TheStack->saveDatabase();

// var_dump($TheStack->getStockQuantity(1));
// var_dump($TheStack->getStockQuantity(2));
// var_dump($TheStack->getStockQuantity(3));
// var_dump($TheStack->getStockQuantity(4));
// var_dump($TheStack->getStockQuantity(40));

// Finally, save data to the file!
$TheStack->saveDatabase();
