<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    protected $table = 'products';
    use Sortable;
    public $sortable = ['company_id', 'product_name', 'price', 'stock'];

    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'product_image'
    ];
    public function company(){
        return $this->belongsTo('App\Models\Company');
    }

    public static function priceConstruct($products){
        $input = 0;
        foreach($products as $product){
            if($input < $product->price){
                $input = $product->price;
            }
        }
        return $input;
    }

    public static function stockConstruct($products){
        $input = 0;
        foreach($products as $product){
            if($input < $product->stock){
                $input = $product->stock;
            }
        }
        return $input;
    }

    public static function newProduct($request){
        $inputs = new Product();
        $inputs->company_id = $request->company_id;
        $inputs->product_name = $request->product_name;
        $inputs->price = $request->price;
        $inputs->stock = $request->stock;
        $inputs->comment = $request->comment;

        if($request->product_image){
            $path = $request->file('product_image')->store('public');
            $file_name = basename($path);
            $inputs->product_image = $file_name;
        }
        return $inputs;
    }

    public static function updateProduct($request){
        $product = Product::find($request->id);
        if($request->product_image){
            $path = $request->file('product_image')->store('public');
            $file_name = basename($path);
            $product->fill(['product_image' => $file_name]);
        }
        $product->fill([
            'company_id' => $request->company_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
        ]);
        $product->save();
        return $product;
    }

    public static function Search($keyword,$search_id,$min_price,$max_price,$min_stock,$max_stock){
        $query = Product::query();
        $products = $query->where('product_name', 'LIKE','%'.$keyword.'%')
        ->orWhere('company_id', $search_id)->orWhereBetween('price', [$min_price, $max_price])
        ->orWhereBetween('stock', [$min_stock, $max_stock])->get();
        return $products;
    }

    public static function Sort($get_sort, $sort_list){
        $query = Product::query();
        if($get_sort === 'asc'){
            $products = $query->orderBy($sort_list, 'desc')->get();
        }
        if($get_sort === 'desc'){
            $products = $query->orderBy($sort_list, 'asc')->get();
        }
        return $products;
    }

}
