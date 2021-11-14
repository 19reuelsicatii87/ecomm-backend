<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;

class ProductController extends Controller
{

    function addProduct(Request $req)
    {

        $product = new Product;
        $product->name = $req->input('name');
        $product->description = $req->input('description');
        $product->price = $req->input('price');
        $product->status = $req->input('status');
        $product->file_path = $req->file('file')->store('products');
        $product->save();

        return $product;
    }

    function listProduct()
    {

        return Product::all();
    }

    function deleteProduct($id)
    {

        $product = Product::find($id);

        if ($product != NULL) {
            $product->delete();
            return ['message' => 'Product successfully deleted'];
        }

        return ['message' => 'Product not found'];
        //return $product;

    }

    function getProduct($id)
    {
        $product = Product::find($id);
        if ($product != NULL) {
            return $product;
        }

        return ['message' => 'Product not found'];
    }

    function updateProduct(Request $req)
    {
        $product = Product::find($req->input('id'));

        if ($req->file('file') == NULL) {
            $product->name = $req->input('name');
            $product->description = $req->input('description');
            $product->price = $req->input('price');
            $product->status = $req->input('status');
            $product->updated_at = date("Y-m-d H:i:s");
        } else {
            $product->name = $req->input('name');
            $product->description = $req->input('description');
            $product->price = $req->input('price');
            $product->status = $req->input('status');
            $product->file_path = $req->file('file')->store('products');
            $product->updated_at = date("Y-m-d H:i:s");
        }

        $product->save();
        return $product;

    }

    function searchProduct($term)
    {
        $products = Product::
        where('name', 'like', "%{$term}%")
        ->orwhere('description', 'like', "%{$term}%")
        ->get();
        return $products;
    }

    function listActiveProduct()
    {
        $products = Product::
        where('status', 'true')
        ->get();

        return $products;
    }

    function payProduct(Request $req)
    {

        $invoice = new Invoice;
        $invoice->product_id = $req->input('product_id');
        $invoice->lead_id = $req->input('lead_id');
        $invoice->status = "Paid";
        $invoice->save();

        return $invoice;
    }
}
