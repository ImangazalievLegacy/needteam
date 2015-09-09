<?php

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@getIndex']);

Route::group(array('prefix' => 'files'), function() {

    Route::group(array('prefix' => 'upload'), function() {

        Route::post('image', ['as' => 'upload.image', 'uses' => 'UploadController@uploadImage']);
    });
});
