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
        // $inputs = new Product;
        // $inputs->company_id = $request->companies()->id;
        // $inputs->product_name = $request->product_name;
        // $inputs->price = $request->price;
        // $inputs->stock = $request->stock;
        // $inputs->comment = $request->comment;
        // $inputs->product_image = $request->product_image;

        // dd($inputs);
        \DB::beginTransaction();
        try{
            Product::create($inputs);
            // $inputs->save();
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
        return view('management.show');
    }

    /**
     * 商品情報編集の表示
     *
     * @param  int  $id
     * @return view
     */
    public function edit($id)
    {
        return view('management.edit');
    }

    /**
     * 商品情報の更新
     *
     * @param  Request  $request
     * @param  int  $id
     * @return view
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 商品情報の削除
     *
     * @param  int  $id
     * @return view
     */
    public function destroy($id)
    {
        //
    }
}
