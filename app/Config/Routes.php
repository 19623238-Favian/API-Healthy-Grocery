<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/ping', function () {
    return json_encode(['status' => 'ok']);
});

$routes->group('api', ['filter' => 'auth'], function ($routes) {
    // $routes->get('recommendation/food', function () {
    //     return 'API OK';
    // });
    $routes->get('recommendation/food', 'RecommendationController::index');
    $routes->post('recommendation/food', 'RecommendationController::recommend');
});