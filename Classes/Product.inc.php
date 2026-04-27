<?php
class Product {

    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private float $price,
        private bool $onSale,
        private array $imagesByView) { 
            if ($price < 0) {
            $price = 0.00;
        }
            if ($inventory < 0) {
            $inventory = 0;
        }
    }

    public function __set($name, $value) {
        if ($name === 'price' && $value < 0) {
            $this->price = 0.00;
        } elseif ($name === 'inventory' && $value < 0) {
            $this->inventory = 0;
        } else {
            $this->$name = $value;
        }
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $this->price = 0;
        $this->inventory = 0;
        $this->onsale = false;
    }

    public function __toString() {
        $output = "<p>Product: ". $this->description . "<br>\n";
        $output .= "Price: $". number_format($this->price, 2) . "<br>\n";
        $output .= "Inventory: " . $this->inventory . "<br>\n";
        $output .= "On sale: ";
        if ($this->onsale) {
          $output .= "Yes</p>\n";
        } else {
          $output .= "No</p>\n";
        }
        return $output;
    }

    public function buyProduct($amount) {
        if ($this->inventory >= $amount) {
          $this->inventory -= $amount;
        } else {
          echo "<p>Sorry, invalid inventory requested: $amount</p>\n";
          echo "<p>There are only $this->inventory left</p>\n";
        }
    }

    public function putonsale() {
      $this->onsale = true;
    }
    public function takeoffsale() {
      $this->onsale = false;
    }   
}
?>