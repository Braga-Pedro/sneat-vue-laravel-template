<?php

use App\Http\Controllers\Admin\Users\UserController;

Route::group(['middleware' => ['auth:sanctum', 'module:admin'], 'prefix' => 'admin', 'name' => 'admin.'], function () {

    Route::group(['prefix' => 'users', 'name' => 'users.'], function () {
        Route::get('admin/agents', [UserController::class, 'agentUsers'])->name('admin.users.admin.agents');
        Route::put('admin/activate/{uuid}', [UserController::class, 'activate'])->name('admin.users.admin.activate');
        Route::post('admin/find-by-argument', [UserController::class, 'findByArgument'])->name('admin.users.admin.finder');
        Route::resource('admin', UserController::class, ['names' => 'admin.users.admin']);
    });
});
