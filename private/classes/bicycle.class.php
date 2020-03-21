<?php
class Bicycle extends DatabaseObject
{
    // ----Start of active record code----
    protected static $table_name = "bicycles";
    protected static $db_columns = [
        'id', 'brand', 'model', 'year', 'category', 'color',
        'gender', 'price', 'weight_kg', 'condition_id',
        'description'
    ];

    //class constants
    public const CATEGORIES = [
        'Hybrid', 'Road', 'City', 'Mountain', 'Cruiser', 'BMX'
    ];
    public const GENDERS = ['men', 'women', 'unisex'];
    public const CONDITION_OPTIONS = [
        1 => 'Beat up',
        2 => 'Decent',
        3 => 'Good',
        4 => 'Great',
        5 => 'Like New'
    ];

    // class properties
    public      $id;
    public      $brand;
    public      $model;
    public      $year;
    public      $category;
    public      $color;
    public      $description;
    public      $gender;
    public      $price;
    public      $condition_id;
    public      $weight_kg = 0.0;
    // construct magic method to initialize properties
    // at object creation
    public function __construct($args = [])
    {
        $this->brand        = $args['brand']        ?? NULL;
        $this->model        = $args['model']        ?? NULL;
        $this->year         = $args['year']         ?? NULL;
        $this->category     = $args['category']     ?? NULL;
        $this->color        = $args['color']        ?? NULL;
        $this->descripion   = $args['description']  ?? NULL;
        $this->gender       = $args['gender']       ?? NULL;
        $this->price        = $args['price']        ?? 20;
        $this->condition_id = $args['condition_id'] ?? NULL;
        $this->weight_kg    = $args['weight_kg']    ?? 0.0;
    }

    public function name()
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    public function price()
    {
        return floatval($this->price);
    }

    public function set_weight_kg($weight)
    {
        $this->weight_kg = $weight;
    }

    public function weight_kg()
    {
        return "{$this->weight_kg} kg's";
    }

    public function set_weight_lbs($weight)
    {
        $this->weight_kg = $weight / 2.204;
    }

    public function weight_lbs()
    {
        $weight_lbs = $this->weight_kg * 2.204;
        return "{$weight_lbs} lbs";
    }

    public function condition()
    {
        $condition_id = $this->condition_id;
        if ($condition_id > 0) {
            return self::CONDITION_OPTIONS[$condition_id];
        }
        return "Unknown";
    }

    protected function validate()
    {
        $this->errors = [];

        if (is_blank($this->brand)) {
            $this->errors[] = "Brand can not be blank";
        }
        if (is_blank($this->model)) {
            $this->errors[] = "Model can not be blank";
        }
        return $this->errors;
    }
}
