<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!\\
|
*/

    //Роут главной страницы
    Route::get('/', [App\Http\Controllers\MainController::class, 'index'])->name('index')->middleware('session');
    //Роут для загрузки файла
    Route::get('/fileDowload/{id}', [App\Http\Controllers\FileController::class, 'fileDowload'])->name('fileDowload')->middleware('session');
    //Роут для скачивания файла
    Route::post('/fileUpload', [App\Http\Controllers\FileController::class, 'fileUpload'])->name('fileUpload')->middleware('session');
    //Роут для удаления 
    Route::delete('/fileDelete', [App\Http\Controllers\FileController::class, 'fileDelete'])->name('fileDelete')->middleware('session');
    //Роут для изменения файла
    Route::post('/editFile', [App\Http\Controllers\FileController::class, 'editFile'])->name('editFile')->middleware('session');
    //Роут авторизации
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    
    //Роут выхода
    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    //Роут админ панели
    Route::post('/adminLogin', [App\Http\Controllers\AdminController::class, 'adminLogin'])->name('adminLogin');
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'admin'])->name('admin')->middleware('admin');
    Route::get('/roleControl', [App\Http\Controllers\AdminController::class, 'roleControl'])->name('roleControl')->middleware('admin');
    Route::get('/userControl', [App\Http\Controllers\AdminController::class, 'userControl'])->name('userControl')->middleware('admin');
    //Страница настроек
    Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings')->middleware('admin');
    //Измененеие пароля
    Route::post('/changePassword', [App\Http\Controllers\AdminController::class, 'changePassword'])->name('changePassword')->middleware('admin');
    //Изменение ключей досупа для yandex ID
    Route::post('/changeYandexIDKey', [App\Http\Controllers\AdminController::class, 'changeYandexIDKey'])->name('changeYandexIDKey')->middleware('admin');
    
    //Страница авторизации
    Route::get('/loginPage', [App\Http\Controllers\AuthController::class, 'loginPage'])->name('loginPage');
    //Страница админестратора
    Route::get('/loginAdmin', [App\Http\Controllers\AdminController::class, 'loginAdmin'])->name('loginAdmin');
    //Добавление пользователя
    Route::post('/addUser', [App\Http\Controllers\AdminController::class, 'addUser'])->name('addUser')->middleware('admin');
    //Удаление пользователя
    Route::delete('/deleteUser', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('deleteUser')->middleware('admin');
    //измеение пользователя
    Route::post('/editUser', [App\Http\Controllers\AdminController::class, 'editUser'])->name('editUser')->middleware('admin');
    //Добавление роли
    Route::post('/addRole', [App\Http\Controllers\AdminController::class, 'addRole'])->name('addRole')->middleware('admin');
    //Удаление роли
    Route::delete('/deleteRole', [App\Http\Controllers\AdminController::class, 'deleteRole'])->name('deleteRole')->middleware('admin');
    //Измененеие роли
    Route::post('/editRole', [App\Http\Controllers\AdminController::class, 'editRole'])->name('editRole')->middleware('admin');

 