<?php

namespace App\Http\Controllers;

use App\Services\ProductService;


use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    

    public function __construct(
        ProductService $productService,
        
    )
    {
        $this->productService = $productService;
        
    }

    private function foreignData()
    {
        return [];
    }

    public function index()
    {
        return view('app.product.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['name', 'status', 'created_from', 'created_to']);
        $items = $this->productService->get($filters);

        return response()->json([
            'data' => $items->map(fn($item) => [
                'id' => $item->id ?? '',
                'name' => $item->name ?? '',
                'status' => $item->status ?? '',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('product.show', $item->id),
                    'edit' => route('product.edit', $item->id),
                    'delete' => route('product.destroy', $item->id),
                ]
            ])->toArray()
        ]);
    }

    public function show($id)
    {
        $item = $this->productService->find($id);
        $foreignData = $this->foreignData();

        return view('app.product.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.product.create', $this->foreignData());
    }

    public function store(StoreProductRequest $request)
    {
        $item = $this->productService->create($request->validated());
        return redirect()->route('product.show', $item)->with('success', __('Product created successfully.'));
    }

    public function edit($id)
    {
        $item = $this->productService->find($id);
        $foreignData = $this->foreignData();

        return view('app.product.edit', [
            'item' => $item,
        ] + $foreignData);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $this->productService->update($id, $request->validated());
        return redirect()->route('product.show', $id)->with('success', __('Product updated successfully.'));
    }

    public function destroy($id)
    {
        $this->productService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('Item deleted successfully.')]);
        }

        return redirect()->route('product.index')->with('success', __('Product deleted successfully.'));
    }
}