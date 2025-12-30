-- Insertar usuario normal
INSERT INTO users (username, email, password_hash, role, is_active)
VALUES (
        'usuario1',
        'usuario1@example.com',
        '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghiJKLmnopqr',
        -- hash de ejemplo
        'user',
        TRUE
    );
-- Insertar usuario administrador
INSERT INTO users (username, email, password_hash, role, is_active)
VALUES (
        'admin1',
        'admin1@example.com',
        '$2y$10$zyxwvutsrqponmlkjihgfedcba0987654321abcdefghiJKLmnopqr',
        -- hash de ejemplo
        'admin',
        TRUE
    );