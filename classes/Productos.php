<?php

namespace dkstore;

use Exception;

class Productos
{

    private array $errorsValidation = [];
    private array $errorsSanitize   = [];
    private array $errorsSystem     = [];

    // Database
    protected static $db;

    // Define DB conection
    public static function setDB($database)
    {
        self::$db = $database;
    }

    // Errores
    protected static array $errores = [];

    public $id;
    public $sku;
    public $product_name;
    public $price;
    public $product_image;
    public $description;
    public $stock_quantity;
    public $low_stock_threshold;
    public $status;
    public $is_deleted;
    public $supplier_id;
    public $category_id;
    public $weight;
    public $dimensions;
    public $cost_price;


    public function __construct($args = [])
    {

        $defaults = [
            'id'              => '',
            'sku'      => '',
            'product_name' => '',
            'price'          => 0.0,
            'product_image'      => '',
            'description'     => '',
            'stock_quantity'      => 0,
            'low_stock_threshold'    => 0,
            'status'       => 'active',
            'is_deleted'      => 0,
            'supplier_id'    => 1,
            'category_id'    => 1,
            'weight' => 0.0,
            'dimensions' => '',
            'cost_price' => 0.0
        ];

        // ⭐ DEBUG: Verifica que $args tenga datos

        error_log("_constructor info: " . print_r($args, true));
        // error_log("_constructor info: " . print_r($args, true) . "\n");

        foreach ($defaults as $prop => $default) {
            $this->$prop = $args[$prop] ?? $default;
        }

        // Normalizar checkbox
        // $this->status$status  = isset($args['status$status']) ? 1 : 0;
        // $this->is_deleted = isset($args['is_deleted']) ? 1 : 0;

        // Normalizar numéricos
        $this->price       = isset($args['price']) ? (float) $args['price'] : 0.0;
        $this->stock_quantity   = isset($args['stock_quantity']) ? (int) $args['stock_quantity'] : 0;
        $this->low_stock_threshold = isset($args['low_stock_threshold']) ? (int) $args['low_stock_threshold'] : 0;

        // Proveedor y categoría
        $this->supplier_id = $args['supplier_id'] ?? 1;
        $this->category_id = $args['category_id'] ?? 1;

        error_log("Created:  '" . $this->sku . ' ' . $this->product_name . "'");
    }

    // Métodos para acumular y consultar errores
    private function addError(string $type, string $message): void
    {
        switch ($type) {
            case 'validation':
                $this->errorsValidation[] = $message;
                break;
            case 'sanitize':
                $this->errorsSanitize[] = $message;
                break;
            case 'system':
                $this->errorsSystem[] = $message;
                break;
        }
    }
    // public function getErrores(?string $type = null): array
    // {
    //     if ($type === 'validation') {
    //         return $this->errorsValidation;
    //     }

    //     if ($type === 'sanitize') {
    //         return $this->errorsSanitize;
    //     }

    //     if ($type === 'system') {
    //         return $this->errorsSystem;
    //     }

    //     // todos juntos
    //     return array_merge($this->errorsValidation, $this->errorsSanitize, $this->errorsSystem);
    // }
    public function getErrores(?string $type = null): array
    {
        return match ($type) {
            'validation' => $this->errorsValidation,
            'sanitize'   => $this->errorsSanitize,
            'system'     => $this->errorsSystem,
            default      => array_merge(
                $this->errorsValidation,
                $this->errorsSanitize,
                $this->errorsSystem
            ),
        };
    }

    // REMOVE  -- Old version
    //  public static function getErrores(): array
    // {
    //     return self::$errores;
    // }
    // END

    // REMOVE --- Desabilitada debido a que resultó vulnerables a SQL injection
    // public function guardarRecord()
    // {

    //     if ($this->sanitize()) {

    //         // insertar en la DB
    //         $query = "INSERT INTO productos (sku,
    //     product_name,
    // price, imagen, description,
    // stock_quantity, low_stock_threshold$low_stock_threshold,
    // activo, is_deleted,
    // supplier_id$supplier_id, category_id$category_id )
    // VALUES ('$this->sku', '$this->product_name',
    // '$this->price', '$this->imagen', '$this->description',
    // '$this->stock_quantity',
    //  $this->low_stock_threshold$low_stock_threshold,
    //  $this->status$status,
    //  $this->is_deleted,
    // '$this->supplier_id$supplier_id', '$this->category_id$category_id' )";

    //         // Save the record
    //         $result = self::$db->query($query);

    //         debugResult($result);
    //     } else {
    //         // Devolver error aqui
    //     }
    // }
    // ++++++
    // + Tu Situación Actual de este bloque (PROBLEMA GRAVE):
    // +
    // +  // VERSIÓN PELIGROSA (la que usas ahora)
    // +  $query = "INSERT INTO productos (...) VALUES ('$this->description', ...)";
    // +  $result = self::$db->query($query);  // ← SQL INJECTION!
    // +  Si alguien envía description = "'; DROP TABLE productos; --", borra  tabla.
    // +
    // +
    // +
    // +++++
    //END BLOCK

    public function guardarRecord(): bool
    {

        try {

            // begin Block  - 99.99% SQLi-PROFF
            $stmt = self::$db->prepare("
            INSERT INTO products (
            sku, product_name, price, product_image, description,
            stock_quantity, low_stock_threshold, status, is_deleted,
            supplier_id, category_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

            // if (! $stmt) {
            //     throw new \Exception("Error preparando el query SQL: " . self::$db->error);
            // }

            // $activo    = $this->status$status ? 1 : 0;
            // $is_deleted = $this->is_deleted ? 1 : 0;

            // Tipos: s = string, d = double, i = integer
            $stmt->bind_param(
                "ssdssiisiii",
                $this->sku,      // s → texto
                $this->product_name, // s → texto
                $this->price,          // d → decimal
                $this->product_image,          // s → texto
                $this->description,     // s → texto
                $this->stock_quantity,      // i → entero
                $this->low_stock_threshold,    // i → entero
                $this->status,       // s
                $this->is_deleted,      // i → tinyint
                $this->supplier_id,    // i → entero
                $this->category_id     // i → entero
            );

            // $result = $stmt->execute();

            if (! $stmt->execute()) {
                // Si falla la ejecución, acumulamos error de sistema
                $this->addError('system', 'Error al guardar el registro en la base de datos.');
                return false;
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            // Capturamos cualquier excepción inesperada
            error_log("[ERROR SISTEMA] " . $e->getMessage());
            $this->addError('system', 'Ocurrió un error interno al guardar el producto.');
            return false;
        }
    }

    private array $sanitizers = [
        'id'              => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
        'sku'      => ['callback' => 'strip_tags'],
        'product_name' => ['callback' => 'strip_tags'],
        'price'          => FILTER_VALIDATE_FLOAT, // ← Era 0?
        'product_image'          => ['callback' => 'strip_tags'],
        'description'     => ['callback' => 'strip_tags'],
        'stock_quantity'      => FILTER_VALIDATE_INT,
        'low_stock_threshold'    => FILTER_VALIDATE_INT,
        'status'       => FILTER_VALIDATE_BOOLEAN,
        'is_deleted'      => FILTER_VALIDATE_BOOLEAN,
        'supplier_id'    => FILTER_VALIDATE_INT,
        'category_id'    => FILTER_VALIDATE_INT,
    ];

    public function sanitize(): bool
    {
        /* // REMOVE   self::$errores = []; // limpiar antes de sanitizar */

        $this->errorsSanitize = []; // limpiar antes de sanitizar

        // 1. VALIDACIONES DE LONGITUD (NUEVO)
        $longitudes = [
            'product_name' => ['max' => 64], // ← AJUSTA según tu esquema
            'description'     => ['min' => 24, 'max' => 1000],
            'sku'      => ['max' => 32],
            'product_image'          => ['max' => 250],
        ];
        foreach ($longitudes as $campo => $limites) {
            if (property_exists($this, $campo) && $this->$campo !== '') {
                $valor = trim((string) $this->$campo);
                $long  = mb_strlen($valor, 'UTF-8');

                if (isset($limites['min']) && $long < $limites['min']) {
                    $this->addError('sanitize', "El campo $campo debe tener al menos {$limites['min']} caracteres.");
                    continue;
                }
                if (isset($limites['max']) && $long > $limites['max']) {
                    $this->addError('sanitize', "El campo $campo no puede exceder {$limites['max']} caracteres.");
                    $this->$campo = mb_substr($valor, 0, $limites['max']);
                    continue;
                }
            }
        }

        /* // Sanitiza descripción, nombre y otros atributos tipo string  */
        if (isset($this->description) && $this->description !== '') {
            [$valido, $resultado] = $this->validardescription($this->description);
            if (! $valido) {
                $this->addError('sanitize', $resultado);
            } else {
                $this->description = strip_tags(trim($resultado)); // Limpia HTML
                $this->revisarIntentos("description", $this->description);
                // $this->validarRegex("description", $this->description);
            }
        }
        // Otras sanitizaciones (ej: product_name)...
        if (isset($this->product_name)) {
            $this->product_name = strip_tags(trim($this->product_name));
            $this->revisarIntentos("product_name", $this->product_name);
            // $this->validarRegex("product_name", $this->product_name);
        }
        /* Codigo SKU */
        if (isset($this->sku)) {
            $this->sku = strip_tags(trim($this->sku));
            $this->revisarIntentos("sku", $this->sku);
            // $this->validarRegex("sku", $this->sku);
        }
        // Validar price
        // if (isset($this->price)) {
        //     $this->validarRegex("price", (string) $this->price);
        // }

        // Validar stock_quantity y stock
        // if (isset($this->stock_quantity)) {
        //     $this->validarRegex("stock_quantity", (string) $this->stock_quantity);
        // }
        // if (isset($this->low_stock_threshold$low_stock_threshold)) {
        //     $this->validarRegex("low_stock_threshold$low_stock_threshold", (string) $this->low_stock_threshold$low_stock_threshold);
        // }

        // Sanitizar nombre y SKU
        $this->product_name = strip_tags(trim($this->product_name ?? ''));
        $this->sku      = strip_tags(trim($this->sku ?? ''));

        // Validar numéricos con regex
        // $this->validarRegex("price", (string) $this->price);
        // $this->validarRegex("stock_quantity", (string) $this->stock_quantity);
        // $this->validarRegex("low_stock_threshold$low_stock_threshold", (string) $this->low_stock_threshold$low_stock_threshold);

        foreach (get_object_vars($this) as $prop => $valor) {
            if (isset($this->sanitizers[$prop]) && $valor !== null) {

                $config = $this->sanitizers[$prop];

                if (isset($config['callback'])) {
                    $this->$prop = call_user_func($config['callback'], trim($valor));
                }
                // ⭐ FIX: Verificar que filter sea válido ANTES
                elseif (isset($config['filter']) && is_int($config['filter']) && $config['filter'] > 0) {
                    $sanitized = filter_var($valor, $config['filter'], $config['options'] ?? []);

                    if ($sanitized !== false) {
                        $this->$prop = $sanitized;
                    }
                    // } else {
                    //     $this->addError('sanitize', "El campo $prop contiene un valor inválido.");
                    // }
                }
            }
        }
        return empty($this->errorsSanitize); // true si todo OK
    }

    public function validardescription(string $description, int $min = 24, int $max = 1000): array
    {
        $description = strip_tags(trim($description));
        $longitud    = mb_strlen($description, 'UTF-8');

        if ($longitud < $min) {
            return [false, "La descripción debe tener al menos {$min} caracteres."];
        }
        if ($longitud > $max) {
            return [false, "La descripción no puede exceder {$max} caracteres."];
        }

        return [true, $description]; // [válido, valor_limpio]
    }

    // Detecta patrones sospechosos en un campo
    private function revisarIntentos(string $campo, string $valor): void
    {
        $patrones = [
            'SELECT',
            'DROP',
            'DELETE',
            'UPDATE',
            'INSERT',
            '<script>',
            '<--',
            '-->',
            'EXEC',
            'SLEEP',
            'BENCHMARK',
            'LOAD_FILE',
            'INTO',
            'OUTFILE',
            'TRUNCATE',
            'onerror=',
            'onload=',
            'javascript:',
            '@param',
            'array',
            '$vars',
            'function',
            'include',
            'bool',
        ];

        foreach ($patrones as $patron) {
            if (stripos($valor, $patron) !== false) {
                // Muestra en consola (error_log)
                error_log("[INTENTO SOSPECHOSO] Campo: {$campo} | Patrón: {$patron} | Valor: {$valor}");

                // También puedes añadirlo a self::$errores si quieres bloquear
                $this->addError('sanitize', "Entrada sospechosa detectada en {$campo} (patrón: {$patron}).");

                // 🚨 Vaciar el campo automáticamente
                $this->$campo = '';
                break; // ya no hace falta seguir revisando
            }
        }
    }
    private function validarRegex(string $campo, string $valor): bool
    {
        $regexMap = [
            'sku'      => '/^[A-Z0-9\-]{3,32}$/',
            'product_name' => '/^[A-Za-zÁÉÍÓÚÑáéíóúñ0-9\s\-\.,]{3,64}$/u',
            'description'     => '/^[A-Za-zÁÉÍÓÚÑáéíóúñ0-9\s\-\.,;:()]{24,1000}$/u',
            'price'          => '/^\d+(\.\d{1,2})?$/',
            'stock_quantity'      => '/^\d+$/',
            'low_stock_threshold'    => '/^\d+$/',
        ];

        if (isset($regexMap[$campo])) {
            if (! preg_match($regexMap[$campo], $valor)) {
                error_log("[VALIDACIÓN FALLIDA] Campo: {$campo} | Valor: {$valor}");

                // Ahora acumulamos en errores de sanitización
                $this->addError('sanitize', "El campo {$campo} contiene caracteres inválidos o no cumple el formato.");

                // Vaciarl el campo para evitar que llegue a la BD
                $this->$campo = '';
                return false;
            }
        }
        return true;
    }

    public function validateEntry(): array
    {

        /* //REMOVE -  self::$errores = []; // limpiar antes de validar */
        $this->errorsValidation = []; // limpiar antes de validar

        if (empty($this->sku)) {
            $this->errorsValidation[] = 'Es obligatorio el código del producto!';
        }

        // validacion
        if (empty($this->product_name)) {
            $this->errorsValidation[] = 'Es obligatorio incluir un nombre';
        }

        // Llama validación de la clase
        [$esValido, $resultado] = $this->validardescription($this->description);
        if (! $esValido) {
            $this->errorsValidation[] = $resultado;
        }
        // } else {
        //     $this->description = $resultado; // Usa versión limpia
        // }
        if (empty($this->description)) {
            $this->errorsValidation[] = 'La descripción es obligatoria';
        }
        if (empty($this->stock_quantity)) {
            $this->errorsValidation[] = 'Es necesario incluir un stock_quantity';
        }
        if (empty($this->price)) {
            $this->errorsValidation[] = 'Es necesario incluir un price';
        }
        if (empty($this->low_stock_threshold)) {
            $this->errorsValidation[] = 'Es necesario incluir un stock minimo del inventario';
        }
        // REMOVE:   suspendido temporalmente
        // if (empty($this->supplier_id$supplier_id)) {
        //     self::$errores[] = 'Es obligatorio incluir el código del proveedor';
        // }
        // if (empty($this->category_id$category_id)) {
        //     self::$errores[] = 'Es obligatorio incluir el código de categoría';
        // }

        // REMOVE
        // if (empty($this->product_image)) {
        //     $this->errorsValidation[] = 'La imagen es obligatoria';
        // }
        if (!isset($this->product_image) || trim($this->product_image) === '') {
            $this->errorsValidation[] = 'La imagen es obligatoria';
        }

        return $this->errorsValidation;
    }




    public function setImage($imagen, $ruta_imagen_anterior)
    {
        if ($imagen) {
            $this->product_image = $imagen;
            error_log("\nImagen nueva agregada -->\n");
            error_log($this->product_image . "\n");
        }
        if (!empty($ruta_imagen_anterior) && file_exists($ruta_imagen_anterior)) {
            unlink($ruta_imagen_anterior);
            error_log("Imagen anterior eliminada -->\n");
            error_log("\n" . $ruta_imagen_anterior . "\n");
        }
    }

    // listar todos los productos
    public static function getAll()
    {
        $sql = "SELECT * from products WHERE is_deleted = 0 ORDER BY updated_at DESC;";

        $listing = self::consultaSQL($sql);

        return $listing;
    }

    public static function getRecordById($id)
    {
        // Busca un registro por su ID

        $sql = "SELECT * FROM products WHERE id = $id AND is_deleted = 0";

        $found = self::consultaSQL($sql);

        // Devuelve el primer elemento del array
        return array_shift($found);
    }

    public static function consultaSQL($sql)
    {
        // consulta la BD
        $result = self::$db->query($sql);

        // iterar sobre los resultados
        $data_array = [];
        while ($record = $result->fetch_assoc()) {
            $data_array[] = self::crearObjeto($record);
        }

        // liberar memoria
        $result->free();

        // devolver resultados
        return $data_array;
    }

    protected static function crearObjeto($record)
    {
        $objeto = new self;

        foreach ($record as $key => $value) {

            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // public function dataBinding($args = [])
    // {
    //     foreach ($args as $key => $value) {
    //         if (property_exists($this, $key) && ! is_null($value)) {
    //             $this->$key = $value;
    //         }
    //     }
    // }

    public function dataBinding($args = [])
    {
        // Asignación genérica
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && ! is_null($value)) {
                $this->$key = $value;
            }
        }

        // Normalización explícita de checkboxes
        $this->status  = isset($args['status']) ? 'active' : 'inactive';
        $this->is_deleted = isset($args['is_deleted']) ? 1 : 0;
        //  **** Para futuras implementacion, considerar estos modelos. ***
        // Radio Buttons
        // $this->tipo = $args['tipo'] ?? 'default';
        // Campos numericos
        // $this->price = isset($args['price']) ? (float)$args['price'] : 0.0;
        // $this->stock  = isset($args['stock']) ? (int)$args['stock'] : 0;
        // *****************************

    }

    public function actualizarRecord(): bool
    {
        try {
            $stmt = self::$db->prepare("
            UPDATE products SET
                sku = ?,
                product_name = ?,
                price = ?,
                product_image = ?,
                description = ?,
                stock_quantity = ?,
                low_stock_threshold = ?,
                status = ?,
                is_deleted = ?,
                supplier_id = ?,
                category_id = ?
            WHERE id = ?
        ");

            if (! $stmt) {
                throw new \Exception("Error preparando el query SQL: " . self::$db->error);
            }

            // Tipos: s = string, d = double, i = integer
            $stmt->bind_param(
                "ssdssiisiiii",
                $this->sku,      // s → texto
                $this->product_name, // s → texto
                $this->price,          // d → decimal
                $this->product_image,          // s → texto
                $this->description,     // s → texto
                $this->stock_quantity,      // i → entero
                $this->low_stock_threshold,    // i → entero
                $this->status,       // s → texto /string
                $this->is_deleted,      // i → tinyint
                $this->supplier_id,    // i → entero
                $this->category_id,    // i → entero
                $this->id               // i → entero (WHERE id=?)
            );

            if (! $stmt->execute()) {
                $this->addError('system', 'Error al actualizar el registro en la base de datos.');
                return false;
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("[ERROR SISTEMA] " . $e->getMessage());
            $this->addError('system', 'Ocurrió un error interno al actualizar el producto.');
            return false;
        }
    }
}
