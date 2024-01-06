<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    

//<<<<<<<<<<---------- API WORKING STARTS ---------->>>>>>>>>>

        public function CategoryCreate(Request $request){
                // Validate the request
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:categories|string|max:255',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'status' => 'required'
                ]);
        
                // If validation fails, return the errors
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
        
                // Handle image upload
                    $image = $request->file('image');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = 'upload/categoryImage/';
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
            
                    $destinationPath = 'upload/categoryImage/';
                    $image->move($destinationPath, $input['imagename']);
        
                // Create a new category
                $category = Category::create([
                    'name' => $request->input('name'),
                    'image' => $input['imagename'],
                    'status' => $request->input('status'),
                ]);
        
                return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
        }
        
        public function AllCategories(Request $request)
        {

           $category = Category::all();
            return response()->json($category);
            
        }
        public function Categorybyid($id)
        {
            
            $category = category::find($id);
            return response()->json($category);
            
        }
        public function CategoryUpdatebyid(Request $request, $id)
        {
            
            // Find the category
            $category = Category::find($id);
            
            // Check if the category exists
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'required',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            // Update the 'image' field only if it's present in the request
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagefile = time().'.'.$image->getClientOriginalExtension();
            
                $destinationPath = 'upload/categoryImage/';
                $img = Image::make($image->getRealPath());
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$imagefile);
        
                $destinationPath = 'upload/categoryImage/';
                $image->move($destinationPath, $imagefile);
                $category->image = $destinationPath . $imagefile;
            }
        
            // Update the fields based on the request
            $category->name = $request->name;
            $category->status = $request->status;
        
            // Save the changes
            $category->save();
        
            return response()->json(['message' => 'Category updated successfully',$category]);
        }


       public function CategoryDeletebyid($id) {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
    
        // Delete associated products as well
        $category->products()->delete();
    
        $category->delete();

        return new JsonResponse(['message' => 'Category deleted successfully'], 200);
    }

                //<<<<<<<<<<---------- API WORKING ENDS ---------->>>>>>>>>>



    
    public function index(){
        $category = Category::all();
        return view('dashboard.category.index' , ['data' => $category]);
    }

    public function create(){
        
        return view('dashboard.category.create');
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
            'image'=>'required|mimes:jpeg,png,jpg',
            'cat_name'=>'required|unique:categories,name',
            'status'=>'required'
        ]);
        

        if($validateData->fails()){
            return back()->with('create_false' , 'Please fill all credentials.');
        }
        
         $category = new Category;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'upload/categoryImage/';
            $image->move($destinationPath, $imageName);
            $category->image = $imageName;
        }
    
        $category->name = $request->cat_name;
    
        // Simplify status assignment
        $category->status = ($request->status == 'Active') ? 1 : 0;
    
        $category->save();
        
        return redirect(route('dashboard.category.index'))->with('create' , "true");
    }
        

    

    public function delete($id){
        
        $category = Category::find($id);
        
         if (!$category) {
            return redirect(route('dashboard.category.index'))->with('delete_error', 'Category not found');
        }

        // Delete associated products as well
        $category->products()->delete();
        
        $oldImagePath = $category->image;
        $str_img = str_replace(url('/'), "", $oldImagePath);
        $image_remove = substr($str_img, 1);
        unlink($image_remove);
        
        $category->delete();
        
        return redirect(route('dashboard.category.index'));
    }

    public function edit($id){
        $Category = Category::find($id);
        
        return view('dashboard.category.edit' , ['data' => $Category]);
    }

    public function update(Request $request){
        
        $validateData = Validator::make($request->all(),
        [
            'cat_name'=>'required'
        ]);
        
        if($validateData->fails()){
            return back()->with('update_error' , 'Please change all credentials.');
        }
        
        $category = Category::find($request->id);
        
        $category->name = $request->cat_name;
        
        $request->status == "Active" 
                     ? $category->status = 1 
                     : $category->status = 0;

        
        if ($request->hasFile('image'))
        {
            $oldImagePath = $category->image;
            $str_img = str_replace(url('/'), "", $oldImagePath);
            $image_remove = substr($str_img, 1);
        
            unlink($image_remove);
        
            $image = $request->file('image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
        
            $destinationPath = 'upload/categoryImage/';
            $img = Image::make($image->getRealPath());
            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $input['imagename']);
        
            $destinationPath = 'upload/categoryImage/';
            $image->move($destinationPath, $input['imagename']);
            $category->image = $input['imagename'];
        }
           

        $category->update();
        return redirect(route('dashboard.category.index'))->with('update' , "true");
            
        
    }

    public function fetch_api(Request $req , $id=null){
        if(isset($id) && !empty($id)){
            $category = Product::where('category_id',$id)->where('status' , 1)->get();
            if(count($category) > 0){                
                return response([
                    'message' => ['Category Data is Exsist.'],
                    'data' => $category
                ], 200);
            }else{
                return response([
                    'message' => ['These Category Data Does not Exist.']
                ], 200);
            }
        }else{
            $category = Category::all()->where('status' , 1);
            if(count($category) > 0){
                return response([
                    'message' => ['Data is Available'],
                    'data' => $category
                ], 200);
            }else{
               return response([
                   'message' => ['Data is not Available'],
               ], 200);
            }
        }
    }

}
