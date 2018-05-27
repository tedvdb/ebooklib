<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'EbookController@list');
    Route::get('/downloadcart/', 'EbookController@downloadCart')->name('downloadcart');
    Route::get('/addToCart/{bookid}', 'EbookController@addToCart')->name('addtocart');
    Route::get('/removeFromCart/{bookid}', 'EbookController@removeFromCart')->name('removetocart');

    Route::group(['middleware' => 'role:admin'],function () {
        Route::get('/admin/reindex', 'AdminController@reindex')->name('reindex');
        Route::get('/admin', 'AdminController@index')->name('admin');
    });

    //TODO make links below with OPDS authentication

    Route::get('/download/{id}', 'EbookController@download')->name('download');
    Route::get('/opds', function (Request $request) {
        return response(\App\Ebook::rootCatalog(), 200)
            ->header('Content-Type', 'text/xml')->header('Content-disposition', 'filename="root.xml"');
    })->name('opdsroot');

    Route::get('/opds/category/{category}.xml', function (Request $request, $category) {
        return response(\App\Ebook::catalog($category), 200)
            ->header('Content-Type', 'text/xml');
    })->name('category');

    Route::get('/thumbnail/{bookid}', function(Request $request, $bookid) {
        if(Storage::exists('thumbcovers/'.$bookid)) {
            return Storage::download('thumbcovers/'.$bookid);
        }
        abort(404);
    })->name('thumb');

    Route::get('/coverimage/{bookid}', function(Request $request, $bookid) {
        if(Storage::exists('covers/'.$bookid)) {
            return Storage::download('covers/'.$bookid);
        }
        abort(404);
    })->name('coverimage');


});
