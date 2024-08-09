<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YouTubeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/youtube',[YouTubeController::class,'index']);
Route::post('/channel-videos', [YouTubeController::class, 'showChannelVideos'])->name('channel.videos');




Route::get('/', function () {
    return view('welcome');
});
