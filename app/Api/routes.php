<?php

Route::get('/', ['as' => 'api.home', 'uses' => 'HomeController@getIndex']);