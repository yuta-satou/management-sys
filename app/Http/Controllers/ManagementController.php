<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

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
        //商品検索
        $products = self::search($request);
        $companies = Company::all();
        return view('management.index')->with('products',$products)
        ->with('companies',$companies);
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
        return redirect(route('managements'));
    }


    /**
     * 商品情報の検索
     *
     * @param  $request
     * @return $products
     */
    public function search($request){
        $query = Product::query();
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');
        if(!empty($keyword)){
            $query->where('product_name', 'LIKE','%'.$keyword.'%');
        }
        if(!empty($company_id) && $company_id != 0){
            $query->where('company_id', $company_id);
        }
        $products = $query->get();
        return $products;
    }

}
