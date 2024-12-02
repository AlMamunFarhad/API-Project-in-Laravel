<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::view('/view/posts', 'viewPosts')->name('viewPosts');
Route::view('/add/post', 'add_post')->name('addPost');
