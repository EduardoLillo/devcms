<?php 

Route::prefix('/admin')->group(function(){
	Route::get('/','Admin\DashboardController@getDashboard')->name('dashboard');

	//MODULE USERS
	Route::get('/users/{status}','Admin\UserController@getUsers')->name('users_list');
	Route::get('/user/{id}/edit','Admin\UserController@getUserEdit')->name('user_edit');
	Route::get('/user/{id}/banned','Admin\UserController@getUserBanned')->name('user_banned');

	//MODULE PRODUCTS
	Route::get('/products','Admin\ProductController@getHome')->name('products');
	Route::get('/product/add','Admin\ProductController@getProductAdd')->name('product_add');
	Route::post('/product/add','Admin\ProductController@postProductAdd')->name('product_add');
	Route::get('/product/{id}/edit','Admin\ProductController@getProductEdit')->name('product_edit');
	Route::post('/product/{id}/edit','Admin\ProductController@postProductEdit')->name('product_edit');
	Route::post('/product/{id}/gallery/add','Admin\ProductController@postProductGalleryAdd')->name('product_gallery_add');
	Route::get('/product/{id}/gallery/{gid}/delete','Admin\ProductController@getProductGalleryDelete')->name('product_gallery_delete');
	// MODULO CATEGORIES

	Route::get('/categories/{module}','Admin\CategoryController@getHome')->name('categories');
	Route::post('/category/add','Admin\CategoryController@postCategoryAdd')->name('category_add');
	Route::get('/category/{id}/edit','Admin\CategoryController@getCategoryEdit')->name('category_edit');
	Route::post('/category/{id}/edit','Admin\CategoryController@postCategoryEdit')->name('category_edit');
	Route::get('/category/{id}/delete','Admin\CategoryController@getCategoryDelete')->name('category_delete');
});

 