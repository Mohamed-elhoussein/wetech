<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductCategoryFilter;
use App\Http\Requests\ProductCategoryFormRequest;
use App\Models\ProductCategories;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(ProductCategoryFilter $filter)
    {
        $categories = ProductCategories::filter($filter)->latest()->paginate(
            $filter->getRequest()->get('limit', 15)
        );

        return view('products.categories.index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return view('products.categories.create');
    }

    public function store(ProductCategoryFormRequest $request)
    {
        ProductCategories::create($request->getDataWithImagePath());
        return redirect()->route('product-categories.index')->with('created', 'تم إنشاء تصنيف المنتج بنجاح');
    }

    public function edit(ProductCategories $product_category)
    {
        return view('products.categories.edit', [
            'category' => $product_category
        ]);
    }

    public function update(ProductCategories $product_category, ProductCategoryFormRequest $request)
    {
        $product_category->update(
            $request->getDataWithImagePath()
        );
        return redirect()->route('product-categories.index')->with('updated', 'تم تعديل تصنيف المنتج بنجاح');
    }

    public function destroy(ProductCategories $product_category)
    {
        $product_category->delete();
        return redirect()->route('product-categories.index')->with('deleted', 'تم إنشاء حذف المنتج بنجاح');
    }
}
