<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('CERICAR_DB_DSN') ?: 'pgsql:host=localhost;port=5432;dbname=etd',
    'username' => getenv('CERICAR_DB_USER') ?: '',
    'password' => getenv('CERICAR_DB_PASSWORD') ?: '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
