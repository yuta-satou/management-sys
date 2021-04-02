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

        return view('management.index');
    }

    /**
     * 商品情報登録の表示
     *
     * @return view
     */
    public function create()
    {
        return view('management.create');
    }

    /**
     * 商品情報登録
     *
     * @param  $request
     * @return view
     */
    public function store(Request $request)
    {
        //
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
