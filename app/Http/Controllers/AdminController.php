<?php

namespace App\Http\Controllers;

use App\Exports\ShopLedgerExport;
use App\Http\Enums\RoleUser as EnumsRoleUser;
use App\Http\Traits\ApiResponse;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\Paid;
use App\Models\Product;
use App\Models\ProductDetail;
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
use Maatwebsite\Excel\Facades\Excel;

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
            return $this->error($e->getMessage());
        }
        $redirectionRoute = '/viewers';

        return $this->success('Viewer Added Successfully',['redirect_to' => $redirectionRoute]);

    }

    public function deleteViewer($id)
    {
        $user = User::where('id',$id)->first();
        if(!$user) return $this->error('No Viewer Found');
        $user->delete();
        $redirectionRoute = '/viewers';

        return $this->success('Viewer Deleted Successfully',['redirect_to' => $redirectionRoute]);
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
            'quantity' => ['required', 'integer','min:1']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }
//        dd($request->all());

        DB::beginTransaction();
        try{
            $product = Product::create([
                'name' => $request->input('name'),
                'user_id' => $this->getAuthUserId(),
                'product_code' => $request->input('product_code'),
                'purchase_price' =>  $request->input('purchase_price'),
                'sale_price' =>  $request->input('sale_price'),
                'quantity' =>  $request->input('quantity') ?? 0,
            ]);
            $sum = 0;
            $oldProductDetails = ProductDetail::where('product_id',$product->id)->get();
            if($oldProductDetails->count() > 0){
                foreach($oldProductDetails as $oldProductDetail){
                    if($oldProductDetail->status == '0'){
                        $sum = $sum + $oldProductDetail->credit;
                    }else{
                        $sum = $sum - $oldProductDetail->debit;
                    }
                }
            }
            $totalCost = (int)($product->quantity*$product->purchase_price);
            $product = ProductDetail::create([
                'product_id' => $product->id,
                'quantity' => $request->input('quantity'),
                'profit' => (int)($product->quantity*($product->sale_price - $product->purchase_price)),
                'status' => '0',
                'credit' => $totalCost,
                'closing_account' => $sum + $totalCost,

            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
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
            'quantity' => ['required', 'integer','min:1']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try{
            $product = Product::findOrFail($id);
            $quantity = $product->quantity;
            $product->name = $request->input('name') ?? $product->name;
            $product->user_id = $this->getAuthUserId() ?? $product->user_id;
            $product->product_code = $request->input('product_code') ?? $product->product_code;
            $product->purchase_price =  $request->input('purchase_price') ?? $product->purchase_price;
            $product->sale_price =  $request->input('sale_price') ?? $product->sale_price;
            $product->quantity =  $request->input('quantity') ?? $product->quantity;
            $product->save();

            $sum = 0;
            $oldProductDetails = ProductDetail::where('product_id',$product->id)->get();
            if($oldProductDetails->count() > 0){
                foreach($oldProductDetails as $oldProductDetail){
                    if($oldProductDetail->status == '0'){
                        $sum = $sum + $oldProductDetail->credit;
                    }else{
                        $sum = $sum - $oldProductDetail->debit;
                    }
                }
            }
            if($quantity > $product->quantity) return $this->error('Your quantity is less then previous stock');
            if($quantity != $product->quantity) {
                $detailQuantity = abs($product->quantity-$quantity);
                $totalCost = (int)($detailQuantity*$product->purchase_price);
                $productDetail = new ProductDetail();
                $productDetail->product_id = $product->id;
                $productDetail->quantity = $detailQuantity ?? '';
                $productDetail->status = '0';
                $productDetail->profit = (int)($detailQuantity*($product->sale_price - $product->purchase_price)) ?? '';
                $productDetail->credit = $totalCost;
                $productDetail->closing_account = $sum + $totalCost;
                $productDetail->save();
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
        }

        $redirectionRoute = '/products';

        return $this->success('Product Updated Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function deleteProduct($id)
    {
        $product = Product::where('id',$id)->with('legders','productDetails')->first();
        if($product->legders->count() > 0 || $product->productDetails->count() > 0) return $this->error("You can not delete this product as it have ledger or product details");
        if(!$product) return $this->error('No Product Found');
        $product->delete();
        $redirectionRoute = '/products';

        return $this->success('Product Deleted Successfully',['redirect_to' => $redirectionRoute]);
    }


    public function payProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'amount' => ['required', 'integer','min:1'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }
        $amount = $request->input('amount');
        $productId = $request->input('product_id');
        DB::beginTransaction();
        try{
            $product = Product::findOrFail($productId);

            $sum = 0;
            $oldProductDetails = ProductDetail::where('product_id',$productId)->get();
            if($oldProductDetails->count() > 0){
                foreach($oldProductDetails as $oldProductDetail){
                    if($oldProductDetail->status == '0'){
                        $sum = $sum + $oldProductDetail->credit;
                    }else{
                        $sum = $sum - $oldProductDetail->debit;
                    }
                }
            }
            $productDetail = new ProductDetail();
            $productDetail->product_id = $product->id;
            $productDetail->status = '1';
            $productDetail->debit = $amount;
            $productDetail->closing_account = $sum - $amount;
            $productDetail->save();

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
        }

        $redirectionRoute = '/products';

        return $this->success('Product Updated Successfully',['redirect_to' => $redirectionRoute]);

    }
    public function productDetailView($productId)
    {
        $productId = Crypt::decrypt($productId);
        $productDetails = ProductDetail::where('product_id',$productId)->with('product')->get();
        foreach ($productDetails as $productDetail) {
            $expense = $productDetail->expense;
            $productDetail['calculate_expense'] = isset($expense) ? (int)($productDetail->profit - $expense) : null;
        }

        $html = view('admin._partials._list_product_details',['productDetails' => $productDetails])->render();

        return view('admin.product_detail_list',['product_detail_list' => $html]);

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
            'address' => ['nullable', 'string','min:5'],
            'phone_number' => ['nullable','regex:/^([0-9\s\-\+\(\)]*)$/','min:9']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        try{
            $shop = Shop::create([
                'name' => $request->input('name'),
                'address' => $request->input('address') ?? null,
                'phone_number' => $request->input('phone_number') ?? null
            ]);
        }catch(Exception $e){
            return $this->error($e->getMessage());
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
            'address' => ['nullable', 'string','min:5'],
            'phone_number' => ['nullable','regex:/^([0-9\s\-\+\(\)]*)$/','min:9']
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        try{
            $shop = Shop::findOrFail($id);
            $shop->name = $request->input('name') ?? $shop->name;
            $shop->address = $request->input('address') ?? $shop->address;
            $shop->phone_number = $request->input('phone_number') ?? $shop->phone_number;
            $shop->save();
        }catch(Exception $e){
            return $this->error($e->getMessage());
        }

        $redirectionRoute = '/shops';

        return $this->success('Shop Added Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function deleteShop($id)
    {
        $shop = Shop::where('id',$id)->with('legders')->first();
        if($shop->legders->count() > 0 ) return $this->error("This shop have ledgers you can not delete it");
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
            $shopId = $request->input('shop_id');
            if($quantity > $product->quantity){
                return $this->error("Order quantity exceeds your product quantity");
            }
            $sum = 0;
            $oldLedgers = Ledger::where('shop_id',$shopId)->get();
            if($oldLedgers->count() > 0){
                foreach($oldLedgers as $oldLedger){
                    if($oldLedger->status == '0'){
                        $sum = $sum + $oldLedger->credit;
                    }else{
                        $sum = $sum - $oldLedger->debit;
                    }
                }
            }
            $price = $product->sale_price;
            $ledger = new Ledger();
            $ledger->shop_id = $request->input('shop_id');
            $ledger->product_id = $request->input('product_id');
            $ledger->price = $price;
            $ledger->quantity = $quantity;
            $ledger->status = '0';
            $ledger->credit = (int)($quantity * $price);
            $ledger->closing_account = $sum + $ledger->credit;
            $ledger->save();

            $product->quantity = (int) ($product->quantity - $quantity);
            $product->save();

            $account = Account::firstOrNew(['shop_id'=>$request->input('shop_id')]);
            if ($account->exists) {
                // user already exists and was pulled from database.
                $remainingAccount = $account->remaining_amount;
                $amount =(int)($price*$quantity);
                $account->payable_amount =  (int)($account->payable_amount + $amount);
                $account->remaining_amount = $amount + $remainingAccount;
                $account->save();
            } else {
                // user created from 'new'; does not exist in database.
                $remainingAccount = $account->remaining_amount;
                $account->payable_amount = (int)(($price*$quantity));
                $account->remaining_amount = $account->payable_amount;
                $account->save();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
        }

        $redirectionRoute = '/ledgers';
        return $this->success('Ledger Record Added Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function getLedgers(){
        $accounts = Account::with('shop')->get();
        $html = view('admin._partials._list_ledgers',['accounts' => $accounts])->render();

        return view('admin.ledgers',['list_ledgers' => $html]);
    }

    public function getChangePasswordView()
    {
        return view('auth.change_password');
    }

    public function postChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'password.confirmed' => 'Password does not match',
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }
        $user = $this->getAuthUser();

        if(!Hash::check($request->input('old_password'),$user->password)){
            return $this->error('Your password does not match with our system');
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $redirectionRoute = '/dashboard';
        return $this->success('Password Changed Successfully',['redirect_to' => $redirectionRoute]);
    }

    public function payLedger(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_id' => ['required', 'integer', 'exists:shops,id'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'amount' => ['required', 'integer','min:1'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }

        Db::beginTransaction();
        try{
            $accountId = $request->input('account_id');
            $amount =  $request->input('amount');
            $account = Account::findOrFail($accountId);
            $account->amount_paid = (int)($account->amount_paid + $amount);
            $account->remaining_amount = (int)($account->remaining_amount - $amount);
            $account->save();

            $sum = 0;
            $shopId = $request->input('shop_id');
            $oldLedgers = Ledger::where('shop_id',$shopId)->get();
            if($oldLedgers->count() > 0){
                foreach($oldLedgers as $oldLedger){
                    if($oldLedger->status == '0'){
                        $sum = $sum + $oldLedger->credit;
                    }else{
                        $sum = $sum - $oldLedger->debit;
                    }
                }
            }

            if($amount > $sum) return $this->error('Your closing account is less than amount entered');
            $ledger = new Ledger();
            $ledger->shop_id = $shopId;
            $ledger->status = '1';
            $ledger->debit = $amount;
            $ledger->closing_account = (int)($sum - $amount);
            $ledger->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }

        $redirectionRoute = '/ledgers';
        return $this->success('Payment Added Successfully',['redirect_to' => $redirectionRoute]);

    }

    public function shopPaymentView($shopId)
    {
        $shopId = Crypt::decrypt($shopId);
        $ledgers = Ledger::where('shop_id',$shopId)->with('shop','product')->get();
        $html = view('admin._partials._list_payment',['ledgers' => $ledgers])->render();

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

        return view('admin.list_payments',['list_payments' => $html,'quantity' => $quantity, 'total_cost' =>$totalCost,'shopId' => $shopId ]);
    }

    public function downloadLedger($id)
    {
        $shop = Shop::where('id', $id)->first();
        $user = User::select('name','email')->get();
        return (Excel::download(new ShopLedgerExport($id),($shop ? $shop->name :'Ledger').'.xlsx'));

    }

    public function addExpense(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'exists:product_details,id'],
            'amount' => ['required', 'integer','min:1'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Failed', ['errors' => $validator->errors()]);
        }
        $productDetails = ProductDetail::where('id',$request->input('product_id'))->first();
        if($productDetails->status == 1) return $this->error("This is a debit Record you can not add expense in it");
        $productDetails->expense = $request->input('amount') ?? '';
        $productDetails->save();
        $redirectionRoute = '/product/detail/'.Crypt::encrypt($productDetails->product_id);

        return $this->success('Expense Added Successfully',['redirect_to' => $redirectionRoute]);
    }
}
