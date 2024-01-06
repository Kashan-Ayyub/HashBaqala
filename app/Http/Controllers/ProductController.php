<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;


class ProductController extends Controller
{
    //
    public function index(){
        $product = Product::select("products.*", "categories.name")->join("categories" , "categories.id" , "=" , "products.category_id")->get();
        return view('dashboard.product.index' , ['data' => $product]);
    }

    public function delete($id){
        $product = Product::find($id);
        $product->delete();
        return redirect(route('dashboard.product.index'));
    }

    public function create(){
        $category = Category::all();
        return view('dashboard.product.create' , ['category' => $category]);
    }
    
                                    // Controller
                                public function show() {
                                    // Assuming you have a $status variable
                                    $status = 1;
                                
                                    // Pass the variable to the view
                                    return view('your_view', compact('status'));
                                }

    public function store(Request $request){
        // return $request->all();

        $validateData = Validator::make($request->all(),
        [
            '_token'=>'required',
            'p_name'=>'required|unique:products',
            'p_price'=>'required',
            'cat_id'=>'required',
            'p_desc'=>'required',
            'image'=>'sometimes|mimes:jpeg,png,jpg',
        ]);

        if($validateData->fails()){
            return back()->with('create_false' , 'Please fill all credentials.');
        }else{
            $find = Product::where('p_name' , $request->p_name)->get();
            if(count($find) > 0){
                return back()->with('name_exsist' , 'Please fill all credentials.');
            }else{
                $product = new Product;
                if(isset($request->image))
                {   
                    $image = $request->file('image');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = 'upload/productImage/';
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
            
                    $destinationPath = 'upload/productImage/';
                    $image->move($destinationPath, $input['imagename']);
                    $product->product_image = $input['imagename'];

                }
    
                $product->p_name = $request->p_name;
                $product->price = $request->p_price;
                $product->description = $request->p_desc;
                $product->category_id = $request->cat_id;
                
                 if ($request->status == "Active") 
                 {
                        $product->status = 1; 
                 } 
                 else 
                 {
                        $product->status = 0; 
                 }
                
                $product->save();
                return redirect(route('dashboard.product.index'))->with('create' , "true");
            }
        }
    }

    public function edit($id){
        $product = Product::find($id);
        $category = Category::all();
        return view('dashboard.product.edit' , ['data' => $product , "category" => $category]);
    }

    public function update(Request $request){
        
        $validateData = $request->validate(
        [
            'p_name'=>'required',
            'Product_Price'=>'required',
            'cat_id'=>'required',
            'p_desc'=>'required',
        ]);
        
                $product = Product::where('id' , $request->id)->first();
                
                if ($product->p_name !== $request->p_name) {
                    
                    $exist = Product::where('p_name' , $request->p_name)->whereNot('id', $product->id)->first();
                    if($exist)
                    return redirect()->back()->with('exist', 'Product Name Already Exists');
                }
                
                if ($request->hasFile('image'))
                 {
                    if(isset($product->product_image) && !empty($product->product_image)){
                        $oldImagePath = $product->product_image;
                        $str_img = str_replace(url('/') , "" ,$oldImagePath);
                        $image_remove = substr($str_img, 1);
                        
                        unlink($image_remove);
                    }

                    $image = $request->file('image');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = 'upload/productImage/';
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
            
                    $destinationPath = 'upload/productImage/';
                    $image->move($destinationPath, $input['imagename']);
                    $product->product_image = $input['imagename'];
                }

                $product->p_name = $request->p_name;
                $product->price = $request->Product_Price;
                $product->description = $request->p_desc;
                $product->category_id = $request->cat_id;
                
                $request->status == "Active" 
                     ? $product->status = 1 
                     : $product->status = 0;
                     
                $product->update();
                return redirect(route('dashboard.product.index'))->with('update' , "true");
            }
        
    

// API WORKING STARTS
    public function fetch_api(Request $req , $id=null){
        if(isset($id) && !empty($id)){
            $product = Product::where('id',$id)->where('status' , 1)->get();
            if(count($product) > 0){                
                return response([
                    'message' => ['Product Data is Exsist.'],
                    'data' => $product
                ], 200);
            }else{
                return response([
                    'message' => ['These Product Data Does not Exsist.']
                ], 200);
            }
        }else{
            $product = Product::where('status' , 1)->get();
            if(count($product) > 0){
                return response([
                    'message' => ['Data is Available'],
                    'data' => $product
                ], 200);
            }else{
               return response([
                   'message' => ['Data is not Available'],
               ], 200);
            }
        }
    }
    
    
        public function ProductCreate(Request $request)
        {
                // Validate the request
                $validator = Validator::make($request->all(), [
                    'p_name'=>'required|unique:products',
                    'category_name' => 'required', // Change validation to accept category_name
                    'price'=>'required',
                    'product_image'=>'required|mimes:jpeg,png,jpg',
                    'description'=>'required',
                    'status' =>'required'
                ]);
        
                // If validation fails, return the errors
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
        
                // Handle image upload
                $image = $request->file('product_image');
                    $input['product_image'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = 'upload/productImage/';
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['product_image']);
            
                    $destinationPath = 'upload/productImage/';
                    $image->move($destinationPath, $input['product_image']);
        
                      // Get category_id using category_name
                    $categoryId = $this->getCategoryIdByName($request->input('category_name'));

        
                // Create a new Product
                $product = Product::create([
                    'p_name' => $request->input('p_name'),
                    'category_id' => $categoryId,
                    'price' => $request->input('price'),
                    'product_image' => $input['product_image'],
                    'description' => $request->input('description'),
                    'status' => $request->input('status'),
                ]);
        
                return response()->json(['message' => 'Product created successfully', 'data' => $product], 201);
                
        }
                             
            // Function to get category_id by category_name
            private function getCategoryIdByName($categoryName){
                $category = Category::where('name', $categoryName)->first();
                return $category ? $category->id : null;
            }
                
           
        
        
        public function AllProducts(Request $request)
        {
           $product = Product::all();
            return response()->json($product);
            
        }
        public function Productbyid($id)
        {
            
            $product = product::find($id);
            return response()->json($product);
            
        }
       public function ProductUpdatebyid(Request $request)
        {
            $validator = Validator::make($request->all(), [
                    'p_name'=>'required',
                    'category_name' => 'required',
                    'price'=>'required',
                    'product_image'=>'sometimes|mimes:jpeg,png,jpg',
                    'description'=>'required',
                    'status' =>'required'
            ]);
        
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
        
            // Find the product
            $product = Product::find($request->id);
        
            // Check if the category exists
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
            
             // Get category_id using category_name
            $categoryId = $this->getCategoryIdByName($request->input('category_name'));

            
            // Update the fields based on the request
            $product->p_name = $request->p_name;
            $product->category_id = $categoryId;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->status = $request->status;
        
            // Update the 'image' field only if it's present in the request
             if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $product->image = $imageName;
            }
        
            // Save the changes
            $product->save();
        
            return response()->json(['message' => 'Product Updated Successfully',$product]);
        }

       public function ProductDeletebyid($id){
        $product = Product::find($id);
        $product->delete();
        return response()->json(['message' => 'Product Updated Successfully']);
    }

    

// API WORKING ENDS


    
    
    
}
