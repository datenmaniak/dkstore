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

    public function __construct($args = [])
    {

        $defaults = [
            'id'              => '',
            'codigo_sku'      => '',
            'nombre_producto' => '',
            'precio'          => 0.0,
            'imagen'          => '',
            'descripcion'     => '',
            'existencia'      => 0,
            'stock_minimo'    => 0,
            'is_active'       => 1,
            'is_deleted'      => 0,
            'proveedor_id'    => 1,
            'categoria_id'    => 1,
        ];

        // ⭐ DEBUG: Verifica que $args tenga datos

        error_log("=== Constructor invocado ===");
        error_log("Record info: " . print_r($args, true));
        error_log("=== END ===");

        foreach ($defaults as $prop => $default) {
            $this->$prop = $args[$prop] ?? $default;
        }

        // Normalizar checkbox
        $this->is_active  = isset($args['is_active']) ? 1 : 0;
        $this->is_deleted = isset($args['is_deleted']) ? 1 : 0;

        // Normalizar numéricos
        $this->precio       = isset($args['precio']) ? (float) $args['precio'] : 0.0;
        $this->existencia   = isset($args['existencia']) ? (int) $args['existencia'] : 0;
        $this->stock_minimo = isset($args['stock_minimo']) ? (int) $args['stock_minimo'] : 0;

        // Proveedor y categoría
        $this->proveedor_id = $args['proveedor_id'] ?? 1;
        $this->categoria_id = $args['categoria_id'] ?? 1;

        error_log("=== Constructor Output ===");
        error_log("Created:  '" . $this->codigo_sku . ' ' . $this->nombre_producto . "'");

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

        try {

            // begin Block  - 99.99% SQLi-PROFF
            $stmt = self::$db->prepare("
            INSERT INTO productos (
            codigo_sku, nombre_producto, precio, imagen, descripcion,
            existencia, stock_minimo, activo, eliminado,
            proveedor_id, categoria_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

            // if (! $stmt) {
            //     throw new \Exception("Error preparando el query SQL: " . self::$db->error);
            // }

            // $activo    = $this->is_active ? 1 : 0;
            // $eliminado = $this->is_deleted ? 1 : 0;

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

    public function sanitize(): bool
    {
        /* // REMOVE   self::$errores = []; // limpiar antes de sanitizar */

        $this->errorsSanitize = []; // limpiar antes de sanitizar

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
        if (isset($this->descripcion) && $this->descripcion !== '') {
            [$valido, $resultado] = $this->validarDescripcion($this->descripcion);
            if (! $valido) {
                $this->addError('sanitize', $resultado);
            } else {
                $this->descripcion = strip_tags(trim($resultado)); // Limpia HTML
                $this->revisarIntentos("descripcion", $this->descripcion);
                // $this->validarRegex("descripcion", $this->descripcion);
            }
        }
        // Otras sanitizaciones (ej: nombre_producto)...
        if (isset($this->nombre_producto)) {
            $this->nombre_producto = strip_tags(trim($this->nombre_producto));
            $this->revisarIntentos("nombre_producto", $this->nombre_producto);
            // $this->validarRegex("nombre_producto", $this->nombre_producto);
        }
        /* Codigo SKU */
        if (isset($this->codigo_sku)) {
            $this->codigo_sku = strip_tags(trim($this->codigo_sku));
            $this->revisarIntentos("codigo_sku", $this->codigo_sku);
            // $this->validarRegex("codigo_sku", $this->codigo_sku);
        }
        // Validar precio
        // if (isset($this->precio)) {
        //     $this->validarRegex("precio", (string) $this->precio);
        // }

        // Validar existencia y stock
        // if (isset($this->existencia)) {
        //     $this->validarRegex("existencia", (string) $this->existencia);
        // }
        // if (isset($this->stock_minimo)) {
        //     $this->validarRegex("stock_minimo", (string) $this->stock_minimo);
        // }

        // Sanitizar nombre y SKU
        $this->nombre_producto = strip_tags(trim($this->nombre_producto ?? ''));
        $this->codigo_sku      = strip_tags(trim($this->codigo_sku ?? ''));

        // Validar numéricos con regex
        // $this->validarRegex("precio", (string) $this->precio);
        // $this->validarRegex("existencia", (string) $this->existencia);
        // $this->validarRegex("stock_minimo", (string) $this->stock_minimo);

        // $this->is_active  = isset($args['is_active']) ? '1' : '0';
        // $this->is_deleted = isset($args['is_deleted']) ? '1' : '0';

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

    public function validarDescripcion(string $descripcion, int $min = 24, int $max = 1000): array
    {
        $descripcion = strip_tags(trim($descripcion));
        $longitud    = mb_strlen($descripcion, 'UTF-8');

        if ($longitud < $min) {
            return [false, "La descripción debe tener al menos {$min} caracteres."];
        }
        if ($longitud > $max) {
            return [false, "La descripción no puede exceder {$max} caracteres."];
        }

        return [true, $descripcion]; // [válido, valor_limpio]
    }

    // Detecta patrones sospechosos en un campo
    private function revisarIntentos(string $campo, string $valor): void
    {
        $patrones = ['SELECT', 'DROP', 'DELETE', 'UPDATE', 'INSERT',
            '<script>', '--', 'EXEC',
            'SLEEP', 'BENCHMARK', 'LOAD_FILE',
            'INTO', 'OUTFILE',
            'TRUNCATE',
            'onerror=', 'onload=', 'javascript:',
            '@param', 'array',
            '$vars', 'function',
            'include', 'bool',
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
            'codigo_sku'      => '/^[A-Z0-9\-]{3,32}$/',
            'nombre_producto' => '/^[A-Za-zÁÉÍÓÚÑáéíóúñ0-9\s\-\.,]{3,64}$/u',
            'descripcion'     => '/^[A-Za-zÁÉÍÓÚÑáéíóúñ0-9\s\-\.,;:()]{24,1000}$/u',
            'precio'          => '/^\d+(\.\d{1,2})?$/',
            'existencia'      => '/^\d+$/',
            'stock_minimo'    => '/^\d+$/',
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

        if (empty($this->codigo_sku)) {
            $this->errorsValidation[] = 'Es obligatorio el código del producto!';
        }

        // validacion
        if (empty($this->nombre_producto)) {
            $this->errorsValidation[] = 'Es obligatorio incluir un nombre';
        }

        // Llama validación de la clase
        [$esValido, $resultado] = $this->validarDescripcion($this->descripcion);
        if (! $esValido) {
            $this->errorsValidation[] = $resultado;
        }
        // } else {
        //     $this->descripcion = $resultado; // Usa versión limpia
        // }
        if (empty($this->descripcion)) {
            $this->errorsValidation[] = 'La descripción es obligatoria';
        }
        if (empty($this->existencia)) {
            $this->errorsValidation[] = 'Es necesario incluir un existencia';
        }
        if (empty($this->precio)) {
            $this->errorsValidation[] = 'Es necesario incluir un precio';
        }
        if (empty($this->stock_minimo)) {
            $this->errorsValidation[] = 'Es necesario incluir un stock minimo del inventario';
        }
        // REMOVE:   suspendido temporalmente
        // if (empty($this->proveedor_id)) {
        //     self::$errores[] = 'Es obligatorio incluir el código del proveedor';
        // }
        // if (empty($this->categoria_id)) {
        //     self::$errores[] = 'Es obligatorio incluir el código de categoría';
        // }

        if (empty($this->imagen)) {
            $this->errorsValidation[] = 'La imagen es obligatoria';
        }

        /* // REMOVE       return self::$errores; */
        return $this->errorsValidation;
    }

    public function setImage($imagen, $ruta_imagen_anterior)
    {
        if ($imagen) {
            $this->imagen = $imagen;
            error_log("=== Imagen agregada ===");
            error_log($this->imagen);
        }
        if (
            ! empty($ruta_imagen_anterior) && file_exists($ruta_imagen_anterior)
        ) {
            unlink($ruta_imagen_anterior);
            error_log("=== Imagen eliminada ===");
            error_log($ruta_imagen_anterior);

        }
    }

    // listar todos los productos
    public static function getAll()
    {
        $sql = "SELECT * from productos WHERE eliminado = 0";

        $listing = self::consultaSQL($sql);

        return $listing;
    }

    public static function getRecordById($id)
    {
        // Busca un registro por su ID

        $sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";

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

    public function dataBinding($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && ! is_null($value)) {
                $this->$key = $value;
            }
        }
    }
    public function actualizarRecord(): bool
    {
        try {
            $stmt = self::$db->prepare("
            UPDATE productos SET
                codigo_sku = ?,
                nombre_producto = ?,
                precio = ?,
                imagen = ?,
                descripcion = ?,
                existencia = ?,
                stock_minimo = ?,
                activo = ?,
                eliminado = ?,
                proveedor_id = ?,
                categoria_id = ?
            WHERE id = ?
        ");

            if (! $stmt) {
                throw new \Exception("Error preparando el query SQL: " . self::$db->error);
            }

            // Tipos: s = string, d = double, i = integer
            $stmt->bind_param(
                "ssdssiiiiiii",
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
                $this->categoria_id,    // i → entero
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
