<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Intervention\Image\Facades\Image;
use Validator;
use Illuminate\Http\JsonResponse;
class BannerController extends Controller
{
     public function index(){
        $banner = Banner::all();
        return view('Banner.index' , ['data' => $banner]);
    }

    public function create(){
        return view('Banner.create');
    }
    
                 // Controller
            public function show() {
            // Assuming you have a $status variable
            $status = 1;
                                
            // Pass the variable to the view
            return view('your_view', compact('status'));
                                }

    public function store(Request $request){
        $validateData = Validator::make($request->all(),
        [
            '_token'=>'required',
            'image'=>'required|mimes:jpeg,png,jpg'
        ]);
        
        if($validateData->fails()){
            return back()->with('create_false' , 'Please fill all credentials.');
        }else{
            $banner = new banner;
            if(isset($request->image))
            {
                $image = $request->file('image');
                $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                $destinationPath = 'upload/bannerImage/';
                $img = Image::make($image->getRealPath());
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
                
                $destinationPath = 'upload/bannerImage/';
                $image->move($destinationPath, $input['imagename']);
                $banner->image = $input['imagename'];
            }
             if ($request->status == "Active") {
                    $banner->status = 1; 
                } else {
                    $banner->status = 0; 
                }
            $banner->save();

            return redirect(route('dashboard.banner.index'))->with('create' , "true");
        }
    }

    public function update(Request $request){
        $validateData = Validator::make($request->all(),
        [
            '_token'=>'required',
            'id' =>'required'
        ]);
        
        if($validateData->fails()){
            return back()->with('create_false' , 'Please fill all credentials.');
        }else{
            $banner = banner::find($request->id);
            if(isset($request->image))
            {
                $image = $request->file('image');
                $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                $destinationPath = 'upload/bannerImage/';
                $img = Image::make($image->getRealPath());
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
                
                $destinationPath = 'upload/bannerImage/';
                $image->move($destinationPath, $input['imagename']);
                $banner->image = $input['imagename'];
            }
            if($request->status == "Active"){
                $banner->status = 1;
            }else{
                $banner->status = 0;
            }
            $banner->update();

            return redirect(route('dashboard.banner.index'))->with('create' , "true");
        }
    }

    public function delete($id){
        $banner = banner::find($id);
        $banner->delete();
        return back();
    }

    public function edit($id){
        // return 1;
        $banner = banner::find($id);
        return view('Banner.edit' , ['data' => $banner]);
    }

    public function fetch_api(Request $req , $id=null){
        if(isset($id) && !empty($id)){
             $banner = banner::where('id' , $id)->where('status' , 1)->get();
            if(count($banner) > 0){                
                return response([
                    'message' => ['Banner Data is Exsist.'],
                    'data' => $banner
                ], 200);
            }else{
                return response([
                    'message' => ['These Banner Data Does not Exsist.']
                ], 200);
            }
        }else{
            $banner = banner::all()->where('status' , 1);
            if(count($banner) > 0){
                return response([
                    'message' => ['Data is Available'],
                    'data' => $banner
                ], 200);
            }else{
               return response([
                   'message' => ['Data is not Available'],
               ], 200);
            }
        }
    }
    
    
// API WORKING STARTS
        public function BannerCreate(Request $request){
                // Validate the request
                $validator = Validator::make($request->all(), [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'status' => 'required'
                ]);
        
                // If validation fails, return the errors
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
         // Handle image upload
                $image = $request->file('image');
                    $input['image'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = 'upload/bannerImage/';
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['image']);
            
                    $destinationPath = 'upload/bannerImage/';
                    $image->move($destinationPath, $input['image']);
        
                // Create a new Banner
                $banner = Banner::create([
                    'image' => $input['image'],
                    'status' => $request->input('status'),
                ]);
        
                return response()->json(['message' => 'Banner created successfully', 'data' => $banner], 201);
        }
        
        public function AllBanners(Request $request)
        {

           $banner = Banner::all();
            return response()->json($banner);
            
        }
        public function Bannerbyid($id)
        {
            
            $banner = banner::find($id);
            return response()->json($banner);
            
        }
        public function BannerUpdatebyid(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'required',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
        
            // Find the banner
            $banner = Banner::find($request->id);
        
            // Check if the banner exists
            if (!$banner) {
                return response()->json(['message' => 'banner not found'], 404);
            }
        
            // Update the fields based on the request
            $banner->status = $request->status;
        
            // Update the 'image' field only if it's present in the request
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $banner->image = $imageName;
            }
        
            // Save the changes
            $banner->save();
            
        
            return response()->json(['message' => 'Banner updated successfully',$banner]);
        }

        public function BannerDeletebyid($id) {
    $banner = Banner::find($id);

    if (!$banner) {
        return response()->json(['error' => 'Banner not found'], 404);
    }

    $banner->delete();

    return new JsonResponse(['message' => 'Banner deleted successfully'], 200);
}

// API WORKING ENDS



    
    
    

}
