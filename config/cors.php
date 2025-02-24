<?php

return [

    'paths' => ['api/*'], // Aplica CORS a rutas API

    'allowed_origins' => [
        'https://mi-frontend.com', // Reemplaza con el dominio de tu frontend de producción
        'http://localhost:5174',
        'http://localhost:5173',
        'https://avances.logosperu.com'   // Para desarrollo local (si lo necesitas)
    ],

    'allowed_origins_patterns' => [],

    'allowed_methods' => ['POST', 'GET', 'OPTIONS', 'PUT', 'DELETE', 'PATCH'], // Métodos comunes para APIs REST

    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'], // Cabeceras comunes

    'exposed_headers' => [],

    'max_age' => 86400, // 24 horas de caché para preflight

    'supports_credentials' => true, // Si necesitas enviar cookies/credenciales en peticiones cross-origin

];