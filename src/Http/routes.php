<?php

use Ghost\Backup\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('backup', Controllers\DcatBackupController::class.'@index');

Route::get('backup/download', Controllers\DcatBackupController::class.'@download')->name('backup.download');
Route::post('backup/run', Controllers\DcatBackupController::class.'@run');
Route::delete('backup/delete', Controllers\DcatBackupController::class.'@delete');
