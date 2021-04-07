@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
    <h2>商品一覧</h2>
    <a href="{{ route('create') }}">商品新規登録画面</a>

    <h2>商品検索</h2>
    <form method="GET" action="{{ route('managements') }}">
        <input type="text" name="keyword" placeholder="キーワードを入力" >
        <select name="company_id">
            <option selected="selected" value="0">選択してください</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">
            検索
        </button>
    </form>
    @if (session('err_msg'))
        <p class="text-danger">
            {{ session('err_msg') }}
        </p>
    @endif

    @foreach($products as $product)
        <p>商品名：{{ $product->product_name }}</p>
        <p>メーカー：{{ $product->company->company_name }}</p>
        <p>価格：{{ $product->price }}</p>
        <p>在庫数：{{ $product->stock }}</p>
        <p>コメント：{{ $product->comment }}</p>
        <img src="{{ asset('storage/' . $product['product_image']) }}" width="250" height="200">
        <button type="button" class="btn btn-primary" onclick="location.href='management/{{ $product->id }}'">詳細</button>
        <form method="POST" action="{{ route('destroy',$product->id) }}" onSubmit="return checkSubmit(delete_msg)">
            @csrf
            <button type="submit" class="btn btn-primary" onclick=>削除</button>
        </form>
    @endforeach
@endsection
