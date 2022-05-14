<?php
/*
 * Author: Diyar Parwana
 * Date: 15 May, 2022
 */

class MyStock
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

    public function logOrder(int $product_id, int $amount) : string
    {
        return "purchase(Product $product_id => $amount)";
    }

    public function logStock() : string
    {
        $stocks = "";

        foreach ($this->database["stock"] as $stock) {
            if ($stock["amount"] > 0) {
                $stocks .= $stock["productId"] . " => " . $stock["amount"] . ", ";
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
            if ($stock["productId"] == $product_id) {
                return $stock_index; // So, auto-break and end of the function!
            }
        }
        return null; // Mean: we not have this product in the STOCK!
    }

    public function getStockQuantity(int $product_id) : int
    {
        $index = $this->getStockIndex($product_id);
        if ($index !== null) {
            return $this->database["stock"][$index]["amount"];
        }
        return 0; // Mean: we not have this product in STOCK!
    }

    private function updateStockQuantity(int $product_id, int $new_quantity)
    {
        $index = $this->getStockIndex($product_id);
        if ($index === null) return null;

        $this->database["stock"][$index]["amount"] = $new_quantity;
    }

    private function appendStockQuantity(int $product_id, int $append_quantity)
    {
        $index = $this->getStockIndex($product_id);
        if ($index === null) return null;

        $balance = $this->getStockQuantity($product_id);

        $this->database["stock"][$index]["amount"] = $balance + $append_quantity;
    }

    public function submitOrder(array $pairs) : array
    {
        $order_id = count($this->database["orders"]);
        if ($order_id === 0) $order_id = 1;

        $order_products = [];
        foreach ($pairs as $pair) {
            // Only check if and only if $pair is an array and has excatly two value.
            if (is_array($pair) && count($pair) === 2) {
                // Only check if and only if `amount` is positive
                if ($pair[1] > 0) {
                    $product_id = (int) $pair[0];
                    $amount = (int) $pair[1];

                    $balance = $this->getStockQuantity($product_id);
                    $has_error = false;

                    // It's not possible to buy more then the balance (for all products/stock)
                    if ($amount > $balance) {
                        // continue; // Skip
                        $has_error = true;
                    }

                    // Rule D
                    if ($product_id === 4) {
                        // We not decreasing the balance, so the decreasing is same as old.
                    }

                    // Rule C
                    else if ($product_id === 3) {
                        $this->updateStockQuantity($product_id, $balance - $amount);

                        if ($balance < 20) {
                            $this->appendStockQuantity($product_id, 20);
                        }
                    }

                    // Rule B
                    else if ($product_id === 2) {
                        $this->updateStockQuantity($product_id, $balance - $amount);

                        if ($balance < 10) {
                            $this->appendStockQuantity($product_id, 3);
                        }
                    }

                    // Rule A
                    else if ($product_id === 1) {
                        $this->updateStockQuantity($product_id, $balance - $amount);
                    }

                    if ($has_error === false) {
                        $this->appendLog($this->logOrder($product_id, $amount));

                        $order_products[] = [
                            "productId" => $product_id, // Cast to int, not float and not string code
                            "amount" => $amount // Cast to int, not float
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

$mystock = new MyStock();
$mystock->readDatabase();

// Router
//// Set default route
if (!isset($_GET["route"])) $_GET["route"] = "data";

if ($_GET["route"] === "data") {
    $mystock->appendLog("--------- CALL DATA ROUTE ---------");
    $mystock->appendLog($mystock->logStock());

    print json_encode([
        "status"=>0,
        "data" => $mystock->getDatabase()
    ]);
}
else if ($_GET["route"] === "purchase") {
    $mystock->appendLog("--------- CALL purchase ROUTE ---------");
    $mystock->appendLog($mystock->logStock());

    $data = file_get_contents('php://input');
    $obj = json_decode($data, true);

    if (is_array($obj)) {
        $res = $mystock->submitOrder($obj);

        if ($res["good"] === 0) {
            $mystock->appendLog("purchase: no good orders!");
            print json_encode([
                "status" => 0
            ]);
        } else {
            $mystock->appendLog("purchase: ****Order ". $res["order_id"]  ."****");

            print json_encode([
                "status" => 1,
            ]);

            $mystock->appendLog($mystock->logStock());
        }
    }
    else {
        $mystock->appendLog("purchase: probably bad request body/format, no good orders!");
        print json_encode([
            "status"=>0
        ]);
    }
}
else {
    $mystock->appendLog("--------- CALL BAD ROUTE ---------");
    http_response_code(404);
    print json_encode([
        "status" => 404
    ]);
}

// Test (Development)
// $mystock->readDatabase();
// $mystock->showDatabase();
// $mystock->submitOrder([

// ]);
// $mystock->showDatabase();
// $mystock->saveDatabase();

// var_dump($mystock->getStockQuantity(1));
// var_dump($mystock->getStockQuantity(2));
// var_dump($mystock->getStockQuantity(3));
// var_dump($mystock->getStockQuantity(4));
// var_dump($mystock->getStockQuantity(40));

// Finally, save new database to the file!
$mystock->saveDatabase();
