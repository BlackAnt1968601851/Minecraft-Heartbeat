CREATE TABLE server_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    IP VARCHAR(45) NOT NULL,
    salt VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    users INT NOT NULL,
    max INT NOT NULL,
    is_public BOOLEAN NOT NULL,
    port INT NOT NULL,
    version INT NOT NULL,
    uuid VARCHAR(36),
    UNIQUE (IP)
);
