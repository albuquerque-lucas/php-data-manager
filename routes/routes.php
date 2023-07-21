<?php


$routes = [
  '/' => function () {
    echo "Página principal";
  },
  '/login' => function () {
    echo "Página de login";
  },
];

return $routes;