# MediTrack - Interim

## Database Seed : First run only

```bash
mysql -u root -p -e "DROP DATABASE IF EXISTS meditrack;"
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

## PHP Run

```bash
php -S localhost:8000 -t public public/index.php
```
