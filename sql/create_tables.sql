CREATE TABLE votes(
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        VARCHAR(30) NOT NULL,
    conference_id  INT NOT NULL,
    speaker_id     VARCHAR(30) NOT NULL,
    term           INT NOT NULL,
    token          VARCHAR(255) NOT NULL,
    
    created_at  DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at  DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
) Engine=InnoDB CHARSET=utf8mb4;
