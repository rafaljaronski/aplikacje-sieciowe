USE asprojektapp;

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