<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('ping', function () {
    return("pong");
});

Route::post('/401', 'AuthController@unauthorized')->name('login');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');     
});


//rotas de usuário
Route::post('/create', 'AuthController@create');

Route::group(['prefix' => 'user'], function () {
    Route::get('/', 'UserController@index');
    Route::post('/create', 'UserController@create');
    Route::get('/{id}', 'UserController@show');
    Route::put('/update/{id}', 'UserController@update');
    Route::delete('/delete/{id}', 'UserController@delete');

});

Route::group(['prefix' => 'client'], function () {
    Route::get('/', 'ClientController@index');
    Route::post('/create', 'ClientController@create');
    Route::get('/{id}', 'ClientController@show');
    Route::put('/update/{id}', 'ClientController@update');
    Route::delete('/delete/{id}', 'ClientController@delete');
});

Route::group(['prefix' => 'bike'], function () {
    Route::get('/', 'BikeController@index');
    Route::post('/create', 'BikeController@create');
    Route::get('/{id}', 'BikeController@show');
    Route::put('/update/{id}', 'BikeController@update');
    Route::delete('/delete/{id}', 'BikeController@delete');
});

Route::group(['prefix' => 'plan'], function () {
    Route::get('/', 'PlanController@index');
    Route::post('/create', 'PlanController@create');
    Route::get('/{id}', 'PlanController@show');
    Route::put('/update/{id}', 'PlanController@update');
    Route::delete('/delete/{id}', 'PlanController@delete');
});

Route::group(['prefix' => 'tag'], function () {
    Route::get('/', 'TagController@index');
    Route::post('/create', 'TagController@create');
    Route::get('/{id}', 'TagController@show');
    Route::put('/update/{id}', 'TagController@update');
    Route::delete('/delete/{id}', 'TagController@delete');
});

Route::group(['prefix' => 'search'], function () {
    Route::get('/bike', 'SearchController@searchBike');
    Route::get('/plan', 'SearchController@searchPlan');
    Route::get('/client', 'SearchController@searchClient');
    Route::get('/user', 'SearchController@searchUser');
    Route::get('/tag', 'SearchController@searchTag');
});

