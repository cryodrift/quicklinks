CREATE TABLE IF NOT EXISTS quicklinks
(
    id         INTEGER PRIMARY KEY,
    url        TEXT UNIQUE,
    name       TEXT,
    sortnum    NUMERIC,
    deleted    TEXT,
    changed    NUMERIC,
    created    NUMERIC DEFAULT CURRENT_TIMESTAMP
);


