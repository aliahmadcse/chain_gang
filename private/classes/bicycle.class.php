<?php
class Bicycle
{
    // ----Start of active record code----
    protected static $database;
    protected static $db_columns = [
        'id', 'brand', 'model', 'year', 'category', 'color',
        'gender', 'price', 'weight_kg', 'condition_id',
        'description'
    ];
    public $errors = [];

    public static function set_database($database)
    {
        self::$database = $database;
    }

    public static function find_by_sql($sql)
    {
        $result = self::$database->query($sql);
        if (!$result) {
            exit("Database query failed.");
        }
        // results into objects
        $object_array = [];

        while ($record = $result->fetch_assoc()) {
            $object_array[] = self::instantiate($record);
        }

        $result->free();
        return $object_array;
    }

    public static function find_all()
    {
        $sql = "SELECT * FROM bicycles";
        return self::find_by_sql($sql);
    }

    public static function find_by_id($id)
    {
        $sql = "SELECT * FROM bicycles ";
        $sql .= "WHERE id='" . self::$database->escape_string($id) . "'";
        $obj_array = self::find_by_sql($sql);
        if (!empty($obj_array)) {
            // removes and return the first element from the array
            return array_shift($obj_array);
        } else {
            return false;
        }
    }

    public static function instantiate($record)
    {
        $object = new self;
        foreach ($record as $property => $value) {
            if (property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
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

    protected function create()
    {
        $this->validate();
        if (!empty($this->errors)) {
            return false;
        }
        $attributes = $this->sanitized_attributes();

        $sql = "INSERT INTO bicycles (";
        $sql .= join(',', array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("','", array_values($attributes));
        $sql .= "')";

        $result = self::$database->query($sql);
        if ($result) {
            $this->id = self::$database->insert_id;
        }
        return $result;
    }

    protected function update()
    {
        $this->validate();
        if (!empty($this->errors)) {
            return false;
        }
        $attributes = $this->sanitized_attributes();
        $attributes_pairs = [];
        foreach ($attributes as $key => $value) {
            $attributes_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE bicycles SET ";
        $sql .= join(',', $attributes_pairs);
        $sql .= "WHERE id='" . self::$database->escape_string($this->id) . "' ";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        return $result;
    }

    public function save()
    {
        // a new record will not have an id yet
        if (isset($this->id)) {
            return $this->update();
        } else {
            return $this->create();
        }
    }

    public function merge_attributes($args)
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // database columns excluding id
    public function attributes()
    {
        $attributes = [];
        foreach (self::$db_columns as $column) {
            if ($column === 'id') {
                continue;
            }
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    protected function sanitized_attributes()
    {
        $sanitized = [];
        foreach ($this->attributes() as $key => $value) {
            $sanitized[$key] = self::$database->escape_string($value);
        }
        return $sanitized;
    }

    public function delete()
    {
        $sql = "DELETE FROM bicycles ";
        $sql .= "WHERE id='" . self::$database->escape_string($this->id) . "'";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        return $result;
    }
    // ----End of active record code----

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
}
