@extends('layouts.app')
@section('title', '商品一覧')

@section('js')
<script src="{{ asset('js/view.js') }}" defer></script>
@endsection

@section('content')
    <div class="text-md-center">
        <h2 class="title">商品一覧</h2>
    </div>
    <div class="text-md-right"><a href="{{ route('create') }}">商品新規登録画面</a></div>
    <div class="text-md-center">
        <input type="text" name="keyword" placeholder="キーワードを入力" id="keyword">
        <select name="company_id" id="company_id">
            <option selected="selected" value="0">選択してください</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
        </select>
        <select name="min_price" id="min_price">
            <option selected="selected" value="0">価格下限</option>
            @for ($i = 1; $i < $price; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <select name="max_price" id="max_price">
            <option selected="selected" value="0">価格上限</option>
            @for ($i = 2; $i <= $price; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        <select name="min_stock" id="min_stock">
            <option selected="selected" value="0">在庫下限</option>
            @for ($i = 1; $i < $stock; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <select name="max_stock" id="max_stock">
            <option selected="selected" value="1">在庫上限</option>
            @for ($i = 2; $i <= $stock; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        <button type="button" class="btn btn-primary" id="get_product">
            検索
        </button>
    </div>
    <div class="text-md-center">
        @if (session('err_msg'))
            <p class="text-danger">
                {{ session('err_msg') }}
            </p>
        @endif
        <table class="table table-bordered table-striped" id="table">
            <tr>
                <th id="sort_product" data-value="asc">商品名</th>
                <th id="sort_company" data-value="asc">メーカー</th>
                <th id="sort_price" data-value="asc">価格</th>
                <th id="sort_stock" data-value="asc">在庫数</th>
                <th>コメント</th>
                <th>画像</th>
                <th></th>
                <th></th>
            </tr>
            @foreach($products as $product)
            <tr id="product-tr">
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->company->company_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @if ($product->comment)
                        {{ $product->comment }}
                    @else
                        なし
                    @endif
                </td>
                <td>
                    @if ($product->product_image)
                        <img src="{{ asset('storage/' . $product['product_image']) }}" width="150" height="100">
                    @else
                        画像なし
                    @endif
                </td>
                <td><button type="button" class="btn btn-primary" onclick="location.href='management/{{ $product->id }}'">詳細</button></td>
                <td>
                    <button type="button" class="btn btn-primary" id="deleteTarget" data-id="{{ $product->id }}">削除</button>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection

