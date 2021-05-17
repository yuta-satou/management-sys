@extends('layouts.app')
@section('title', '商品登録フォーム')
@section('content')
    <div class="offset-sm-5">
        <h2>商品登録フォーム</h2>
    </div>
    <div class="offset-sm-3">
        <div class="col-md-11 col-md-offset-1">
            <form method="POST" action="{{ route('store') }}" onSubmit="return checkSubmit(create_msg)" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="product_name">
                        商品名
                    </label>
                    <input
                        id="product_name"
                        name="product_name"
                        class="form-control"
                        value="{{ old('product_name') }}"
                        type="text">
                    @if ($errors->has('product_name'))
                        <div class="text-danger">
                            {{ $errors->first('product_name') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="company_id">
                        メーカー名
                    </label>
                    <select name="company_id">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('company_id"'))
                        <div class="text-danger">
                            {{ $errors->first('company_id"') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="price">
                        価格
                    </label>
                    <input
                        id="price"
                        name="price"
                        class="form-control"
                        value="{{ old('price') }}"
                        type="text">
                    @if ($errors->has('price'))
                        <div class="text-danger">
                            {{ $errors->first('price') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="stock">
                        在庫数
                    </label>
                    <input
                        id="stock"
                        name="stock"
                        class="form-control"
                        value="{{ old('stock') }}"
                        type="text">
                    @if ($errors->has('stock'))
                        <div class="text-danger">
                            {{ $errors->first('stock') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="comment">
                        コメント
                    </label>
                    <textarea
                        id="comment"
                        name="comment"
                        class="form-control"
                        rows="4">{{ old('comment') }}
                    </textarea>
                </div>
                <div class="form-group">
                    <label for="product_image">
                        商品画像
                    </label>
                    <input type="file" id="product_image" name="product_image" class="form-control">
                </div>
                <div class="mt-5">
                    <a class="btn btn-secondary" href="{{ route('managements') }}">
                        キャンセル
                    </a>
                    <button type="submit" class="btn btn-primary">
                        投稿する
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
