<?php

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@getIndex']);

Route::group(array('prefix' => 'account'), function(){
    
    Route::get('/create', [
        'as'  => 'account.create',
        'uses' => 'AccountController@getCreate'
    ]);

    Route::get('activate/{code}', array(
        'as'   => 'account.activate',
        'uses' => 'AccountController@getActivate'
    ))->where('code', '[a-zA-Z0-9]+');

    Route::get('/login', [
        'as'  => 'account.login',
        'uses' => 'AccountController@getLogin'
    ]);

    Route::get('/logout', [
        'as'  => 'account.logout',
        'uses' => 'AccountController@getLogout'
    ]);

    Route::group(array('middleware' => 'csrf'), function(){

        Route::post('/create', [
            'as'  => 'account.create-post',
            'uses' => 'AccountController@postCreate'
        ]);

        Route::post('/login', [
            'as'  => 'account.login-post',
            'uses' => 'AccountController@postLogin'
        ]);
    });
});

Route::get('/projects', [
        'as'  => 'projects.show',
        'uses' => 'ProjectController@getIndex'
    ]);

Route::group(array('prefix' => 'project'), function(){
    
    Route::get('/add', [
        'as'  => 'project.add',
        'uses' => 'ProjectController@getCreate'
    ]);

    Route::get('/{id}', array(
        'as'   => 'project.show',
        'uses' => 'ProjectController@getShow'
    ))->where('code', '[a-zA-Z0-9]+');


    Route::group(array('middleware' => 'csrf'), function(){

        Route::post('/create', [
            'as'  => 'project.create-post',
            'uses' => 'ProjectController@postCreate'
        ]);
    });
});
