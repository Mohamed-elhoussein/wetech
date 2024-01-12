<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductTypeFilter;
use App\Http\Requests\ProductTypeFormRequest;
use App\Models\ProductCategories;
use App\Models\ProductTypes;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index(ProductTypeFilter $filter)
    {
        $types = ProductTypes::filter($filter)->with('category')->latest()->paginate(
            $filter->getRequest()->get('limit', 15)
        );

        return view('products.types.index', [
            'types' => $types
        ]);
    }

    public function create()
    {
        $categories = ProductCategories::all();
        return view('products.types.create', [
            'categories' => $categories
        ]);
    }

    public function store(ProductTypeFormRequest $request)
    {
        ProductTypes::create($request->validated());
        return redirect()->route('product-types.index')->with('created', 'تم إنشاء تصنيف المنتج بنجاح');
    }

    public function edit(ProductTypes $product_type)
    {
        $categories = ProductCategories::all();
        return view('products.types.edit', [
            'type' => $product_type,
            'categories' => $categories
        ]);
    }

    public function update(ProductTypes $product_type, ProductTypeFormRequest $request)
    {
        $product_type->update(
            $request->validated()
        );
        return redirect()->route('product-types.index')->with('updated', 'تم تعديل تصنيف المنتج بنجاح');
    }

    public function destroy(ProductTypes $product_type)
    {
        $product_type->delete();
        return redirect()->route('product-types.index')->with('deleted', 'تم إنشاء حذف المنتج بنجاح');
    }
}
