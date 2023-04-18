<?php

namespace App\Http\Controllers;

use App\Http\Enums\RoleUser as EnumsRoleUser;
use App\Http\Traits\ApiResponse;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\Product;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Crypt;
use Exception;

class AdminController extends Controller
{
    use ApiResponse;
    public function getViewers()
    {
        $users = User::whereHas('roles', function($query) {
            return $query->where('role_id', EnumsRoleUser::Viewer);
        })->get();
        $html = view('admin._partials._viewer',['viewers' => $users])->render();
        return view('admin.viewers', [ 'list_all_viewers' => $html]);
    }

    public function addViewerView()
    {
        return view('admin.add_viewer');
    }

    public function addViewer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $roles = RoleUser::create([
                'user_id' => $user->id,
                'role_id' => EnumsRoleUser::Viewer,
            ]);
        }catch(Exception $e){
            return $this->error('SomeThing Went Wrong. Please Try Again');
        }
        $redirectionRoute = '/viewers';

        return $this->success('Viewer Added Successfully',['redirect_to' => $redirectionRoute]);

    }

    public function getProducts()
    {
        $products = Product::all();
        $html = view('admin._partials._list_products',['products' => $products])->render();

        return view('admin.products',['list_products' => $html]);
    }

    public function addProductView()
    {
        return view('admin.add_product');
    }

    public function addProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'product_code' => ['required','string','min:5','unique:products,product_code'],
            'purchase_price' => ['required', 'integer','min:1'],
            'sale_price' => ['required', 'integer','min:1','gt:purchase_price'],
            'discount' => ['nullable', 'integer','min:1','lt:purchase_price'],
            'quantity' => ['required', 'integer','min:1']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }


        try{
            $product = Product::create([
                'name' => $request->input('name'),
                'user_id' => $this->getAuthUserId(),
                'product_code' => $request->input('product_code'),
                'purchase_price' =>  $request->input('purchase_price'),
                'sale_price' =>  $request->input('sale_price'),
                'discount' =>  $request->input('discount') ?? 0,
                'quantity' =>  $request->input('quantity') ?? 0,
            ]);
        }catch(Exception $e){
            return $this->error('SomeThing Went Wrong. Please Try again');
        }
        $redirectionRoute = '/products';

        return $this->success('Product Added Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function updateProductView($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::where('id',$id)->first();

        return view('admin.update_product',['product'=> $product]);
    }

    public function updatePoduct(Request $request)
    {
        $id = $request->input('product_id');
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'product_code' => 'required|string|min:5|unique:products,product_code,'.$id ,
            'purchase_price' => ['required', 'integer','min:1'],
            'sale_price' => ['required', 'integer','min:1','gt:purchase_price'],
            'discount' => ['nullable', 'integer','min:1','lt:purchase_price'],
            'quantity' => ['required', 'integer','min:1']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        try{
            $product = Product::findOrFail($id);
            $product->name = $request->input('name') ?? $product->name;
            $product->user_id = $this->getAuthUserId() ?? $product->user_id;
            $product->product_code = $request->input('product_code') ?? $product->product_code;
            $product->purchase_price =  $request->input('purchase_price') ?? $product->purchase_price;
            $product->sale_price =  $request->input('sale_price') ?? $product->sale_price;
            $product->discount =  $request->input('discount') ?? $product->discount;
            $product->quantity =  $request->input('quantity') ?? $product->quantity;
            $product->save();

        }catch(Exception $e){
            return $this->error('SomeThing Went Wrong. Please Try again');
        }

        $redirectionRoute = '/products';

        return $this->success('Product Updated Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function deleteProduct($id)
    {
        $product = Product::where('id',$id)->first();
        if(!$product) return $this->error('No Product Found');
        $product->delete();
        $redirectionRoute = '/products';

        return $this->success('Product Deleted Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function getShops()
    {
        $shops = Shop::all();
        $html = view('admin._partials._list_shops',['shops' => $shops])->render();

        return view('admin.shops', ['list_shops' => $html]);
    }

    public function addShopView()
    {
        return view('admin.add_shop');
    }

    public function addShop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:shops,email'],
            'address' => ['nullable', 'string','min:5'],
            'phone_number' => ['nullable','regex:/^([0-9\s\-\+\(\)]*)$/','min:9'],
            'post_code' => ['nullable','string','min:3'],
            'country' => ['nullable', 'string','min:3','max:255']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        try{
            $shop = Shop::create([
                'name' => $request->input('name'),
                'email' => $request->input('email') ?? null,
                'address' => $request->input('address') ?? null,
                'phone_number' => $request->input('phone_number') ?? null,
                'post_code' => $request->input('post_code') ?? null,
                'country' => $request->input('country') ?? null,
            ]);
        }catch(Exception $e){
            return $this->error('Something Went Wrong. Please Try Again');
        }

        $redirectionRoute = '/shops';

        return $this->success('Shop Added Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function updateShopView($id)
    {
        $id = Crypt::decrypt($id);
        $shop = Shop::where('id',$id)->first();

        return view('admin.update_shop',['shop'=> $shop]);
    }

    public function updateShop(Request $request)
    {
        $id = $request->input('shop_id');
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:shops,email,'.$id],
            'address' => ['nullable', 'string','min:5'],
            'phone_number' => ['nullable','regex:/^([0-9\s\-\+\(\)]*)$/','min:9'],
            'post_code' => ['nullable','string','min:3'],
            'country' => ['nullable', 'string','min:3','max:255']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        try{
            $shop = Shop::findOrFail($id);
            $shop->name = $request->input('name') ?? $shop->name;
            $shop->email = $request->input('email') ?? $shop->email;
            $shop->address = $request->input('address') ?? $shop->address;
            $shop->phone_number = $request->input('phone_number') ?? $shop->phone_number;
            $shop->post_code = $request->input('post_code') ?? $shop->phone_number;
            $shop->country = $request->input('country') ?? $shop->country;
            $shop->save();
        }catch(Exception $e){
            return $this->error('Something Went Wrong. Please Try Again');
        }

        $redirectionRoute = '/shops';

        return $this->success('Shop Added Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function deleteShop($id)
    {
        $shop = Shop::where('id',$id)->first();
        if(!$shop) return $this->error('No Shop Found');
        $shop->delete();
        $redirectionRoute = '/shops';

        return $this->success('Product Deleted Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function addLedgerView()
    {
        $products = Product::all();
        $shops = Shop::all();

        return view('admin.add_ledger',['products' => $products, 'shops' => $shops]);
    }

    public function addLedger(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_id' => ['required', 'integer', 'exists:shops,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer','min:1'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }
        DB::beginTransaction();
        try{
            $product = Product::find($request->input('product_id'));
            $quantity = $request->input('quantity');
            $price = $product->sale_price;
            $discount = $product->discount;
            $ledger = new Ledger();
            $ledger->shop_id = $request->input('shop_id');
            $ledger->product_id = $request->input('product_id');
            $ledger->price = $price;
            $ledger->quantity = $quantity;
            $ledger->discount = $discount;
            $ledger->save();

            $account = Account::firstOrNew(['shop_id'=>$request->input('shop_id')]);
            if ($account->exists) {
                // user already exists and was pulled from database.
                $remainingAccount = $account->remaining_amount;
                $amount =(($price*$quantity) - ($quantity*$discount));
                $account->payable_amount =  (int)($account->payable_amount + $amount);
                $account->remaining_amount = $amount + $remainingAccount;
                $account->save();
            } else {
                // user created from 'new'; does not exist in database.
                $remainingAccount = $account->remaining_amount;
                $account->payable_amount = (int)(($price*$quantity) -($quantity*$discount));
                $account->remaining_amount = $account->payable_amount;
                $account->save();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return $this->error('Something Went Wrong. Please Try Again');
        }

        $redirectionRoute = '/ledgers';
        return $this->success('Ledger Record Added Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function getLedgers(){
        $accounts = Account::all();
        $html = view('admin._partials._list_ledgers',['accounts' => $accounts])->render();

        return view('admin.ledgers',['list_ledgers' => $html]);
    }

}
