CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS stores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    manager_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    street TEXT NOT NULL,
    postal_code TEXT NOT NULL,
    city TEXT NOT NULL,
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_stores_name ON stores(name);
CREATE INDEX IF NOT EXISTS idx_stores_manager_name ON stores(manager_name);
CREATE INDEX IF NOT EXISTS idx_stores_postal_code ON stores(postal_code);
CREATE INDEX IF NOT EXISTS idx_stores_city ON stores(city);
CREATE INDEX IF NOT EXISTS idx_stores_created_at ON stores(created_at);