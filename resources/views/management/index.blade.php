@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
    <div class="text-md-center">
        <h2>商品一覧</h2>
        <a href="{{ route('create') }}">商品新規登録画面</a>
    </div>
    <div class="text-md-center">
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
    </div>
    <div class="text-md-center">
        @if (session('err_msg'))
            <p class="text-danger">
                {{ session('err_msg') }}
            </p>
        @endif
        <table class="table table-bordered table-striped">
            <tr>
                <th>商品名</th>
                <th>メーカー</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>コメント</th>
                <th>画像</th>
                <th></th>
                <th></th>
            </tr>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->company->company_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->comment }}</td>
                <td><img src="{{ asset('storage/' . $product['product_image']) }}" width="150" height="100"></td>
                <td><button type="button" class="btn btn-primary" onclick="location.href='management/{{ $product->id }}'">詳細</button></td>
                <td>
                    <form method="POST" action="{{ route('destroy',$product->id) }}" onSubmit="return checkSubmit(delete_msg)">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick=>削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

@endsection
