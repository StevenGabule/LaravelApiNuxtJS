<?php

// public routes
Route::get('me', 'User\MeController@getMe');

// get design
Route::get('designs', 'Designs\DesignController@index');

// get users
Route::get('users', 'User\UserController@index');

// Route group for guest only
Route::group(['middleware' => ['guest:api']], static function() {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');
});

// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], static function() {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@updatePassword');

    // upload designs
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');

});
