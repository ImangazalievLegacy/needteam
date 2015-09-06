<?php

Route::get('/', ['as' => 'api.home', 'uses' => 'HomeController@getIndex']);

Route::group(array('prefix' => 'files'), function() {

    Route::group(array('prefix' => 'upload'), function() {

        Route::post('image', ['as' => 'api.upload.image', 'uses' => 'UploadController@uploadImage']);
    });
});
