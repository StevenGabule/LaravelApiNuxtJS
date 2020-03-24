<?php

// public routes
Route::get('me', 'User\MeController@getMe');


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
});
