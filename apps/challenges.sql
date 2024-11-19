-- テーブルが存在しない場合に作成

DROP TABLE IF EXISTS stage_clear_records;
DROP TABLE IF EXISTS vulnerabilities;


CREATE TABLE IF NOT EXISTS vulnerabilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL,
    alt VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS stage_clear_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vulnerability_id INT NOT NULL,
    level INT NOT NULL,
    description TEXT,
    is_cleared BOOLEAN NOT NULL DEFAULT FALSE,
    cleared_at DATETIME NULL,
    FOREIGN KEY (vulnerability_id) REFERENCES vulnerabilities(id)
);

CREATE TABLE IF NOT EXISTS evaluation_results (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- 一意のID (自動増分)
    level VARCHAR(255) NOT NULL,                 -- テストレベル
    code TEXT NOT NULL,                          -- テスト対象コード
    passed TINYINT(1) NOT NULL,                  -- テスト合否 (1: true, 0: false)
    message TEXT NOT NULL,                       -- レスポンスメッセージ
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- 作成日時 (デフォルトで現在の時刻)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





-- デフォルト値の挿入
INSERT INTO vulnerabilities (name, icon, alt) VALUES
('SQLインジェクション', '../images/injection.png', 'Icon 1')
ON DUPLICATE KEY UPDATE name='SQLインジェクション';

INSERT INTO vulnerabilities (name, icon, alt) VALUES
('CSRF', '../images/certification.jpeg', 'Icon 10')
ON DUPLICATE KEY UPDATE name='CSRF';

INSERT INTO vulnerabilities (name, icon, alt) VALUES
('XSS', '../images/xss.png', 'Icon 6')
ON DUPLICATE KEY UPDATE name='XSS';

INSERT INTO vulnerabilities (name, icon, alt) VALUES
('OSコマンドインジェクション', '../images/injection.png', 'Icon 11')
ON DUPLICATE KEY UPDATE name='OSコマンドインジェクション';

-- デフォルト値の挿入
INSERT INTO stage_clear_records (vulnerability_id, level, is_cleared) VALUES
(1, 1, FALSE),
(1, 2, FALSE),
(1, 3, FALSE),
(1, 4, FALSE),
(2, 1, FALSE),
(2, 2, FALSE),
(3, 1, FALSE),
(3, 2, FALSE),
(3, 3, FALSE),
(3, 4, FALSE),
(4, 1, FALSE),
(4, 2, FALSE),
(4, 3, FALSE);