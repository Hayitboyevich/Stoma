installation steps:

- in root dir run "php init" command
- run rbac migration: yii migrate --migrationPath=@yii/rbac/migrations/
- install bower components in backend/web
- run console command  php yii rbac/init
- run console command  php yii init/create-admin
- run console command  php yii init/create-default-configs
- run composer install
- configure common/config/params-local-sample.php
- create backend/uploads folder in backend for media files


IMPORT old database

- create file console/controllers/import-files/import.xlsx
- yii import/patients
- yii import/employees
- yii import/import-patients
- yii import/import-users