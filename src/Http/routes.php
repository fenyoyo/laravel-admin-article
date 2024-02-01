<?php


use Illuminate\Support\Facades\Route;

Route::resource('article', \Intop\Article\Http\Controllers\ArticleController::class);
Route::resource('category', \Intop\Article\Http\Controllers\CategoryController::class);
