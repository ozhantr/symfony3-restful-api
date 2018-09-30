# symfony3-restful-api

How to set up
---
- $ git clone https://github.com/ozhantr/symfony3-restful-api.git

- $ cd docker-mysql
- $ docker-compose up -d

- $ cd symfony3
- $ composer install
- $ php bin/console doctrine:schema:create
- $ php bin/console doctrine:fixtures:load
- $ php bin/console server:run

API
---
`API DOC`
http://127.0.0.1:8000/app_dev.php/api/doc/

5 records available.
http://127.0.0.1:8000/app_dev.php/locations

5 records available.
http://127.0.0.1:8000/app_dev.php/services

No record.
http://127.0.0.1:8000/app_dev.php/jobs

MySQL
---
`USER:`restful

`PASSWORD:` 123456

`PORT:` 3306

`Table Types:` InnoDB

`Character charset:` utf8mb4

`Collate:` utf8mb4_unicode_ci

`phpMyAdmin:` http://127.0.0.1:8080
