@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    {{-- 検索フォーム     --}}
    <div class="search mt-5">

        <!-- 検索フォーム。GETメソッドで、商品一覧のルートにデータを送信 -->
        <form id="search-form" action="{{ route('products.search') }}" method="GET" class="row g-3">

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
            <!-- 価格（下限〜上限）検索用の入力欄 -->
            <div class="col-sm-12 col-md-4">
                <input type="text" name="min_price" class="form-control" placeholder="最小価格" value="{{ request('min_price') }}">
            </div>
            <div class="col-sm-12 col-md-4">
                <input type="text" name="max_price" class="form-control" placeholder="最大価格" value="{{ request('max_price') }}">
            </div>
            <!-- 在庫数（下限〜上限）検索用の入力欄 -->
            <div class="col-sm-12 col-md-4">
                <input type="text" name="min_stock" class="form-control" placeholder="最小在庫数" value="{{ request('min_stock') }}">
            </div>
            <div class="col-sm-12 col-md-4">
                <input type="text" name="max_stock" class="form-control" placeholder="最大在庫数" value="{{ request('max_stock') }}">
            </div>
            <!-- 検索ボタン -->
            <div class="col-sm-12 col-md-1">
                <button id="search-btn" class="btn btn-outline-secondary" type="button">検索</button>
            </div>
        </form>

        <div id="search-results" class="mt-5">

        </div>
    </div>

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
                <tr data-product-id="{{ $product->id }}">
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

@section('scripts')
<script>
    $(document).ready(function () {

        $(".tablesorter").tablesorter( {
            sortList: [[0,1]]
        });

        // // 商品データを削除するための Ajax リクエスト
        // $('.delete-product').on('click', function (event) {
        //     event.preventDefault();
            
        //     var productId = $(this).data('product-id');

        //     if (confirm("削除しますか？")) {
        //         $.ajax({
        //             type: 'POST',
        //             data:{'_method':'delete'},
        //             url: '/products/' + productId,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function (data) {
        //                 // 削除された行を非表示にする
        //                 $('tr[data-product-id="' + productId + '"]').hide();
        //                 alert('削除しました。');
        //             },
        //             error: function (data) {
        //                 alert('削除に失敗しました。');
        //             }
        //         });
        //     }
        // });
        // }

        // 商品データを削除するための Ajax リクエスト
        $(document).on('click', '.delete-product', function (event) {
            event.preventDefault();
            
            var productId = $(this).data('product-id');

            if (confirm("削除しますか？")) {
                $.ajax({
                    type: 'POST',
                    data:{'_method':'delete'},
                    url: '/products/' + productId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        // 削除された行を非表示にする
                        $('tr[data-product-id="' + productId + '"]').hide();
                        alert('削除しました。');

                        // 削除後に検索を再度実行して画面を更新する
                        $('#search-btn').trigger('click');
                    },
                    error: function (data) {
                        alert('削除に失敗しました。');
                    }
                });
            }
        });

        $('#search-btn').on('click', function () {
            var formData = $('#search-form').serialize();

            // 検索ルートにAjaxリクエストを送信
            $.ajax({
                type: 'GET',
                url: '/products/search',
                data: formData,
                dataType: 'json',
                success: function (data) {
                    // 検索結果のdivを更新
                    $('#search-results').empty(); // 既存の内容をクリア

                    if(data.products.length > 0) {
                        // データが存在する場合の処理
                        var newTableHtml = '<table class="table table-striped tablesorter" id="table_sort">' +
                            '<thead><tr>' +
                            '<th>ID</th>' +
                            '<th>商品画像</th>' +
                            '<th>商品名</th>' +
                            '<th>価格</th>' +
                            '<th>在庫数</th>' +
                            '<th>メーカー名</th>' +
                            '<th></th>' +
                            '</tr></thead><tbody>';

                        $.each(data.products, function (index, product) {
                            // 適切な方法でデータを表示するための処理
                            newTableHtml += '<tr>' +
                                '<td>' + product.id + '</td>' +
                                '<td><img src="' + product.img_path + '" alt="商品画像" width="100"></td>' +
                                '<td>' + product.product_name + '</td>' +
                                '<td>' + product.price + '</td>' +
                                '<td>' + product.stock + '</td>' +
                                '<td>' + product.company_name + '</td>' +
                                '<td>' +
                                '<a href="/products/show/' + product.id + '" class="btn btn-info btn-sm mx-1">詳細</a>' +
                                '<form method="POST" action="/products/' + product.id + '" class="d-inline">' +
                                '@csrf' +
                                '@method("DELETE")' +
                                '<button type="submit" class="btn btn-danger btn-sm mx-1 delete-product" data-product-id="' + product.id + '">削除</button>' +
                                '</form>' +
                                '</td>' +
                                '</tr>';
                        });
                        
                        newTableHtml += '</tbody></table>';

                        $('.products').hide();

                        $('#search-results').html(newTableHtml);

                        // 検索後の結果にもtablesorterを適用
                        $("#search-results table.tablesorter").tablesorter();
                    } else {
                        // データが存在しない場合の処理
                        $('#search-results').html('<p>該当する商品が見つかりませんでした。</p>');
                    }
                },
                error: function (data) {
                    alert('検索に失敗しました。もう一度お試しください。');
                }
            });
        });
    });
</script>
@endsection