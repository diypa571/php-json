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

    public function showDatabase()
    {
        print_r($this->database);
    }

    public function saveDatabase()
    {
        return file_put_contents($this->database_file, json_encode($this->database));
    }

    public function readDatabase()
    {
        // Check the file exists
        if (!file_exists($this->database_file)) return null;

        // Read the file
        $data = file_get_contents($this->database_file);
        $obj = json_decode($data, true);

        // Update current database
        $this->database = $obj;
    }

    public function getStockIndex(int $product_id) : ?int
    {
        $a = array_filter($this->database["stock"], function($item) use ($product_id) {
            if ($item["productId"] == $product_id) {
                return true;
            }
        });

        if (count($a) > 0) {
            return array_key_first($a);
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

    public function submitOrder(array $pairs)
    {
        $order_id = count($database["orders"]);
        if ($order_id === 0) $order_id = 1;

        $order_products = [];
        foreach ($pairs as $pair) {
            $order_products[] = [
                "productId" => (int) $pair[0],
                "amount" => (int) $pair[1]
            ];
        }
        $new_order = [
            "orderingId" => $order_id,
            "products" => $order_products
        ];
        $database["orders"][] = $new_order;
    }
}

$mystock = new MyStock();
$mystock->readDatabase();
$mystock->showDatabase();
$mystock->saveDatabase();


var_dump($mystock->getStockQuantity(1));
var_dump($mystock->getStockQuantity(2));
var_dump($mystock->getStockQuantity(3));
var_dump($mystock->getStockQuantity(4));
var_dump($mystock->getStockQuantity(40));
