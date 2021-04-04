<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{
    /**
     * 商品情報の一覧
     *
     * @return view
     */
    public function index()
    {
        $products = Product::all();
        return view('management.index', ['products' => $products]);
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
    public function store(Request $request)
    {
        $inputs = $request->all();
        // dd($inputs);
        \DB::beginTransaction();
        try{
            Product::create($inputs);
            \DB::commit();
        } catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
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
        $companies = Company::all();
        return view('management.edit',['product' => $product],['companies' => $companies]);
    }

    /**
     * 商品情報の更新
     *
     * @param  Request  $request
     * @param  int  $id
     * @return view
     */
    public function update(Request $request)
    {
        $inputs = $request->all();
        \DB::beginTransaction();
        try{
            $product = Product::find($inputs['id']);
            $product->fill([
                'company_id' => $inputs['company_id'],
                'product_name' => $inputs['product_name'],
                'price' => $inputs['price'],
                'stock' => $inputs['stock'],
                'comment' => $inputs['comment'],
                'product_image' => $inputs['product_image'],
            ]);
            $product->save();
            \DB::commit();
        } catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
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
        return redirect(route('managements'));
    }
}
