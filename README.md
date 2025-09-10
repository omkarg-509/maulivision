This is my own web site 

## Database Migrations & Seeders

Lightweight file-based migrations and seeders are included.

### Run migrations
php migrate.php

Creates the `migrations` table (if missing) and applies any new files in `database/migrations`.

### Run seeders
php seed.php

Executes each seeder in `database/seeders` (idempotent logic inside each seeder prevents duplicates).

### Creating a new migration
1. Add a timestamped file to `database/migrations`, e.g. `20250910_create_example_table.php`.
2. Return an anonymous class with `up(mysqli $db)` and optional `down(mysqli $db)`.

### Creating a new seeder
1. Add a file to `database/seeders`, returning an anonymous class with `run(mysqli $db)`.
2. Add idempotency checks (e.g., `SELECT 1 FROM table LIMIT 1`).

### Example migration skeleton
```php
<?php
return new class {
	public function up(mysqli $db){
		$db->query("CREATE TABLE example (id INT AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB");
	}
	public function down(mysqli $db){
		$db->query("DROP TABLE IF EXISTS example");
	}
};
```

### Example seeder skeleton
```php
<?php
return new class {
	public function run(mysqli $db){
		$r = $db->query("SELECT id FROM example LIMIT 1");
		if($r && $r->num_rows) return; // already seeded
		$db->query("INSERT INTO example VALUES ()");
	}
};
```

### Notes
- Migrations run only once; tracked in `migrations` table.
- Seeders always run; each should guard against duplicates.
- Database credentials come from `core/Database.php` (can override with env vars: DB1_*, DB2_*).
