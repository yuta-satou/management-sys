@extends('layouts.app')
@section('title', '商品情報詳細')
@section('content')
    <h2>商品情報詳細</h2>
    <p>No.{{ $product->id }}</p>
    <p>商品名：{{ $product->product_name }}</p>
    <p>メーカー：{{ $product->company->company_name }}</p>
    <p>価格：{{ $product->price }}</p>
    <p>在庫：{{ $product->stock }}</p>
    <p>コメント：{{ $product->comment }}</p>
    <p>商品画像：</p><img src="{{ $product->product_image }}" alt="画像">
    <button type="button" class="btn btn-primary" onclick="location.href='edit/{{ $product->id }}'">編集</button>
    <a href="{{ route('managements') }}">戻る</a>

@endsection
