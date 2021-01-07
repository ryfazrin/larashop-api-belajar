<?php

use Illuminate\Http\Request;

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

// Route::get('/book', function ()
// {
//     return new BookResource(Book::find(1));
// });

// dengan prefix /v1/[]
Route::prefix('v1')->group(function () {
    Route::get('books', 'BookController@index');
    Route::get('book/{id}', 'BookController@view')->where('id', '[0-9]+');

    Route::get('categories', function () {
        // match dengan "/v1/categories"
    });

    // bersifat public : siapapun boleh mengakses resourcenya
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    // bersifat private : hanya user yang berwenang saja yang boleh mengakses resourcenya
    Route::middleware('auth:api')->group(function ()
    {
        Route::post('logout', 'AuthController@logout');
    });
});

// 
// -----------------------------------------------------------------------

// Route::get('nama', function () {
//     return 'Namaku, Larashop API';
// });

// // throttle batasan akses mengunjungi
// // Route::middleware('throttle:10,1')->group(function () {
// //     Route::get('buku/{judul}', 'BookController@cetak');
// // });

// // route parameter
// Route::get('category/{id?}', function ($id=null) {
//     $categories = [
//         1 => 'Programming',
//         2 => 'desain grafis',
//         3 => 'jaringan computer',
//     ];
//     $id = (int) $id;
//     if ($id==0) return 'silahkan pilih kategori';
//     else return 'anda memilih kategori <b>'.$categories[$id].'</b>';
// });


// // route Regular Expression (regex)
// Route::get('book/{id}', function () {
//     return 'buku angka';
// })->where('id', '[0-9]+');

// Route::get('book/{title}', function ($title) {
//     return 'buku abjad';
// })->where('id', '[A-Za-z]+');

// // route sub-domain
// Route::domain('{category}.larashop.id')->group(function (){
//     Route::get('book/{id}', function ($category, $id) {
//         // 
//     });
// });