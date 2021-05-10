
$(function(){
    get_data();
    get_sort_data();
    delete_data();
});


function get_data(){
    $('#get_product').on('click',function(){
        let search_keyword = $('#keyword').val();
        let search_id = $('#company_id').val();
        let min_price = $('#min_price').val();
        let max_price = $('#max_price').val();
        let min_stock = $('#min_stock').val();
        let max_stock = $('#max_stock').val();
        if(!search_keyword){
            search_keyword = null;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/management/index/' + search_keyword + '/' + search_id + '/' + min_price + '/' + max_price + '/' + min_stock + '/' + max_stock,
            data: {
                'keyword': search_keyword,
                'search_id': search_id,
                'min_price': min_price,
                'max_price': max_price,
                'min_stock': min_stock,
                'max_stock': max_stock,
            },
            dataType: 'json',
        })
        .done(function(data){
            get_value(data);
        })
        .fail(function(){
            alert("通信に失敗しました");
        });
    });
}


//ソート機能実装
function get_sort_data(){
    $('#sort_product').on('click',function(){
        let get_sort = $('#sort_product').data('value');
        let sort_list = 'product_name';
        let id_name = '#sort_product';
        get_ajax(get_sort,sort_list,id_name);
    });
    $('#sort_company').on('click',function(){
        let get_sort = $('#sort_company').data('value');
        let sort_list = 'company_id';
        let id_name = '#sort_company';
        get_ajax(get_sort,sort_list,id_name);
    });
    $('#sort_price').on('click',function(){
        let get_sort = $('#sort_price').data('value');
        let sort_list = 'price';
        let id_name = '#sort_price';
        get_ajax(get_sort,sort_list,id_name);
    });
    $('#sort_stock').on('click',function(){
        let get_sort = $('#sort_stock').data('value');
        let sort_list = 'stock';
        let id_name = '#sort_stock';
        get_ajax(get_sort,sort_list,id_name);
    });
}


function get_ajax(get_sort,sort_list,id_name){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/management/sort/' + get_sort + '/' + sort_list,
        data: {
            'get_sort': get_sort,
            'sort_list': sort_list,
        },
        dataType: 'json',
    })
    .done(function(data){
        if(get_sort == 'asc'){
            $(id_name).data('value', 'desc');
        }
        if(get_sort == 'desc'){
            $(id_name).data('value', 'asc');
        }
        get_sort = $(id_name).data('value');
        get_value(data);

    })
    .fail(function(jqXHR,textStatus,errorThrown){
        alert("通信に失敗しました");
    });
}

//描画処理
function get_value(data){
    $('#table #product-tr').empty();
    $.each(data[0],function (key, value){
        let id = value.id;
        let name = value.product_name;
        let company = value.company_id;
        //メーカー名を出力する
        $.each(data[1],function (key, value){
            if(company == value.id){
                company = value.company_name;
            }
        })
        let price = value.price;
        let stock = value.stock;
        let comment = value.comment;
        let image = value.product_image;
        if(comment == null){
            comment = "なし";
        }
        txt =`
        <tr id="product-tr">
            <td>${name}</td>
            <td>${company}</td>
            <td>${price}</td>
            <td>${stock}</td>
            <td>${comment}</td>
        `
        if(image != null){
            txt +=`
                <td id="image_at"><img src="storage/${image}" width="150" height="100"></td>
            `
        }else{
            txt +=`
                <td id="image_at">画像なし</td>
            `
        }
        txt +=`
            <td><button type="button" class="btn btn-primary" onclick="location.href='management/${id}'">詳細</button></td>
            <td>
                <button type="button" class="btn btn-primary" id="deleteTarget" data-id="${id}">削除</button>
            </td>
        </tr>
        `
        $('#table').append(txt);
    })
}


//削除機能
function delete_data(){
    $(document).on('click', '[id=deleteTarget]', function(){
        console.log("delete押された");
        let deleteConfirm = confirm('削除してもよろしいですか？');
        let clickEle;
        let data_id;
        if(deleteConfirm == true){
            clickEle = $(this);
            data_id = clickEle.attr('data-id');
        }else{
            return false;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/management/destroy/' + data_id,
            type: 'GET',
            data: {'id': data_id,
                   '_method': 'DELETE'},
          })
          .done(function(data) {
            get_value(data);
          })

         .fail(function(jqXHR,textStatus,errorThrown) {
            alert("通信に失敗しました");
          });

    })
}

