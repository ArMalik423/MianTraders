<?php

namespace Route\Http;

//use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use \Illuminate\Support\Facades\Route;

class Admin
{
    static function register()
    {
        Route::group(['middleware' => ['auth:sanctum','isAdmin']],function(){
            Route::get('/viewers',[AdminController::class,'getViewers'])->name('get.viewers');
            Route::get('/viewer/add',[AdminController::class,'addViewerView'])->name('get.add.viewer');
            Route::post('/viewer/add',[AdminController::class,'addviewer'])->name('post.add.name');
            Route::delete('/viewer/delete/{id}',[AdminController::class,'deleteViewer'])->name('post.delete.viewer');

            Route::get('/products',[AdminController::class, 'getProducts'])->name('get.products');
            Route::get('/product/add', [AdminController::class, 'addProductView'])->name('get.add.product');
            Route::post('/product/add', [AdminController::class, 'addProduct'])->name('post.add.product');
            Route::get('/product/update/{id}', [AdminController::class, 'updateProductView'])->name('get.update.product');
            Route::post('/product/update',[AdminController::class, 'updatePoduct'])->name('post.update.product');
            Route::delete('/product/delete/{id}', [AdminController::class, 'deleteProduct'])->name('delete.product');
            Route::get('/product/detail/{productId}', [AdminController::class, 'productDetailView'])->name('product.detail.view');
            Route::post('/product/pay', [AdminController::class, 'payProduct'])->name('product.pay');

            Route::get('/shops', [AdminController::class, 'getShops'])->name('get.shops');
            Route::get('/shop/add', [AdminController::class, 'addShopView'])->name('get.add.shop');
            Route::post('/shop/add', [AdminController::class, 'addShop'])->name('post.add.shop');
            Route::get('/shop/update/{id}',[AdminController::class, 'updateShopView'])->name('get.update.shop');
            Route::post('/shop/update',[AdminController::class, 'updateShop'])->name('post.update.shop');
            Route::delete('/shop/delete/{id}', [AdminController::class, 'deleteShop'])->name('delete.shop');

            Route::get('/ledgers', [AdminController::class, 'getLedgers'])->name('get.ledgers');
            Route::get('/ledger/add', [AdminController::class, 'addLedgerView'])->name('get.add.ledger');
            Route::post('/ledger/add', [AdminController::class, 'addLedger'])->name('post.add.ledger');
            Route::post('/ledger/delete/{ledger}', [AdminController::class, 'deleteLedger'])->name('delete.ledger');

            Route::post('/ledger/pay', [AdminController::class, 'payLedger'])->name('ledger.pay');
            Route::get('/ledger/shop/payment/{shopId}', [AdminController::class,'shopPaymentView'])->name('shop.payment.view');

            Route::get('/download/ledger/{id}', [AdminController::class, 'downloadLedger'])->name('download.ledger');

            Route::post('/add/expense', [AdminController::class, 'addExpense'])->name('add.expense');
        });

        Route::group(['middleware' => 'auth:sanctum'],function(){
            Route::get('/change/password',[AdminController::class, 'getChangePasswordView'])->name('get.change.password');
            Route::post('/change/password',[AdminController::class, 'postChangePassword'])->name('post.change.password');
        });

    }

}
