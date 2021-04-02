@extends('layouts.app')
@section('title', '商品登録フォーム')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2>商品登録フォーム</h2>
            <form method="POST" action="{{ route('store') }}" onSubmit="return checkSubmit()">
                @csrf
                <div class="form-group">
                    <label for="title">
                        商品名
                    </label>
                    <input
                        id="title"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}"
                        type="text"
                    >
                    @if ($errors->has('title'))
                        <div class="text-danger">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="title">
                        メーカー
                    </label>
                    <select name="company_id">
                        <option value="1">sony</option>
                        <option value="2">ダイハツ</option>
                    </select>
                    @if ($errors->has('title'))
                        <div class="text-danger">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="title">
                        価格
                    </label>
                    <input
                        id="title"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}"
                        type="text"
                    >
                    @if ($errors->has('title'))
                        <div class="text-danger">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="title">
                        在庫数
                    </label>
                    <input
                        id="title"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}"
                        type="text"
                    >
                    @if ($errors->has('title'))
                        <div class="text-danger">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="content">
                        コメント
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        class="form-control"
                        rows="4"
                    >{{ old('content') }}</textarea>
                    @if ($errors->has('content'))
                        <div class="text-danger">
                            {{ $errors->first('content') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="content">
                        商品画像
                    </label>
                    <input type="file" id="file" name="file" class="form-control">
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
