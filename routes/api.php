<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', 'CommonController@index');
Route::get('open', 'CommonController@open');

Route::post('login', 'JwtAuthController@login')->name('login');
Route::post('register', 'JwtAuthController@register')->name('register');

Route::post('forget-password', 'JwtAuthController@forget_password');
Route::post('reset-password', 'JwtAuthController@reset_password');
Route::post('verify-code', 'JwtAuthController@code_verification');

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::post('logout', 'JwtAuthController@logout');

    Route::get('my-profile', 'CommonController@getMyProfile');
    Route::post('user-update', 'CommonController@userUpdate');

    Route::post('user-profile-image-update', 'CommonController@userProfileImageUpdate');
    Route::post('user-signature-update', 'CommonController@userSignatureUpdate');

    Route::post('academic-create-or-update', 'CommonController@academicInformationsCreateUpdate');
    Route::get('user-academic-information-list', 'CommonController@academicInformationsList');
    
});

