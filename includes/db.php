<?php
// Database setup - SQLite auto-creation
$db_path = __DIR__ . '/../data/site.db';

$data_dir = dirname($db_path);
if (!is_dir($data_dir)) {
    mkdir($data_dir, 0755, true);
}

try {
    global $db;
    $db = new PDO("sqlite:$db_path");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    $db->exec("CREATE TABLE IF NOT EXISTS agents (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        role TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT,
        phone2 TEXT,
        bio TEXT,
        photo TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS listings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        address TEXT,
        city TEXT,
        county TEXT,
        state TEXT DEFAULT 'MS',
        zip TEXT,
        price REAL,
        beds INTEGER,
        baths REAL,
        sqft INTEGER,
        description TEXT,
        property_type TEXT DEFAULT 'House',
        status TEXT DEFAULT 'active',
        featured INTEGER DEFAULT 0,
        agent_id INTEGER,
        image_1 TEXT,
        image_2 TEXT,
        image_3 TEXT,
        image_4 TEXT,
        image_5 TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (agent_id) REFERENCES agents(id)
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT,
        message TEXT,
        agent_notified INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (listing_id) REFERENCES listings(id)
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        email TEXT UNIQUE,
        password_hash TEXT NOT NULL,
        approved INTEGER DEFAULT 1,
        role TEXT DEFAULT 'admin',
        agent_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (agent_id) REFERENCES agents(id)
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS pending_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        email TEXT NOT NULL,
        password_hash TEXT NOT NULL,
        agent_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Add columns if upgrading existing database
    try { $db->exec("ALTER TABLE users ADD COLUMN agent_id INTEGER"); } catch (Exception $e) {}
    try { $db->exec("ALTER TABLE pending_users ADD COLUMN agent_id INTEGER"); } catch (Exception $e) {}
    try { $db->exec("ALTER TABLE listings ADD COLUMN county TEXT"); } catch (Exception $e) {}
    
    // Create default super admin if not exists
    $check = $db->prepare("SELECT COUNT(*) as c FROM users WHERE username = ? AND role = 'super'");
    $check->execute(['admin']);
    $row = $check->fetch();
    if ($row['c'] == 0) {
        $hash = password_hash('admin', PASSWORD_DEFAULT);
        $ins = $db->prepare("INSERT INTO users (username, email, password_hash, approved, role) VALUES (?, ?, ?, 1, 'super')");
        $ins->execute(['admin', 'mckeerealty@att.net', $hash]);
    }
    
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}