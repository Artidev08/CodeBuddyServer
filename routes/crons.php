<?php
use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Route;

//General Crons
Route::group(['prefix' => 'cron'], function () {
    Route::get('update-wallet/UW7638', [CronController::class,'updateWallet'])->name('update-wallet');

    Route::get('post-blogs/PB6780', [CronController::class,'postBlogs'])->name('post-blogs');
});