<?php
// Centralized configuration. Override via environment variables on the server.
return [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_user' => getenv('DB_USER') ?: 'u367009900_doorapp',
    'db_pass' => getenv('DB_PASS') ?: '&u9CPI1(dq1mm;JdaQeH',
    'db_name' => getenv('DB_NAME') ?: 'u367009900_doorapp',
];
