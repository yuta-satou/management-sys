@extends('layouts.app')
@section('title', '商品情報詳細')
@section('content')
    <div class="text-md-center">
        <h2>商品情報詳細</h2>
        @if (session('err_msg'))
        <p class="text-danger">
            {{ session('err_msg') }}
        </p>
    @endif
    </div>

    <div class="col-md-10 offset-sm-4">
        <h3>No.{{ $product->id }}</h3>
        @if ($product->product_image)
            <img src="{{ asset('storage/' . $product['product_image']) }}" width="450" height="400">
        @else
            <p class="blockquote">画像なし</p>
        @endif
        <p class="blockquote">商品名：{{ $product->product_name }}</p>
        <p class="blockquote">メーカー：{{ $product->company->company_name }}</p>
        <p class="blockquote">価格：{{ $product->price }}</p>
        <p class="blockquote">在庫：{{ $product->stock }}</p>
        <p class="blockquote">コメント：
            @if ($product->comment)
                {{ $product->comment }}
            @else
                なし
            @endif
        </p>
        <button type="button" class="btn btn-primary" onclick="location.href='edit/{{ $product->id }}'">編集</button>
        <a href="{{ route('managements') }}">戻る</a>
        <form action="pay/{{ $product->id }}" method="POST">
            {{ csrf_field() }}
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="{{ env('STRIPE_KEY') }}"
                data-amount="{{ $product->price }}"
                data-name="{{ $product->product_name }}"
                data-label="決済をする"
                data-description="決済しますか"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-currency="JPY">
            </script>
        </form>
    </div>
@endsection
