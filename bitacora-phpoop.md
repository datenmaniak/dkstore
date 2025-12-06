# Bitácora de migración a programación orientada a objetos

## Características actual del proyecto

-- Basado en PHP
-- Más info en dkstore (Github)

### Primeros pasos

```zsh
composer init
mkdir classes
composer update
composer dump-autoload


```

### Al reasigar un nuevo nombre al 'namespace', esto es necesario:

```zsh

composer dump-autoload


```

### Active record

Crear en base a las columnas de la tabla de productos.
