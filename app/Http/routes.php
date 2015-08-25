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

	Route::group(array('middleware' => 'csrf'), function(){

		Route::post('/create', [
			'as'  => 'account.create-post',
			'uses' => 'AccountController@postCreate'
		]);
	});
});