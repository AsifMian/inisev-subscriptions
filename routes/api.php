<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//  Note i am ignoring token verificatoin as well as written in txt file of requirement to avoid complexity
// i used santum in couple of projects as user authentication like this
    // Route::group(['middleware'=>['auth:sanctum']],function () {
    // }

Route::prefix("v1")->group(function (){
    Route::post('/create-post',[SubscriptionsController::class, 'create_post'])->name("Create_post");
    Route::post('/subcribe-to-web',[SubscriptionsController::class, 'subcribe_to_website'])->name('list.search');
});

