<?php
include('Product.inc.php');

class Keyboard extends Product {

    publice funtion __construct(
        protected int $id,
        protected string $title,
        protected string $description,
        protected float $price,
        protected bool $onSale,
        protected array $imagesByView,
        protected int $numberOfKeys) {
            parent::__construct($id, $title, $description, $price, $onSale, $imagesByView);
            }
    
    public function __toString() {
        $output = "<p>Product: ". $this->description . "<br>\n";
        $output .= "Price: $". number_format($this->price, 2) . "<br>\n";
        $output .= "Inventory: " . $this->inventory . "<br>\n";
        $output .= "On sale: ";
        if ($this->onsale) {
          $output .= "Yes<br>\n";
        } else {
          $output .= "No<br>\n";
        }
        $output .= "Number of keys: " . $this->numberOfKeys . "</p>\n";
        return $output;
    }

    public function restock($amount) {
        $this->inventory += $amount;
    }
}
?>