<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\ProductTransformer;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $fractal;
    private $product;

    public function __construct()
    {
        $this->fractal = new Manager();
        $this->product = new Product();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // call data with pagination
        $paginator = $this->product->paginate(10);
        // get collection data
        $products = $paginator->getCollection();
        
        $resource = new Collection($products, new ProductTransformer);
        
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "product_name" => "bail|required|max:50",
            "product_description" => 'bail|required',
        ]);

        $createProduct = $this->product->create($request->all());
        $resource = new Item($createProduct, new ProductTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        $resource = new Item($product, new ProductTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate request parameters
        $this->validate($request, [
            'product_name' => 'max:255',
        ]);

        //Return error 404 response if product was not found
        if(!$this->product->find($id)) return response()->json([
            "message" => "Product Not Found"
        ], 404);

        $product = $this->product->find($id)->update($request->all());

        if($product){
            //return updated data
            $resource = new Item($this->product->find($id), new ProductTransformer); 
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful        
        return response()->json([
            "message" => "Failed to update product."
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Return error 404 response if product was not found
        if(!$this->product->find($id)) return response()->json([
            "message" => "Product Not Found"
        ], 404);

        //Return 410(done) success response if delete was successful
        if($this->product->find($id)->delete()){
            return $this->customResponse('Product deleted successfully!', 410);
        }

        //Return error 400 response if delete was not successful
        return response()->json([
            "message" => "Failed to delete product."
        ], 400);
    }
}
