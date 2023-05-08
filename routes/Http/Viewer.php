<?php

namespace Route\Http;

//use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ViewerController;
use \Illuminate\Support\Facades\Route;

class Viewer
{
    static function register()
    {
        Route::group(['middleware' => ['auth:sanctum','isViewer']],function(){
            Route::get('/viewers/products',[ViewerController::class, 'getViewerProducts'])->name('get.viewer.products');
            Route::get('/viewer/shop/payment/{shopId}', [ViewerController::class,'shopViewerPayment'])->name('viewer.shop.payment');
            Route::get('/viewer/ledgers', [ViewerController::class,'viewerLegderrs'])->name('viewer.ledgers');
            Route::get('/viewer/product/detail/{productId}', [ViewerController::class, 'productViewerDetailView'])->name('product.viewer.detail.view');
        });
    }
}
