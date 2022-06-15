<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin:admin')
            ->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $exists = [];
        foreach ($products as $key => $product) {
            $exists[$key] = File::exists($product->image_path);
        }
        return view('admin.product.index', compact('products', 'exists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
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
            'name' => ['required', 'max:64'],
            'qty' => ['required', 'numeric', 'min:0', 'max:1000'],
            'price' => ['required', 'numeric', 'min:1000'],
            'category_id' => ['required']
        ]);

        // Create new Path Image
        // if request has file
        if ($request->hasFile('image')) {

            // validate image
            $this->validate($request, [
                'image' => ['image', 'mimes:jpeg,png,jpg', 'max:1024'],
            ]);

            // Create new file name
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            // save file to destination path
            $request->image->move(public_path('storage'), $imageName);

            // create path destination file for database
            $destination = 'storage' . '/' . $imageName;

            // create new array request
            $data = array(
                'name' => ucwords($request->name),
                'description' => ucfirst($request->description),
                'qty' => $request->qty,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'image_path' => $destination
            );

            // save data to database
            Product::create($data);

            session()->flash('message', 'Data has been saved');
            session()->flash('alert-class', 'alert-success');
            return redirect('products');
        }

        // If file not exists
        // create new array request
        $data = array(
            'name' => ucwords($request->name),
            'description' => ucfirst($request->description),
            'qty' => $request->qty,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_path' => 'default'
        );

        // save data to database
        Product::create($data);

        session()->flash('message', 'Data has been saved');
        session()->flash('alert-class', 'alert-success');
        return redirect('products');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        abort(404, 'Page Not Found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $exists = File::exists($product->image_path);
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories', 'exists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => ['required', 'max:64'],
            'qty' => ['required', 'numeric', 'min:0', 'max:1000'],
            'price' => ['required', 'numeric', 'min:1000'],
            'category_id' => ['required']
        ]);

        // if has request image_path
        if ($request->image_path) {
            // validate the path
            $this->validate($request, [
                'image_path' => ['max:255'],
            ]);

            // update data
            $product->update($request->all());

            session()->flash('message', 'Data has been updated');
            session()->flash('alert-class', 'alert-success');
            return redirect('products');
        }


        // Update Data when file has change
        if ($request->hasFile('image')) {
            //if has file request
            $this->validate($request, [
                'image' => ['image', 'mimes:jpeg,png,jpg', 'max:1024'],
            ]);

            // Create new file name
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            // save file to destination path
            $request->image->move(public_path('storage'), $imageName);

            // create path destination file for database
            $destination = 'storage' . '/' . $imageName;

            // create new array request
            $data = array(
                'name' => ucwords($request->name),
                'description' => ucfirst($request->description),
                'qty' => $request->qty,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'image_path' => $destination
            );

            // Delete file when Before Change
            if (File::exists($product->image_path)) {
                File::delete($product->image_path);
            }

            // update change
            $product->update($data);


            session()->flash('message', 'Data has been updated');
            session()->flash('alert-class', 'alert-success');
            return redirect('products');
        } else {
            //if no has file request
            // create new array request
            $data = array(
                'name' => ucwords($request->name),
                'description' => ucfirst($request->description),
                'qty' => $request->qty,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'image_path' => 'default'
            );

            // update data
            $product->update($data);

            session()->flash('message', 'Data has been updated');
            session()->flash('alert-class', 'alert-success');
            return redirect('products');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $image_path = public_path($product->image_path);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        $product->delete();

        session()->flash('message', 'Data has been deleted');
        session()->flash('alert-class', 'alert-success');
        return redirect('products');
    }
}
