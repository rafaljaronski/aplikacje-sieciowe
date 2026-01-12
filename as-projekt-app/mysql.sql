CREATE DATABASE asprojektapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SHOW DATABASES;
CREATE USER 'appuser'@'localhost' IDENTIFIED BY 'appuserpswrd';
USE asprojektapp;
GRANT ALL PRIVILEGES ON asprojektapp.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'appuser'@'localhost';

-- php artisan tinker --execute="echo DB::connection()->getDatabaseName();"
-- php -r "echo password_hash('admin', PASSWORD_BCRYPT, ['cost' => 12]);"
-- $2y$12$BziWN3PKJ0p1pfpoC7bAbu3sLmHiMRMiqUZvxMCajj542yotyOCNi
-- php -r "echo password_hash('autor', PASSWORD_BCRYPT, ['cost' => 12]);"
-- $2y$12$lAlU4s8a4vuIgKitYuvucuE/T9/JqED2mR.jOMpEdXGPtljJSfTX2
-- php -r "echo password_hash('moderator', PASSWORD_BCRYPT, ['cost' => 12]);"
-- $2y$12$eNLxkW4zGZnclAs0TYjsVe9rtwSjIxWgs5MCLL20xEXUvPjwzHY/e
-- php -r "echo password_hash('czytelnik', PASSWORD_BCRYPT, ['cost' => 12]);"
-- $2y$12$ZJVT.pD/8tJ2yzQ5LJYQ5efDa3SbjCSCMa9QRR8q1hnllh/0upJ.S

-- php -r "echo password_hash('autor2', PASSWORD_BCRYPT, ['cost' => 12]);"
-- $2y$12$2qnE1LCM6CcjtKJ48H57xeqRvngo//TIvenNeuirGVvxQtCm46uXK

-- /d/xampp/mysql/bin/mysql.exe -u appuser -pappuserpswrd asprojektapp < as-projekt-app/mysqlrun.sql

CREATE TABLE user (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    updated_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO user (email, password, first_name, last_name) VALUES
('admin@projekt.pl', '$2y$12$BziWN3PKJ0p1pfpoC7bAbu3sLmHiMRMiqUZvxMCajj542yotyOCNi', 'Aaa', 'Adminnazwisko'),
('autor@projekt.pl', '$2y$12$lAlU4s8a4vuIgKitYuvucuE/T9/JqED2mR.jOMpEdXGPtljJSfTX2', 'Aaa', 'Autornazwisko'),
('moderator@projekt.pl', '$2y$12$eNLxkW4zGZnclAs0TYjsVe9rtwSjIxWgs5MCLL20xEXUvPjwzHY/e', 'Mmm', 'Moderatornazwisko'),
('czytelnik@projekt.pl', '$2y$12$ZJVT.pD/8tJ2yzQ5LJYQ5efDa3SbjCSCMa9QRR8q1hnllh/0upJ.S', 'Ccc', 'Czytelniknazwisko');

INSERT INTO user (email, password, first_name, last_name) VALUES
('autor2@projekt.pl', '$2y$12$2qnE1LCM6CcjtKJ48H57xeqRvngo//TIvenNeuirGVvxQtCm46uXK', 'Aaa2', 'Autor2nazwisko');
INSERT INTO user_role (user_id, role_id) VALUES
(5, 2);


CREATE TABLE role (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    deactivated_at TIMESTAMP NULL DEFAULT NULL,
    deactivated_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (deactivated_by) REFERENCES user(id) ON DELETE SET NULL,
    INDEX idx_name (name),
    INDEX idx_active (deactivated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO role (name) VALUES
('Administrator'),
('Autor'),
('Moderator'),
('Czytelnik');

CREATE TABLE user_role (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    assigned_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES role(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES user(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_role (user_id, role_id),
    INDEX idx_user (user_id),
    INDEX idx_role (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO user_role (user_id, role_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

CREATE TABLE article_status (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE article (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status_id INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (status_id) REFERENCES article_status(id) ON DELETE RESTRICT,
    FOREIGN KEY (author_id) REFERENCES user(id) ON DELETE RESTRICT,
    FOREIGN KEY (reviewed_by) REFERENCES user(id) ON DELETE SET NULL,
    INDEX idx_status (status_id),
    INDEX idx_author (author_id),
    INDEX idx_reviewed_by (reviewed_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO article_status (name, display_status) VALUES
    ('draft', 'Wersja robocza'),
    ('pending', 'Oczekuje na akceptacje'),
    ('approved', 'Zaakceptowany'),
    ('rejected', 'Odrzucony');
