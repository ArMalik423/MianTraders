<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthUser;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;

class ViewerController extends BaseController
{
    public function viewerLegderrs()
    {
        $accounts = Account::with('shop')->get();
        $html = view('viewer._partials._list_ledgers',['accounts' => $accounts])->render();

        return view('viewer.list_ledgers',['list_all_ledgers' => $html]);
    }
    public function shopViewerPayment($shopId){

        $shopId = Crypt::decrypt($shopId);
        $ledgers = Ledger::where('shop_id',$shopId)->with('shop','product')->get();
        $html = view('viewer._partials._list_payment',['ledgers' => $ledgers])->render();

        $quantity = 0;
        $totalCost = 0;
        if($ledgers->count() > 0){
            foreach ($ledgers as $ledger){

                if($ledger->status == '0') {
                    $quantity = $quantity + $ledger->quantity;
                    $totalCost = (int)($totalCost + $ledger->credit);
                }
                else
                    $totalCost = (int)($totalCost - $ledger->debit);
            }
        }

        return view('viewer.list_payments',['list_payments' => $html,'quantity' => $quantity, 'total_cost' =>$totalCost ]);
    }

    public function getViewerProducts()
    {
        $products = Product::all();
        $html = view('viewer._partials._list_products',['products' => $products])->render();

        return view('viewer.products',['list_products' => $html]);
    }

    public function productViewerDetailView($productId)
    {
        $productId = Crypt::decrypt($productId);
        $productDetails = ProductDetail::where('product_id',$productId)->with('product')->get();

        $html = view('viewer._partials._list_product_details',['productDetails' => $productDetails])->render();

        return view('viewer.product_detail_list',['product_detail_list' => $html]);
    }
}
