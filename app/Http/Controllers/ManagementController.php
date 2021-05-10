<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;



class ManagementController extends Controller
{
    /**
     * 商品情報の一覧
     *
     * @param  $request
     * @return view
     */
    public function index(Request $request)
    {
        $products = Product::all();
        $companies = Company::all();
        $price = 0;
        $stock = 0;
        foreach($products as $product){
            if($price < $product->price){
                $price = $product->price;
            }
        }
        foreach($products as $product){
            if($stock < $product->stock){
                $stock = $product->stock;
            }
        }
        return view('management.index',compact('products'))
        ->with('companies',$companies)->with('price',$price)
        ->with('stock',$stock);
    }

    /**
     * 商品情報登録の表示
     *
     * @return view
     */
    public function create()
    {
        $companies = Company::all();
        return view('management.create',['companies' => $companies]);
    }

    /**
     * 商品情報登録
     *
     * @param  $request
     * @return view
     */
    public function store(ProductRequest $request)
    {
        $inputs = new Product();
        $inputs->company_id = $request->company_id;
        $inputs->product_name = $request->product_name;
        $inputs->price = $request->price;
        $inputs->stock = $request->stock;
        $inputs->comment = $request->comment;
        if($request->product_image){
            $path = $request->file('product_image')->store('public');
            $file_name = basename($path);
            $inputs->product_image = $file_name;
        }

        \DB::beginTransaction();
        try{
            $inputs->save();
            \DB::commit();
        } catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
        \Session::flash('err_msg','商品情報を登録しました。');
        return redirect(route('managements'));
    }

    /**
     * 商品情報の詳細
     *
     * @param  int  $id
     * @return view
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(empty($product->id)){
            //エラーメッセージを送る処理
            \Session::flash('err_msg','データがありません。');
            return redirect(route('managements'));
        }
        return view('management.show',['product' => $product]);
    }

    /**
     * 商品情報編集の表示
     *
     * @param  int  $id
     * @return view
     */
    public function edit($id)
    {
        $product = Product::find($id);
        if(empty($product->id)){
            //エラーメッセージを送る処理
            \Session::flash('err_msg','データがありません。');
            return redirect(route('managements'));
        }
        $companies = Company::all();
        return view('management.edit',['product' => $product],['companies' => $companies]);
    }

    /**
     * 商品情報の更新
     *
     * @param  Request  $request
     * @return view
     */
    public function update(ProductRequest $request)
    {
        \DB::beginTransaction();
        try{
            $product = Product::find($request->id);
            if($request->product_image){
                $path = $request->file('product_image')->store('public');
                $file_name = basename($path);
                $product->fill(['product_image' => $file_name]);
            }
            $product->fill([
                'company_id' => $request->company_id,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
            ]);
            $product->save();
            \DB::commit();
        } catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
        \Session::flash('err_msg','商品情報を更新しました。');
        return redirect(route('managements'));
    }

    /**
     * 商品情報の削除
     *
     * @param  int  $id
     * @return view
     */
    public function destroy($id)
    {
        if(empty($id)){
            //エラーメッセージを送る処理
            \Session::flash('err_msg','データがありません。');
            return redirect(route('managements'));
        }
        try{
            Product::destroy($id);
        } catch(\Throwable $e){
            abort(500);
        }
        \Session::flash('err_msg','削除しました。');
        $products = Product::all();
        $json[] = $products;
        $companies = Company::all();
        $json[] = $companies;
        return response()->json($json);
    }


    /**
     * 商品情報の検索
     *
     * @param  $request
     * @return $json
     */
    public function getKeyword($keyword,$search_id,$min_price,$max_price,$min_stock,$max_stock){
        $query = Product::query();
        $products = $query->where('product_name', 'LIKE','%'.$keyword.'%')
        ->orWhere('company_id', $search_id)->orWhereBetween('price', [$min_price, $max_price])
        ->orWhereBetween('stock', [$min_stock, $max_stock])->get();

        $json[] = $products;
        $companies = Company::all();
        $json[] = $companies;
        return response()->json($json);
    }


   /**
     * 商品情報のソート
     *
     * @param  $get_sort, $sort_list
     * @return $products
     */
    public function getSort($get_sort, $sort_list){
        $query = Product::query();
        if($get_sort == 'asc'){
            $products = $query->orderBy($sort_list, 'desc')->get();
        }
        if($get_sort == 'desc'){
            $products = $query->orderBy($sort_list, 'asc')->get();
        }
        $json[] = $products;
        $companies = Company::all();
        $json[] = $companies;
        return response()->json($json);
    }


    /**
     * 商品の決済
     *
     * @param  int  $id $request
     * @return view
     */
    public function pay(Request $request, $id)
    {
        $product = Product::find($id);
        if($product->stock > 0){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $charge = Charge::create(array(
                'amount' => $product->price,
                'currency' => 'jpy',
                'source'=> request()->stripeToken,
            ));

            $product->stock--;
            $product->save();
            $sale = new Sale();
            $sale->product_id = $product->id;
            $sale->save();
            \DB::commit();

        }else{
            \Session::flash('err_msg','商品の在庫がありません。');
        }
        return redirect(route('show', ['id'=> $product->id]));
    }

}
