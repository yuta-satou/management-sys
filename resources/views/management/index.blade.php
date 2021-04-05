@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
    <h2>商品一覧</h2>
    <a href="{{ route('create') }}">商品登録画面</a>

    @foreach($products as $product)
        <p>{{ $product->product_name }}</p>
        <p>{{ $product->company->company_name }}</p>
        <p>{{ $product->price }}</p>
        <p>{{ $product->stock }}</p>
        <p>{{ $product->comment }}</p>
        <img src="{{ asset('storage/' . $product['product_image']) }}" width="250" height="200">
        <button type="button" class="btn btn-primary" onclick="location.href='management/{{ $product->id }}'">詳細</button>
        <form method="POST" action="{{ route('destroy',$product->id) }}" onSubmit="return checkDelete()">
            @csrf
            <button type="submit" class="btn btn-primary" onclick=>削除</button>
        </form>
    @endforeach
@endsection
