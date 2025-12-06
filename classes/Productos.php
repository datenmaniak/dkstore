<?php
namespace dkstore;

class Productos
{

    // Database
    protected static $db;

    // Define DB conection
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public $id;
    public $codigo_sku;
    public $nombre_producto;
    public $precio;
    public $imagen;
    public $descripcion;
    public $existencia;
    public $stock_minimo;
    public $is_active;
    public $is_deleted;
    public $proveedor_id;
    public $categoria_id;

    public function __construct(array $args = [])
    {

        $defaults = [
            'id'              => null,
            'codigo_sku'      => '',
            'nombre_producto' => '',
            'precio'          => 0.0,
            'imagen'          => '',
            'descripcion'     => '',
            'existencia'      => 0,
            'stock_minimo'    => 0,
            'is_active'       => 1,
            'is_deleted'      => 0,
            'proveedor_id'    => null,
            'categoria_id'    => null,
        ];

        // ⭐ DEBUG: Verifica que $args tenga datos
        error_log("Constructor recibe: " . print_r($args, true));

        foreach ($defaults as $prop => $default) {
            $this->$prop = $args[$prop] ?? $default;
        }

        error_log("Producto creado - nombre: '" . $this->nombre_producto . "'");
    }

    // REMOVE --- Desabilitada debido a que resultó vulnerables a SQL injection
    // public function guardarRecord()
    // {

    //     if ($this->sanitize()) {

    //         // insertar en la DB
    //         $query = "INSERT INTO productos (codigo_sku,
    //     nombre_producto,
    // precio, imagen, descripcion,
    // existencia, stock_minimo,
    // activo, eliminado,
    // proveedor_id, categoria_id )
    // VALUES ('$this->codigo_sku', '$this->nombre_producto',
    // '$this->precio', '$this->imagen', '$this->descripcion',
    // '$this->existencia',
    //  $this->stock_minimo,
    //  $this->is_active,
    //  $this->is_deleted,
    // '$this->proveedor_id', '$this->categoria_id' )";

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
    // +  $query = "INSERT INTO productos (...) VALUES ('$this->descripcion', ...)";
    // +  $result = self::$db->query($query);  // ← SQL INJECTION!
    // +  Si alguien envía descripcion = "'; DROP TABLE productos; --", borra  tabla.
    // +
    // +
    // +
    // +++++
    //END BLOCK

    public function guardarRecord(): bool
    {

        if (! $this->sanitize()) {
            return false; // Maneja errores con getErrores()
        }

        // Begin  ********* Antes  ***
        //     $query = "INSERT INTO productos (
        //     codigo_sku, nombre_producto, precio, imagen, descripcion,
        //     existencia, stock_minimo, activo, eliminado,
        //     proveedor_id, categoria_id
        // ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //     $stmt = self::$db->prepare($query);
        // End ***********

        // begin Block  - 99.99% SQLi-PROFF
        $stmt = self::$db->prepare("
            INSERT INTO productos (
            codigo_sku, nombre_producto, precio, imagen, descripcion,
            existencia, stock_minimo, activo, eliminado,
            proveedor_id, categoria_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (! $stmt) {
            throw new \Exception("Error preparando el query SQL: " . self::$db->error);
        }

        $activo    = $this->is_active ? 1 : 0;
        $eliminado = $this->is_deleted ? 1 : 0;

        // Tipos: s = string, d = double, i = integer
        $stmt->bind_param(
            "ssdssiiiiii",
            $this->codigo_sku,      // s → texto
            $this->nombre_producto, // s → texto
            $this->precio,          // d → decimal
            $this->imagen,          // s → texto
            $this->descripcion,     // s → texto
            $this->existencia,      // i → entero
            $this->stock_minimo,    // i → entero
            $this->is_active,       // i → tinyint
            $this->is_deleted,      // i → tinyint
            $this->proveedor_id,    // i → entero
            $this->categoria_id     // i → entero
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    // private array $sanitizers = [
    //     'id'              => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
    //     'codigo_sku'      => ['callback' => 'strip_tags'], // Remueve tags
    //     'nombre_producto' => ['callback' => 'strip_tags'], // ← CAMBIO CLAVE
    //     'precio'          => ['filter' => FILTER_VALIDATE_FLOAT, 'options' => ['min_range' => 0]],
    //     'imagen'          => ['callback' => 'strip_tags'], // Nombres de archivos
    //     'descripcion'     => ['callback' => 'strip_tags'], // ← También aquí
    //     'existencia'      => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0]],
    //     'stock_minimo'    => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 0]],
    //     'is_active'       => FILTER_VALIDATE_BOOLEAN,
    //     'is_deleted'      => FILTER_VALIDATE_BOOLEAN,
    //     'proveedor_id'    => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
    //     'categoria_id'    => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
    // ];

    private array $sanitizers = [
        'id'              => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
        'codigo_sku'      => ['callback' => 'strip_tags'],
        'nombre_producto' => ['callback' => 'strip_tags'],
        'precio'          => FILTER_VALIDATE_FLOAT, // ← Era 0?
        'imagen'          => ['callback' => 'strip_tags'],
        'descripcion'     => ['callback' => 'strip_tags'],
        'existencia'      => FILTER_VALIDATE_INT,
        'stock_minimo'    => FILTER_VALIDATE_INT,
        'is_active'       => FILTER_VALIDATE_BOOLEAN,
        'is_deleted'      => FILTER_VALIDATE_BOOLEAN,
        'proveedor_id'    => FILTER_VALIDATE_INT,
        'categoria_id'    => FILTER_VALIDATE_INT,
    ];

    private array $errores = [];

    public function sanitize(): bool
    {
        $this->errores = [];

        // 1. VALIDACIONES DE LONGITUD (NUEVO)
        $longitudes = [
            'nombre_producto' => ['max' => 64], // ← AJUSTA según tu esquema
            'descripcion'     => ['min' => 24, 'max' => 1000],
            'codigo_sku'      => ['max' => 32],
            'imagen'          => ['max' => 250],
        ];
        foreach ($longitudes as $campo => $limites) {
            if (property_exists($this, $campo) && $this->$campo !== '') {
                $valor = trim((string) $this->$campo);
                $long  = mb_strlen($valor, 'UTF-8');

                if (isset($limites['min']) && $long < $limites['min']) {
                    $this->errores[] = "El campo $campo debe tener al menos {$limites['min']} caracteres.";
                    continue;
                }
                if (isset($limites['max']) && $long > $limites['max']) {
                    $this->errores[] = "El campo $campo no puede exceder {$limites['max']} caracteres.";
                    $this->$campo    = mb_substr($valor, 0, $limites['max']); // CORTA
                    continue;
                }
            }
        }

        // Sanitiza descripción + validación integrada
        if (isset($this->descripcion) && $this->descripcion !== '') {
            [$valido, $resultado] = $this->validarDescripcion($this->descripcion);
            if (! $valido) {
                $this->errores[] = $resultado;
            } else {
                $this->descripcion = strip_tags(trim($resultado)); // Limpia HTML [memory:6]
            }
        }
        // Otras sanitizaciones (ej: nombre_producto)...
        if (isset($this->nombre_producto)) {
            $this->nombre_producto = strip_tags(trim($this->nombre_producto));
        }

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
                }
            }
        }
        return empty($this->errores); // true si todo OK
    }

    // AGREGAR DESPUÉS de sanitize()
    public function getErrores(): array
    {
        return $this->errores;
    }

    public function validarDescripcion(string $descripcion, int $min = 24, int $max = 1000): array
    {
        $descripcion = trim($descripcion);
        $longitud    = mb_strlen($descripcion, 'UTF-8');

        if ($longitud < $min) {
            return [false, "La descripción debe tener al menos {$min} caracteres."];
        }
        if ($longitud > $max) {
            return [false, "La descripción no puede exceder {$max} caracteres."];
        }

        return [true, $descripcion]; // [válido, valor_limpio]
    }

}
