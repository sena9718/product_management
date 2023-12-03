@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報詳細画面</h1>

    <dl class="row mt-3" >
        <dt class="col-sm-3">ID</dt>
        <dd class="col-sm-9">{{ $product->id }}</dd>

        <dt class="col-sm-3">商品画像</dt>
        <dd class="col-sm-9">{{ $product->product_name }}</dd>

        <dt class="col-sm-3">メーカー名</dt>
        <dd class="col-sm-9">{{ $product->company->company_name }}</dd>

        <dt class="col-sm-3">価格</dt>
        <dd class="col-sm-9">{{ $product->price }}</dd>

        <dt class="col-sm-3">在庫数</dt>
        <dd class="col-sm-9">{{ $product->stock }}</dd>

        <dt class="col-sm-3">コメント</dt>
        <dd class="col-sm-9">{{ $product->comment }}</dd>

        <dt class="col-sm-3">商品画像</dt>
        <dd class="col-sm-9"><img src="{{ asset($product->img_path) }}" width="300"></dd>
    </dl>

    <div class="container mt-4">
        <div>
            <button type="button" class="border border-0"><a href="{{ route('products.edit', ['id' => $product->id]) }}" class="btn btn-warning">編集</a></button>
            <button type="button" class="border border-0"><a href="{{ route('products.index') }}" class="btn btn-primary ml-5">戻る</a></button>
        </div>
    </div>
</div>
@endsection