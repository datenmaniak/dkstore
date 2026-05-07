
# Error

```php
 138   function requireRole($role)
 139   {
 140       // session_start();
 141       if (! isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
 142           header("Location: /admin/no-access.php");
 143           exit;
 144       }
 145   }
```

```plaintext

Warning: session_start(): Session cannot be started after headers have already been sent in /var/www/html/includes/functions.php on line 142

Warning: Cannot modify header information - headers already sent by (output started at /var/www/html/includes/app.php:1) in /var/www/html/includes/functions.php on line 144
```
