<?php
Route::group(
/**
 * @author: promise tan
 * @param $route
 */
    [
        'namespace' => 'App\YoSDK\Http',
        'prefix' => 'sdk',
    ], function ($route) {

    $route->post('/login', 'UserWSDL@logined');
    $route->post('/register', 'UserWSDL@registered');
    $route->get('/code', 'UserWSDL@authCode');
});