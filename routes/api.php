<?php


// Route group for guest only
Route::group(['middleware' => ['guest:api']], static function() {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
//    Route::post('verification/resend', 'Auth\VerificationController@resend');
});


// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], static function() {

});
