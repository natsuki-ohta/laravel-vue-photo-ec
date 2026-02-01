<?php

return [
  'paths' => ['api/*', 'login', 'logout', 'sanctum/csrf-cookie'],

  'allowed_methods' => ['*'],

  'allowed_origins' => [
  'http://localhost:5173',
  'http://163.44.118.25',
],

  'allowed_headers' => ['*'],

  'supports_credentials' => true,
];