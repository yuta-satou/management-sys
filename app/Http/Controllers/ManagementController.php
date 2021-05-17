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
use App\Config;



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
        $price = Product::priceConstruct($products);
        $stock = Product::stockConstruct($products);
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
        $inputs = Product::newProduct($request);
        \DB::beginTransaction();
        try{
            $inputs->save();
            \DB::commit();
        } catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
        \Session::flash('err_msg', config('message.Managements.REGISTRATION_MSG'));
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
            \Session::flash('err_msg', config('message.Managements.DATE_MSG'));
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
            \Session::flash('err_msg', config('message.Managements.DATE_MSG'));
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
            $product = Product::updateProduct($request);
            \DB::commit();
        } catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
        \Session::flash('err_msg', config('message.Managements.UPDATE_MSG'));
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
            \Session::flash('err_msg', config('message.Managements.DATE_MSG'));
            return redirect(route('managements'));
        }
        try{
            Product::destroy($id);
        } catch(\Throwable $e){
            abort(500);
        }
        \Session::flash('err_msg', config('message.Managements.DELETE_MSG'));
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
        $products = Product::Search($keyword,$search_id,$min_price,
                                    $max_price,$min_stock,$max_stock
                                    );
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
        $products = Product::Sort($get_sort, $sort_list);
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
            Sale::newSale($product->id);
            \DB::commit();

        }else{
            \Session::flash('err_msg',config('message.Managements.STOCK_MSG'));
        }
        return redirect(route('show', ['id'=> $product->id]));
    }

}
