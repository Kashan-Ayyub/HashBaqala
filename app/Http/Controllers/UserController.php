<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(){
        $user = User::where('user_type', '=', '2')->get();
        return view('dashboard.users.index' , ['data' => $user]);
    }

    public function create(){
        return view('dashboard.users.create');
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
            'username'=>'required|unique:users',
            'email'=>'required|unique:users',
            'password'=>'required',
            'phone_number'=>'required',
            'address'=>'required',
            'image'=>'required|mimes:jpeg,png,jpg',
        ]);

        if($validateData->fails()){
            return back()->with('create_false' , 'Please fill all credentials.');
        }else{
            $find = User::where('username' , $request->username)->get();
            if(count($find) > 0){
                return back()->with('name_exsist' , 'Please fill all credentials.');
            }else{
                $user = new User;
                if(isset($request->image))
                {
                    $image = $request->file('image');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = "upload/profileImage/";
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
            
                    $destinationPath = "upload/profileImage/";
                    $image->move($destinationPath, $input['imagename']);
                    $user->user_image = $input['imagename'];
                }
                $user->username = $request->username;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;
                $user->address = $request->address;
                $user->password = Hash::make($request->password);
                $user->user_type = 2;
                
                 if ($request->status == "Active") {
                    $user->status = 1; 
                } else {
                    $user->status = 0; 
                }
                
                $user->save();
                return redirect(route('dashboard.user.index'))->with('create' , "true");
            }
        }
    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();
        return redirect(route('dashboard.user.index'));
    }

    public function edit($id){
        $user = User::find($id);
        return view('dashboard.users.edit' , ['data' => $user]);
    }

    public function update(Request $request){
        // return $request->all();

        $validateData = Validator::make($request->all(),
        [
            '_token'=>'required',
            'username'=>'required|',
            'email'=>'required|',
            'password'=>'required',
            'phone_number'=>'required',
            'address'=>'required',
            'hidden_id'=>'required',
        ]);

         $user = User::where('id' , $request->hidden_id)->first();
         
        if ($user->username !== $request->username) {
              if(User::where('username' , $request->username)->whereNot('id', $user->id)->exists())
              return redirect()->back()->with('exist', 'Product Name Already Exists');
            }
                
        
                if($request->hasFile('image'))
                {
                    
                    if(isset($user->user_image) && !empty($user->user_image)){
                        $oldImagePath = $user->user_image;
                        $str_img = str_replace(url('/') , "" ,$oldImagePath);
                        $image_remove = substr($str_img, 1);
                        if(is_file($image_remove)){
                        unlink($image_remove); }
                    }

                    $image = $request->file('image');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = "upload/profileImage/";
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
            
                   $destinationPath = "upload/profileImage/";
                    $image->move($destinationPath, $input['imagename']);
                    $user->user_image = $input['imagename'];
                }

                $user->username = $request->username;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;
                $user->address = $request->address;
                $user->password = $request->password;
                if($request->status == "Active"){
                    $user->status = 1;
                }else{
                    $user->status = 0;
                }
                $user->update();
                return redirect(route('dashboard.user.index'))->with('create' , "true");
            
        
    }

    // API WORKING STARTS
    public function login_api(Request $req)
    {
        // Validate request data
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

    
        // Find the user by email and check if the user is active
        $user = User::where('email', $req->email)->where('status', 1)->first();
    
        // Check if the user exists and the password is correct
        if ($user && Hash::check($req->password, $user->password)) 
        {
            // Check if the user type is admin (you might adjust this condition based on your user_type setup)
            if ($user->user_type == 1) 
            {
                // Generate a token for the user
                $token = $user->createToken('my-app-token')->plainTextToken;
    
                // Prepare the success response for admin
                $response = [
                    'message' => 'Admin login successful!',
                ];
    
                // Return the response with a 201 status code (Created)
                return response($response, 201);
            } 
            else 
            {
                // Return an error response with a 403 status code (Forbidden) for non-admin users
                return response(['error' => 'You do not have permission to access this resource.'], 403);
            }
        } 
        else 
        {
            // Return an error response with a 404 status code (Not Found)
            return response(['error' => 'Invalid credentials. Please check your email and password.'], 404);
        }
    }




    public function signup_api(Request $req){
        $validateData = Validator::make($req->all(),
        [
            'username'=>'required',
            'email'=>'required',
            'password'=>'required',
            'phone_number'=>'required',
            'address'=>'required',
            'image'=>'required|mimes:jpeg,png,jpg',
        ]);

        if($validateData->fails()){
            return response([
                'message' => ['These credentials do not stored in database username must be have min 4 character email and password is required.']
            ], 400);
        }else{
            $check = User::where('username',$req->username)->count();
            if($check > 0 ){
                return response([
                    'message' => ['The username is already exsist.']
                ], 400);
            }else{
                $user = new User;
                $user->username = $req->username;
                $user->email = $req->email;
                $user->password = Hash::make($req->password);
                $user->address = $req->address;
                $user->phone_number = $req->phone_number;
                $user->user_type = 2;
                
                if(isset($req->image))
                {
                    $image = $req->file('image');
                    $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = public_path('upload/profileImage/');
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);
            
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $input['imagename']);
                    $user->user_image = $input['imagename'];
                }
                $user->status = 1;  
                $save = $user->save();
                if($save == 1){
                    $token = $user->createToken('my-app-token')->plainTextToken;
                    $response = [
                        'data' => $user,
                        'token' => $token
                    ];
                    return response($response, 201);
                } else{
                    return response([
                        'message' => ['These credentials do not stored in databse username must be have min 4 character email and password is required.']
                    ], 400);
                }
            }
        }
    }
        public function UserCreate(Request $request){
                // Validate the request
                $validator = Validator::make($request->all(), [
                    'username' => 'required|unique:users|string|max:255',
                    'email' => 'required|unique:users|string|max:255',
                    'password' => 'required|string|max:255',
                    'phone_number' => 'required|unique:users|numeric',
                    'address' => 'required|string|max:255',
                    'user_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'status' => 'required'
                ]);
        
                // If validation fails, return the errors
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
                
                // Handle image upload
                $image = $request->file('user_image');
                    $input['user_image'] = time().'.'.$image->getClientOriginalExtension();
                
                    $destinationPath = 'upload/profileImage/';
                    $img = Image::make($image->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['user_image']);
            
                    $destinationPath = 'upload/profileImage/';
                    $image->move($destinationPath, $input['user_image']);
        
                // Create a new User
                $user = User::create([
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->password),
                    'phone_number' => $request->input('phone_number'),
                    'address' => $request->input('address'),
                    'user_image' => $input['user_image'],
                    'status' => $request->input('status'),
                    'user_type' => 2,
                ]);
        
                return response()->json(['message' => 'User created successfully', 'data' => $user], 201);
        }
        
        public function AllUsers(Request $request)
        {

           $user = User::where('user_type', '=', '2')->get();
            return response()->json($user);
            
        }
        public function Userbyid($id)
        {
            
            $user = User::find($id);
            return response()->json($user);
            
        }
        public function UserUpdatebyid(Request $request)
        {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255', // Make password field nullable for update
                'phone_number' => 'required|numeric',
                'address' => 'required|string|max:255',
                'user_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Make image field nullable for update
                'status' => 'required'
            ]);
        
            // If validation fails, return the errors
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            $user = User::find($request->id);
        
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
        
           
            // Update the fields based on the request
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->status = $request->status;
        
            // Update the 'image' field only if it's present in the request
             if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('user_image'), $imageName);
                $user->image = $imageName;
            }
        
            // Save the changes
            $user->save();
            return response()->json(['message' => 'User updated successfully',$user]);
        }

        public function UserDeletebyid($id) {
            $user = User::find($id);
        
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
        
            $user->delete();
        
            return new JsonResponse(['message' => 'User deleted successfully'], 200);
        }

// API WORKING ENDS

    

}
