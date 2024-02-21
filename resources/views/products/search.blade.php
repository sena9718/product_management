@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">検索結果</h1>

        <div class="products mt-5">
            <table class="table table-striped tablesorter" id="table_sort">
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
                              <button type="submit" class="btn btn-danger btn-sm mx-1 delete-product" data-product-id="{{ $product->id }}">削除</button>
                          </form>
                      </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection