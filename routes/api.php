<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ActivationAccountController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\CategoriesControllerResource;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeneralServiceController;
use App\Http\Controllers\SubjectsControllerResource;
use App\Http\Controllers\SubjectsVideosControllerResource;
use App\Http\Controllers\SubscriptionsControllerResource;
use App\Http\Controllers\VideoViewController;
use App\Http\Controllers\BillsControllerResource;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MediaControllerResource;
use App\Http\Controllers\UniversitiesControllerResource;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>'changeLang'],function (){
    // auth module
    Route::group(['prefix'=>'/auth'],function (){
        Route::post('/login',[LoginController::class,'login']);
        Route::post('/activate-account',[ActivationAccountController::class,'index']);
        Route::post('/register',[RegisterController::class,'register']);
        Route::post('/forget-password',[ForgetPasswordController::class,'index']);
        Route::post('/new-password',[ForgetPasswordController::class,'new_password']);
        Route::post('/logout',[RegisterController::class,'logout']);
        Route::post('/me',[LoginController::class,'get_user_by_token']);
        Route::post('/csrf',[LoginController::class,'getToken']);
    });

    // get users
    Route::group(['prefix'=>'/users','middleware'=>'auth:sanctum'],function (){
        Route::get('/',[UsersController::class,'index']);
    });



    // resources
    Route::resources([

        'categories'=>CategoriesControllerResource::class,
        'media'=>MediaControllerResource::class,
        'subjects'=>SubjectsControllerResource::class,
        'subjects-videos'=>SubjectsVideosControllerResource::class,
        'mediaviews'=>SubscriptionsControllerResource::class,
        'bills'=>BillsControllerResource::class,
    ]);




    Route::post('/deleteitem',[GeneralServiceController::class,'delete_item']);

});

