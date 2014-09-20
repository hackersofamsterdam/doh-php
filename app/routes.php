<?php

Route::get('/', function() {
    return Redirect::route('dashboard');
});

Route::group(['prefix' => 'login'], function () {
    Route::get('/', [
        'as'   => 'login.start',
        'uses' => 'LoginController@startAction'
    ]);

    Route::get('authorize/{provider}', [
        'as'   => 'login.authorize',
        'uses' => 'LoginController@authorizeAction'
    ]);

    Route::get('return/{provider}', [
        'as'   => 'login.return',
        'uses' => 'LoginController@returnAction'
    ]);
});

Route::group(['before' => 'auth'], function () {
    Route::get('dashboard', [
        'as'   => 'dashboard',
        'uses' => 'DashboardController@indexAction'
    ]);

    Route::get('products', [
        'as'   => 'products',
        'uses' => 'ProductController@productsAction'
    ]);

    Route::post('product/{id}', [
        'as'   => 'product.update',
        'uses' => 'ProductController@updateProductAction'
    ]);

    Route::get('update_products', [
        'as'   => 'products.update',
        'uses' => 'ProductController@updateProductsAction'
    ]);

    Route::get('provider/product/{provider}/{productId}', [
        'as'   => 'search.product',
        'uses' => 'SearchController@productAction'
    ]);

    Route::get('provider/catalog/{q}', [
        'as'   => 'search.catalog',
        'uses' => 'SearchController@catalogAction'
    ]);
});