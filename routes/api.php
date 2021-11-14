<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Test Routes
Route::get('/test/{id}/{email}','UserController@test');

// User Controller
// ========================================================
Route::post('/register','UserController@register');
Route::get('/fakeUser','UserController@getFakeUser');
Route::post('/login','UserController@login');
Route::get('/basicEmail','UserController@basicEmail');
Route::get('/htmlEmail','UserController@htmlEmail');
Route::get('/htmlWithAttachmentEmail','UserController@htmlWithAttachmentEmail');
Route::get('/getUserDetails/{id}/{email}','UserController@getUserDetails');


// Product Controller
// ========================================================
Route::post('/addProduct','ProductController@addProduct');
Route::get('/listProduct','ProductController@listProduct');
Route::delete('/deleteProduct/{id}','ProductController@deleteProduct');
Route::get('/getProduct/{id}','ProductController@getProduct');
Route::post('/updateProduct','ProductController@updateProduct');
Route::get('/searchProduct/{term}','ProductController@searchProduct');
Route::get('/listActiveProduct','ProductController@listActiveProduct');
Route::post('/payProduct','ProductController@payProduct');


// Lead Controller
// ========================================================
Route::post('/addLead','LeadController@addLead');
Route::get('/listLead','LeadController@listLead');
Route::delete('/deleteLead/{id}','LeadController@deleteLead');
Route::get('/getLead/{id}','LeadController@getLead');
Route::post('/getLeadForm','LeadController@getLeadForm');
Route::post('/updateLead','LeadController@updateLead');
Route::post('/searchLead','LeadController@searchLead');
Route::get('/exportLead','LeadController@exportLead');
Route::get('/downloadLead/{stage}-{status}-{term}','LeadController@downloadLead');
Route::post('/importLead','LeadController@importLead');
Route::get('/sendScheduleEmail','LeadController@sendScheduleEmail');






