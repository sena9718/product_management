@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    {{-- 検索フォーム     --}}
    <div class="search mt-5">

        <!-- 検索フォーム。GETメソッドで、商品一覧のルートにデータを送信 -->
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">

            <!-- 商品名検索用の入力欄 -->
            <div class="col-sm-12 col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="検索キーワード" value="{{ request('keyword') }}">
            </div>
            <!-- メーカー名検索用の入力欄 -->
            <div class="col-sm-12 col-md-4">
                <select class="form-select" name="search-company" value="{{ request('searchCompany') }}" placeholder="メーカーを選択">
                    <option value="" selected>メーカーを選択してください</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>    
            </div>

            <!-- 検索ボタン -->
            <div class="col-sm-12 col-md-1">
                <button class="btn btn-outline-secondary" type="submit">検索</button>
            </div>
        </form>
    </div>

    <div class="products mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th><a href="{{ route('products.create') }}" class="btn btn-warning">新規登録</a></th>                
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company_name }}</td>
                    </td>
                    <td>
                        {{-- 詳細ボタン --}}
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            {{-- 削除ボタン --}}
                            <button type="submit" class="btn btn-danger btn-sm mx-1" onclick='return confirm("削除しますか？")'>削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection