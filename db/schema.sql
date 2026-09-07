CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user' CHECK (role IN ('admin', 'user'))
);

CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    image TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at TEXT NOT NULL,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS categories_post (
    post_id INTEGER NOT NULL,
    categories_id INTEGER NOT NULL,
    PRIMARY KEY (post_id, categories_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (categories_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT OR IGNORE INTO categories (title) VALUES
("Development"),
("Web Design"),
("Cybersecurity"),
("Artificial Intelligence"),
("DevOps & Cloud");