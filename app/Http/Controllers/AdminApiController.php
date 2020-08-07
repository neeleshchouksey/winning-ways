<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Career;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\DebitRequest;
use App\Models\Faq;
use App\Models\HomepageSection1;
use App\Models\HomepageSection2;
use App\Models\HomepageSection3;
use App\Models\HomepageSection4;
use App\Models\HomepageSection5;
use App\Models\Package;
use App\Models\PackageCommission;
use App\Models\PrivacyPolicy;
use App\Models\SubCategory;
use App\Models\SubcategoryVideo;
use App\Models\TermsCondition;
use App\Models\UserPackage;
use App\Models\UserPayments;
use App\Models\WorkFollowup;
use App\Models\WorkPackage;
use App\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AdminApiController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        } else if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => $request->userType])) {
            $user = Auth::user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();
            return response()->json([
                "status" => "success",
                "message" => "User login successfully",
                'access_token' => $tokenResult->accessToken,
                'user' => $user,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Sorry, you entered an incorrect email address or password.'], 400);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => 'success'], 200);

    }

    public function get_users(Request $request)
    {
        $res = User::select("users.id","users.email","users.phone","users.name","users.created_at", "packages.name as package",
            DB::raw("from_unixtime(user_packages.expiry,'%d/%m/%Y %h:%i %p') as expiry" ))
            ->join("user_packages", "users.id", "user_packages.user_id")
            ->join("packages", "user_packages.package_id", "packages.id")
            ->orderby("id", "desc");

        if ($request->userName) {
            $res = $res->where("users.name", 'like', "%$request->userName%");
        }
        if ($request->email) {
            $res = $res->where("users.email", 'like', "%$request->email%");
        }
        if ($request->phone) {
            $res = $res->where("users.phone", 'like', "%$request->phone%");
        }

        $res = $res->paginate(10);

        foreach ($res as $r) {
            $r->total_works = Work::where(["user_id"=> $r->id,"claim"=>0])->count();
            $r->total_claimed_works = Work::where(["user_id"=> $r->id,"claim"=>1])->count();
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_recent_users(Request $request)
    {
        $res = User::select("users.*", "user_packages.expiry", "packages.name as package")
            ->join("user_packages", "users.id", "user_packages.user_id")
            ->join("packages", "user_packages.package_id", "packages.id")
            ->orderby("id", "desc")
            ->limit(5)
            ->get();

        foreach ($res as $r) {
            $r->total_works = Work::where("user_id", $r->id)->count();
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_packages()
    {
        $res = Package::select("id","name","price","image","validity")->orderby("id", "desc")
            ->paginate(10);
        foreach ($res as $r) {
            $r->image = url('/') . '/public/storage/package-images/' . $r->image;
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_package_requests(Request $request)
    {
        $work_package = Package::select("user_packages.id", "packages.name as pkg_name", "packages.validity", "packages.price", "users.name")
            ->join("user_packages", "user_packages.package_id", "packages.id")
            ->join("users", "users.id", "user_packages.user_id")
            ->where("user_packages.status", 0);

        if($request->user){
            $work_package = $work_package->where("users.name","like","%".$request->user."%");
        }
         if($request->package){
            $work_package = $work_package->where("packages.name","like","%".$request->package."%");
        }
        $work_package = $work_package->paginate(10);

        return response()->json(['status' => 'success', "res" => $work_package], 200);
    }

    public function accept_package_request($id)
    {
        $up = UserPackage::find($id);
        $p = Package::find($up->package_id);
        $up->expiry = strtotime("+$p->validity days", time());
        $up->status = 1;
        $up->save();
        return response()->json(['status' => 'success', "message" => "Package Request accepted successfully"], 200);
    }

    public function get_single_package($id)
    {
        $res = Package::with("packagecommission")->find($id);
        $res->image = url('/') . '/public/storage/package-images/' . $res->image;
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_package(Request $request)
    {

        $subcategory = explode(',', $request->subcategory);
        $commission = explode(',', $request->commission);

        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required'],
            'price' => ['required'],
            'image' => ['required'],
            'validity' => ['required'],
            'subcategory.*' => ['required'],
            'commission.*' => ['required'],

        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/package-images', $front_image);
            }
            $w = new Package();
            $w->name = $request->name;
            $w->price = $request->price;
            $w->validity = $request->validity;
            $w->image = $front_image;
            $w->save();


            for ($i = 0; $i < sizeof($subcategory); $i++) {
                $pc = new PackageCommission();
                $pc->package_id = $w->id;
                $pc->subcategory_id = $subcategory[$i];
                $pc->commission = $commission[$i];
                $pc->save();
            }

            return response()->json(['status' => 'success', 'message' => "Package Added Successfully"], 200);
        }
    }

    public function update_package(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required'],
            'price' => ['required'],
            'commission' => ['required'],
            'validity' => ['required'],
        ]);
        $w = Package::find($request->id);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($request->hasfile('image')) {
                //  unlink(storage_path('app/public/package-images/' . $w->image));
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/package-images', $front_image);
            } else {
                $front_image = $w->image;
            }

            $w->name = $request->name;
            $w->price = $request->price;
            $w->validity = $request->validity;
            $w->image = $front_image;
            $w->save();

            PackageCommission::where("package_id", $request->id)->delete();

            $subcategory = explode(',', $request->subcategory);
            $commission = explode(',', $request->commission);
            for ($i = 0; $i < sizeof($subcategory); $i++) {
                $pc = new PackageCommission();
                $pc->package_id = $w->id;
                $pc->subcategory_id = $subcategory[$i];
                $pc->commission = $commission[$i];
                $pc->save();
            }

            return response()->json(['status' => 'success', 'message' => "Package Updated Successfully"], 200);
        }
    }

    public function delete_package($id)
    {
        $res = Package::find($id);
        unlink(storage_path('app/public/package-images/' . $res->image));
        Package::find($id)->delete();
        //$res->image = url('/').'/public/storage/package-images/'.$res->image;
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_cat_sub()
    {
        $res = SubCategory::orderby("id", "desc")
            ->get();
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_cat_sub_edit($id)
    {
        $res = SubCategory::orderby("id", "desc")
            ->get();
        $commission = array();

        foreach ($res as $r) {
            $r->checked = get_sub_checked($r->id, $id);
            $r->commission = get_commission($r->id, $id);
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function select_categories()
    {
        $res = Category::orderby("id", "desc")->get();
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_categories(Request $request)
    {

        $res = Category::select("id","category_name","image","description")->orderby("id", "desc");

        if($request->keyword){
          $res = $res->where("category_name","like","%".$request->keyword."%")
                    ->orwhere("description","like","%".$request->keyword."%");
        }

        $res = $res->paginate(10);
        foreach ($res as $r) {
            $r->image = url('/') . '/public/storage/category-images/' . $r->image;
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_category(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'unique:categories,category_name'],
            'description' => ['required'],
            'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/category-images', $front_image);
            }
            $w = new Category();
            $w->category_name = $request->name;
            $w->description = $request->description;
            $w->image = $front_image;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Category Added Successfully"], 200);
        }
    }

    public function get_single_category($id)
    {
        $res = Category::find($id);
        $res->image = url('/') . '/public/storage/category-images/' . $res->image;
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_category(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $validator = Validator::make($data, [
                'name' => ['required', 'unique:categories,category_name,' . $request->id],
                'description' => ['required'],
                'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'name' => ['required', 'unique:categories,category_name,' . $request->id],
                'description' => ['required'],
            ]);
        }
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = Category::find($request->id);
            if ($request->hasfile('image')) {
                unlink(storage_path('app/public/category-images/' . $w->image));
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/category-images', $front_image);
            } else {
                $front_image = $w->image;
            }

            $w->category_name = $request->name;
            $w->description = $request->description;
            $w->image = $front_image;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Category Updated Successfully"], 200);
        }
    }

    public function delete_category($id)
    {
        Category::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Category Deleted Successfully"], 200);
    }

    public function add_category_to_homepage(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $type = $request->type;

        $c = SubCategory::find($id);
        if ($type == 1) {
            //  $c->online_services = $status;
        } elseif ($type == 2) {
            //  $c->offline_Services = $status;
        } elseif ($type == 3) {
            $c->category_slider = $status;
        } elseif ($type == 4) {
            $c->category_slider2 = $status;
        }

        $c->save();

        if ($status == 0) {
            $msg = "Removed Successfully";
        } else {
            $msg = "Added Successfully";
        }

        return response()->json(['status' => 'success', 'message' => $msg], 200);
    }

    public function get_sub_categories(Request $request)
    {
        $res = SubCategory::select("sub_categories.id","sub_categories.sub_category_name","sub_categories.description","sub_categories.image", "categories.category_name")
            ->join("categories", "sub_categories.category_id", "categories.id")
            ->orderby("id", "desc");

        $keyword = $request->keyword;

        if($request->category){
            $res = $res->where("category_id",$request->category);
        }

        if($request->keyword){
            $res = $res->where(function ($query) use ($keyword) {
                $query->where('sub_category_name', "like", "%$keyword%");
                $query->orWhere(function ($query) use ($keyword) {
                    $query->where('sub_categories.description', "like", "%$keyword%");
                });
            });
        }



       $res = $res->paginate(10);
        foreach ($res as $r) {
            $r->image = url('/') . '/public/storage/sub-category-images/' . $r->image;
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_sub_category(Request $request)
    {
        $messages = [
            'unique' => 'Subcategory name is already taken in this category, Please try different name/category',
        ];
        $data = $request->all();
        $subcat = $request->name;
        $cat = $request->categoryId;

        $validator = Validator::make($data, [
            'categoryId' => 'required',
            'name' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat) {
                return $query->where('category_id', $cat)
                    ->where('sub_category_name', $subcat);
            })],
            'description1' => ['required'],
            'image1' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            'description2' => ['required'],
            'image2' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            'description3' => ['required'],
            'image3' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            'description4' => ['required'],
            'image4' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
        ], $messages);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($request->hasfile('image1')) {
                $file1 = $request->file("image1");
                $front_image1 = time() . "_" . $file1->getClientOriginalName();
                $path = $file1->storeAs('public/sub-category-images', $front_image1);
            }
            if ($request->hasfile('image2')) {
                $file2 = $request->file("image2");
                $front_image2 = time() . "_" . $file2->getClientOriginalName();
                $path = $file2->storeAs('public/sub-category-images', $front_image2);
            }
            if ($request->hasfile('image3')) {
                $file3 = $request->file("image3");
                $front_image3 = time() . "_" . $file3->getClientOriginalName();
                $path = $file3->storeAs('public/sub-category-images', $front_image3);
            }
            if ($request->hasfile('image4')) {
                $file4 = $request->file("image4");
                $front_image4 = time() . "_" . $file4->getClientOriginalName();
                $path = $file4->storeAs('public/sub-category-images', $front_image4);
            }

            $w = new SubCategory();
            $w->category_id = $request->categoryId;
            $w->sub_category_name = $request->name;
            $w->description = $request->description1;
            $w->image = $front_image1;
            $w->description2 = $request->description2;
            $w->image2 = $front_image2;
            $w->description3 = $request->description3;
            $w->image3 = $front_image3;
            $w->description4 = $request->description4;
            $w->image4 = $front_image4;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Subcategory Added Successfully"], 200);
        }
    }

    public function get_single_sub_category($id)
    {
        $res = SubCategory::find($id);
        $res->image = url('/') . '/public/storage/sub-category-images/' . $res->image;
        $res->image2 = url('/') . '/public/storage/sub-category-images/' . $res->image2;
        $res->image3 = url('/') . '/public/storage/sub-category-images/' . $res->image3;
        $res->image4 = url('/') . '/public/storage/sub-category-images/' . $res->image4;

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_sub_category(Request $request)
    {
        $messages = [
            'unique' => 'Subcategory name is already taken in this category, Please try different name/category',
        ];

        $data = $request->all();
        $subcat = $request->name;
        $cat = $request->categoryId;
        $id = $request->id;
        if ($request->hasfile('image1')) {
            $validator = Validator::make($data, [
                'categoryId' => 'required',
                'name' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat, $id) {
                    return $query->where('category_id', $cat)
                        ->where('sub_category_name', $subcat);
                })->ignore($id, 'id')],
                'description1' => ['required'],
                'image1' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
                'description2' => ['required'],
                'description3' => ['required'],
                'description4' => ['required'],
            ], $messages);
        } elseif ($request->hasfile('image2')) {
            $validator = Validator::make($data, [
                'categoryId' => 'required',
                'name' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat, $id) {
                    return $query->where('category_id', $cat)
                        ->where('sub_category_name', $subcat);
                })->ignore($id, 'id')],
                'description1' => ['required'],
                'description2' => ['required'],
                'image2' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
                'description3' => ['required'],
                'description4' => ['required'],
            ], $messages);
        } elseif ($request->hasfile('image3')) {
            $validator = Validator::make($data, [
                'categoryId' => 'required',
                'name' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat, $id) {
                    return $query->where('category_id', $cat)
                        ->where('sub_category_name', $subcat);
                })->ignore($id, 'id')],
                'description1' => ['required'],
                'description2' => ['required'],
                'description3' => ['required'],
                'image3' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
                'description4' => ['required'],
            ], $messages);
        } elseif ($request->hasfile('image4')) {
            $validator = Validator::make($data, [
                'categoryId' => 'required',
                'name' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat, $id) {
                    return $query->where('category_id', $cat)
                        ->where('sub_category_name', $subcat);
                })->ignore($id, 'id')],
                'description1' => ['required'],
                'description2' => ['required'],
                'description3' => ['required'],
                'description4' => ['required'],
                'image4' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            ], $messages);
        } else {
            $validator = Validator::make($data, [
                'categoryId' => 'required',
                'name' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat, $id) {
                    return $query->where('category_id', $cat)
                        ->where('sub_category_name', $subcat);
                })->ignore($id, 'id')],
                'description1' => ['required'],
                'description2' => ['required'],
                'description3' => ['required'],
                'description4' => ['required'],
            ], $messages);
        }
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = SubCategory::find($request->id);
            if ($request->hasfile('image1')) {
                unlink(storage_path('app/public/sub-category-images/' . $w->image));
                $file1 = $request->file("image1");
                $front_image1 = time() . "_" . $file1->getClientOriginalName();
                $path = $file1->storeAs('public/sub-category-images', $front_image1);
            } else {
                $front_image1 = $w->image;
            }
            if ($request->hasfile('image2')) {
                unlink(storage_path('app/public/sub-category-images/' . $w->image2));
                $file2 = $request->file("image2");
                $front_image2 = time() . "_" . $file2->getClientOriginalName();
                $path = $file2->storeAs('public/sub-category-images', $front_image2);
            } else {
                $front_image2 = $w->image2;
            }
            if ($request->hasfile('image3')) {
                unlink(storage_path('app/public/sub-category-images/' . $w->image3));
                $file3 = $request->file("image3");
                $front_image3 = time() . "_" . $file3->getClientOriginalName();
                $path = $file3->storeAs('public/sub-category-images', $front_image3);
            } else {
                $front_image3 = $w->image3;
            }
            if ($request->hasfile('image4')) {
                unlink(storage_path('app/public/sub-category-images/' . $w->image4));
                $file4 = $request->file("image4");
                $front_image4 = time() . "_" . $file4->getClientOriginalName();
                $path = $file4->storeAs('public/sub-category-images', $front_image4);
            } else {
                $front_image4 = $w->image4;
            }

            $w->category_id = $request->categoryId;
            $w->sub_category_name = $request->name;
            $w->description = $request->description1;
            $w->image = $front_image1;
            $w->description2 = $request->description2;
            $w->image2 = $front_image2;
            $w->description3 = $request->description3;
            $w->image3 = $front_image3;
            $w->description4 = $request->description4;
            $w->image4 = $front_image4;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Subcategory Updated Successfully"], 200);
        }
    }

    public function delete_sub_category($id)
    {
        Subcategory::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Subcategory Deleted Successfully"], 200);
    }

    public function get_works(Request $request)
    {
        DB::enableQueryLog();
        $res = Work::select('works.id','works.description','works.name','works.min_budget','works.max_budget','works.status', 'users.name as user_name', "categories.category_name", "sub_categories.sub_category_name")
            ->join('users', 'works.user_id', 'users.id')
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->orderby("id", "desc")
            ->where("claim",0);

        if ($request->clientName) {
            $res = $res->where("works.name", "like", "%$request->clientName%");
        }
        if ($request->userName) {
            $res = $res->where("users.name", "like", "%$request->userName%");
        }
        if ($request->category) {
            $res = $res->where("works.category", $request->category);
        }
        if ($request->subcategory) {
            $res = $res->where("works.sub_category", $request->subcategory);
        }
        if ($request->status) {
            if ($request->status == "Pending") {
                $status = 0;
            } elseif ($request->status == "Progress") {
                $status = 1;
            } elseif ($request->status == "Deal") {
                $status = 2;
            } elseif ($request->status == "Completed") {
                $status = 3;
            } elseif ($request->status == "Delivered") {
                $status = 4;
            } elseif ($request->status == "Closed") {
                $status = 5;
            }
            $res = $res->where("works.status", $status);
        }


        $res = $res->paginate(10);

        foreach ($res as $r){
            if($r->status == 0){
                $r->status = "Pending";
            }if($r->status == 1){
                $r->status = "Progress";
            }if($r->status == 2){
                $r->status = "Deal";
            }if($r->status == 3){
                $r->status = "Completed";
            }if($r->status == 4){
                $r->status = "Delivered";
            }if($r->status == 5){
                $r->status = "Closed";
            }
        }


        return response()->json(['status' => 'success', 'res' => $res], 200);
    }
    public function get_claimed_works(Request $request)
    {
        DB::enableQueryLog();
        $res = Work::select('works.id','works.description','works.name','works.min_budget','works.max_budget','works.status', 'users.name as user_name', "categories.category_name", "sub_categories.sub_category_name")
            ->join('users', 'works.user_id', 'users.id')
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->orderby("id", "desc")
            ->where("claim",1);

        if ($request->clientName) {
            $res = $res->where("works.name", "like", "%$request->clientName%");
        }
        if ($request->userName) {
            $res = $res->where("users.name", "like", "%$request->userName%");
        }
        if ($request->category) {
            $res = $res->where("works.category", $request->category);
        }
        if ($request->subcategory) {
            $res = $res->where("works.sub_category", $request->subcategory);
        }
        if ($request->status) {
            if ($request->status == "Pending") {
                $status = 0;
            } elseif ($request->status == "Progress") {
                $status = 1;
            } elseif ($request->status == "Deal") {
                $status = 2;
            } elseif ($request->status == "Completed") {
                $status = 3;
            } elseif ($request->status == "Delivered") {
                $status = 4;
            } elseif ($request->status == "Closed") {
                $status = 5;
            }
            $res = $res->where("works.status", $status);
        }


        $res = $res->paginate(10);

        foreach ($res as $r){
            if($r->status == 0){
                $r->status = "Pending";
            }if($r->status == 1){
                $r->status = "Progress";
            }if($r->status == 2){
                $r->status = "Deal";
            }if($r->status == 3){
                $r->status = "Completed";
            }if($r->status == 4){
                $r->status = "Delivered";
            }if($r->status == 5){
                $r->status = "Closed";
            }
        }


        return response()->json(['status' => 'success', 'res' => $res], 200);
    }
    public function get_recent_works(Request $request)
    {
        $res = Work::select('works.*', 'users.name as user_name', "categories.category_name", "sub_categories.sub_category_name")
            ->join('users', 'works.user_id', 'users.id')
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->orderby("id", "desc")
            ->limit(5)
            ->get();

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_contact_data(Request $request)
    {
        DB::enableQueryLog();
        $res = ContactUs::orderby("id", "desc");

        if ($request->name) {
            $res = $res->where("name", "like", "%$request->name%");
        }
        if ($request->email) {
            $res = $res->where("email", "like", "%$request->email%");
        }
        if ($request->phone) {
            $res = $res->where("phone", "like", "%$request->phone%");
        }

        $res = $res->paginate(10);

        //dd(DB::getQueryLog());


        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_works_user($id,Request $request)
    {
        $user = User::find($id)->name;
        $res = Work::select('works.*', 'users.name as user_name', "categories.category_name", "sub_categories.sub_category_name")
            ->join('users', 'works.user_id', 'users.id')
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->where("works.user_id", $id)
            ->where("works.claim",0)
            ->orderby("id", "desc");

        if ($request->clientName) {
            $res = $res->where("works.name", "like", "%$request->clientName%");
        }
        if ($request->userName) {
            $res = $res->where("users.name", "like", "%$request->userName%");
        }
        if ($request->category) {
            $res = $res->where("works.category", $request->category);
        }
        if ($request->subcategory) {
            $res = $res->where("works.sub_category", $request->subcategory);
        }
        if ($request->status) {
            if ($request->status == "Pending") {
                $status = 0;
            } elseif ($request->status == "Progress") {
                $status = 1;
            } elseif ($request->status == "Deal") {
                $status = 2;
            } elseif ($request->status == "Completed") {
                $status = 3;
            } elseif ($request->status == "Delivered") {
                $status = 4;
            } elseif ($request->status == "Closed") {
                $status = 5;
            }
            $res = $res->where("works.status", $status);
        }

           $res = $res->paginate(10);
        foreach ($res as $r){
            if($r->status == 0){
                $r->status = "Pending";
            }if($r->status == 1){
                $r->status = "Progress";
            }if($r->status == 2){
                $r->status = "Deal";
            }if($r->status == 3){
                $r->status = "Completed";
            }if($r->status == 4){
                $r->status = "Delivered";
            }if($r->status == 5){
                $r->status = "Closed";
            }
        }

        return response()->json(['status' => 'success', 'res' => $res,'user'=>$user], 200);
    }

    public function get_claimed_works_user($id,Request $request)
    {
        $user = User::find($id)->name;
        $res = Work::select('works.*', 'users.name as user_name', "categories.category_name", "sub_categories.sub_category_name")
            ->join('users', 'works.user_id', 'users.id')
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->where("works.user_id", $id)
            ->where("works.claim",1)
            ->orderby("id", "desc");
        if ($request->clientName) {
            $res = $res->where("works.name", "like", "%$request->clientName%");
        }
        if ($request->userName) {
            $res = $res->where("users.name", "like", "%$request->userName%");
        }
        if ($request->category) {
            $res = $res->where("works.category", $request->category);
        }
        if ($request->subcategory) {
            $res = $res->where("works.sub_category", $request->subcategory);
        }
        if ($request->status) {
            if ($request->status == "Pending") {
                $status = 0;
            } elseif ($request->status == "Progress") {
                $status = 1;
            } elseif ($request->status == "Deal") {
                $status = 2;
            } elseif ($request->status == "Completed") {
                $status = 3;
            } elseif ($request->status == "Delivered") {
                $status = 4;
            } elseif ($request->status == "Closed") {
                $status = 5;
            }
            $res = $res->where("works.status", $status);
        }
            $res = $res->paginate(10);

        foreach ($res as $r){
            if($r->status == 0){
                $r->status = "Pending";
            }if($r->status == 1){
                $r->status = "Progress";
            }if($r->status == 2){
                $r->status = "Deal";
            }if($r->status == 3){
                $r->status = "Completed";
            }if($r->status == 4){
                $r->status = "Delivered";
            }if($r->status == 5){
                $r->status = "Closed";
            }
        }

        return response()->json(['status' => 'success', 'res' => $res,'user'=>$user], 200);
    }

    public function get_work_details($id)
    {
        $res = Work::select('works.*', 'users.name as username')
            ->join('users', 'works.user_id', 'users.id')
            ->where("works.id", $id)
            ->first();
        if ($res->image) {
            $res->image = url('/') . '/public/storage/work-images/' . $res->image;
        } else {
            $res->image = "";
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_all_work_followup(Request $request)
    {
        $res = WorkFollowup::select("work_followup.id","works.name","work_followup.comment","work_followup.created_at",
            DB::raw("from_unixtime(work_followup.next_followup_date,'%d/%m/%Y') as next_followup_date" ))
            ->join('works', "works.id", "work_followup.work_id")
            ->orderby("id", "desc")
            ->where("works.status", '<', 4);

        if ($request->clientName) {
            $res = $res->where("works.name", "like", "%$request->clientName%");
        }

        if ($request->dateTo && $request->dateFrom) {
            $res = $res->whereBetween("work_followup.next_followup_date", [strtotime($request->dateFrom), strtotime($request->dateTo)]);
        }
        if (!$request->dateTo && $request->dateFrom) {
            $res = $res->where("work_followup.next_followup_date", ">", strtotime($request->dateFrom));
        }

        $res = $res->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_work_followup($id)
    {
        $res = WorkFollowup::
        where("work_id", $id)
            ->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function delete_work_followup($id)
    {
        $res = WorkFollowup::where("id", $id)->delete();
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_single_work_followup($id)
    {
        $res = WorkFollowup::find($id);
        $res->next_followup_date = date("Y-m-d", $res->next_followup_date);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function take_work_followup($id, Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'comment' => ['required'],
            'nextFollowupDate' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = new WorkFollowup();
            $w->work_id = $id;
            $w->comment = $request->comment;
            $w->next_followup_date = strtotime($request->nextFollowupDate);
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Followup Taken Successfully"], 200);
        }
    }

    public function update_work_followup(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'comment' => ['required'],
            'nextFollowupDate' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = WorkFollowup::find($request->id);
            $w->comment = $request->comment;
            $w->next_followup_date = strtotime($request->nextFollowupDate);
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Followup Updated Successfully"], 200);
        }
    }

    public function get_user_payments(Request $request)
    {
        $user_id = $request->userId;
        $username = User::find($user_id)->name;
        $res = UserPayments::select("user_payments.*")
            ->where("user_payments.user_id", $user_id)
            ->orderby("id", "asc")
            ->paginate(10);
        $balance = 0;
        $new_arr = [];
        foreach ($res as $key => $value) {
            if ($key == 0) {
                $balance = $value->amount;
                $credit = $value->amount;
                $debit = "-";
            } else if ($value->transaction_type == "CREDIT") {
                $balance = $balance + $value->amount;
                $credit = $value->amount;
                $debit = "-";
            } else if ($value->transaction_type) {
                $balance = $balance - $value->amount;
                $debit = $value->amount;
                $credit = "-";
            }
            $value->balance = $balance;

            $new_arr[$key]['Sno'] = $key + 1;
            $new_arr[$key]['Date'] = date("d/m/Y h:i A", strtotime($value->created_at));
            $new_arr[$key]['Credit'] = $credit;
            $new_arr[$key]['Debit'] = $debit;
            $new_arr[$key]['Balance'] = $balance;

        }

        // $new_arr = json_encode($new_arr);

        return response()->json(['status' => 'success', 'res' => $res, 'user_name' => $username, 'new_arr' => $new_arr], 200);
    }


    public function export_user_account_statement(Request $request)
    {
        $user_id = $request->userId;
        $res = UserPayments::select("created_at", "amount", "transaction_type")
            ->where("user_payments.user_id", $user_id)
            ->where("user_payments.status", "!=", 0)
            ->paginate(10);
        $balance = 0;
        $new_arr = [];
        foreach ($res as $key => $value) {
            if ($key == 0) {
                $balance = $value->amount;
                $credit = $value->amount;
                $debit = "-";
            } else if ($value->transaction_type == "CREDIT") {
                $credit = $value->amount;
                $debit = "-";
                $balance = $balance + $value->amount;
            } else if ($value->transaction_type) {
                $debit = $value->amount;
                $credit = "-";
                $balance = $balance - $value->amount;
            }

        }
        return response()->json(['status' => 'success', 'res' => $new_arr], 200);

    }


    public function get_debit_requests(Request $request)
    {
        $res = DebitRequest::select("debit_requests.id","users.name", "users.id as user_id", "debit_requests.amount", "debit_requests.comment", "debit_requests.status","debit_requests.created_at")
            ->join("users", "debit_requests.user_id", "users.id")
            ->orderBy("id", "desc");

        if ($request->userName) {
            $res = $res->where("users.name", "like", "%$request->userName%");
        }
        if ($request->date) {
            $res = $res->whereDate("users.created_at", "$request->date");
        }

        $res = $res->paginate(10);

        foreach ($res as $r){
            if($r->status == 0){
                $r->status = "Pending";
            }else{
                $r->status = "Accepted";
            }
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function credit_amount(Request $request)
    {
//        $request_id = $request->requestId;
//        if($request_id) {
        $r = DB::table("debit_requests")->where("user_id", $request->userId)->update(["status" => 1]);

        //}
        $up1 = new UserPayments();
        $up1->user_id = $request->userId;
        $up1->amount = $request->amount;
        $up1->transaction_type = "DEBIT";
        $up1->status = 1;
        $up1->comments = $request->comment;
        $up1->save();
        return response()->json(['status' => 'success', 'message' => "Amount Credited Successfully"], 200);
    }

    public function update_work(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required'],
            'category' => ['required'],
            'subcategory' => ['required'],
            'description' => ['required'],
            'minBudget' => ['required'],
            'maxBudget' => ['required'],
            'status' => ['required']
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($request->status == 0 || $request->status == 1) {
                $amount = 0;
            } else {
                $amount = $request->amount;
            }
            $w = Work::find($request->id);
            $w->name = $request->name;
            $w->category = $request->category;
            $w->sub_category = $request->subcategory;
            $w->description = $request->description;
            $w->min_budget = $request->minBudget;
            $w->max_budget = $request->maxBudget;
            $w->status = $request->status;
            $w->amount = $amount;
            $w->save();

            if ($request->status == 3) {

                $upp = UserPackage::join("packages", "packages.id", "user_packages.package_id")
                    ->where(["user_id"=> $w->user_id])->first();

                if($upp->expiry > time() && $upp->status==1){
                    $pckg_comm = PackageCommission::where(['package_id' => $upp->package_id, "subcategory_id" => $request->subcategory])->first();
                    $amount = ($w->amount * $pckg_comm->commission) / 100;
                }else{
                    $pckg_comm = PackageCommission::where(['package_id' => 1, "subcategory_id" => $request->subcategory])->first();
                    $amount = ($w->amount * $pckg_comm->commission) / 100;
                }

                $up = new UserPayments();
                $up->user_id = $w->user_id;
              //  $up->work_id = 0;
                $up->amount = $amount;
                $up->transaction_type = "CREDIT";
                $up->save();
            }

            if ($request->status == 4) {
                DB::table("user_payments")->where(["user_id" => $w->user_id])->update(["status" => 1]);
//                $up = UserPayments::where(["user_id" => $w->user_id, "work_id" => $w->id])->first();
//                $up->status = 1;
//                $up->save();
            }

            return response()->json(['status' => 'success', 'message' => "Work Updated Successfully"], 200);
        }
    }

    public function change_status($id, Request $request)
    {
        $w = Work::find($id);
        $w->status = $request->status;
        $w->save();

        return response()->json(['status' => 'success', 'message' => "Status Changed Successfully"], 200);
    }

    public function close_work($id, Request $request)
    {
        $w = Work::find($id);
        $w->status = 5;
        $w->save();

        return response()->json(['status' => 'success', 'message' => "Status Changed Successfully"], 200);
    }

    public function get_business_slider()
    {
        $res = HomepageSection5::orderby("id", "desc")
            ->paginate(10);
        foreach ($res as $r) {
            $r->image = url('/') . '/public/storage/slider-images/' . $r->image;
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_single_business_slider($id)
    {
        $res = HomepageSection5::find($id);
        $res->image = url('/') . '/public/storage/slider-images/' . $res->image;
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_business_slider(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $validator = Validator::make($data, [
                'link' => ['required'],
                'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'link' => ['required'],
            ]);
        }
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = HomepageSection5::find($request->id);
            if ($request->hasfile('image')) {
                unlink(storage_path('app/public/slider-images/' . $w->image));
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/slider-images', $front_image);
            } else {
                $front_image = $w->image;
            }

            $w->link = $request->link;
            $w->image = $front_image;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Image Updated Successfully"], 200);
        }
    }

    public function add_business_slider(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'link' => ['required'],
            'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/slider-images', $front_image);
            }
            $w = new HomepageSection5();
            $w->link = $request->link;
            $w->image = $front_image;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Image Added Successfully"], 200);
        }
    }

    public function add_main_slider(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'link' => ['required'],
            'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/slider-images', $front_image);
            }
            $w = new HomepageSection1();
            $w->link = $request->link;
            $w->image = $front_image;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Image Added Successfully"], 200);
        }
    }

    public function delete_business_slider($id)
    {
        HomepageSection5::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Image Deleted Successfully"], 200);
    }

    public function get_single_main_slider($id)
    {
        $res = HomepageSection1::find($id);
        $res->image = url('/') . '/public/storage/slider-images/' . $res->image;
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_main_slider(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $validator = Validator::make($data, [
                'link' => ['required'],
                'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'link' => ['required'],
            ]);
        }
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = HomepageSection1::find($request->id);
            if ($request->hasfile('image')) {
                unlink(storage_path('app/public/slider-images/' . $w->image));
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/slider-images', $front_image);
            } else {
                $front_image = $w->image;
            }

            $w->link = $request->link;
            $w->image = $front_image;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Image Updated Successfully"], 200);
        }
    }

    public function delete_main_slider($id)
    {
        HomepageSection1::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Image Deleted Successfully"], 200);
    }

    public function get_main_slider()
    {
        $res = HomepageSection1::orderby("id", "desc")
            ->paginate(10);
        foreach ($res as $r) {
            $r->image = url('/') . '/public/storage/slider-images/' . $r->image;
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_how_we_work(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name1' => ['required'],
            'description1' => ['required'],
            'name2' => ['required'],
            'description2' => ['required'],
            'name3' => ['required'],
            'description3' => ['required'],

        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->id) {
                $w = HomepageSection2::find($request->id);
            } else {
                $w = new HomepageSection2();
            }
            $w->title1 = $request->name1;
            $w->description1 = $request->description1;
            $w->title2 = $request->name2;
            $w->description2 = $request->description2;
            $w->title3 = $request->name3;
            $w->description3 = $request->description3;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Added Successfully"], 200);
        }
    }

    public function get_how_we_works()
    {
        $res = HomepageSection2::orderby("id", "desc")
            ->first();

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_get_to_know_us(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'link' => ['required'],
            'description' => ['required'],
            'read_more_link' => ['required','url']
        ]);

        $url_parsed_arr = parse_url($request->link);


        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (isset($url_parsed_arr['path'])) {
            $path = $url_parsed_arr['path'];
            $path = explode("/", $path);
            if (!isset($url_parsed_arr['host']) || $url_parsed_arr['host'] != "www.youtube.com") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } elseif (!isset($path[1]) || $path[1] != "embed") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } else {
                if ($request->id) {
                    $w = HomepageSection3::find($request->id);
                } else {
                    $w = new HomepageSection3();
                }
                $w->link = $request->link;
                $w->description = $request->description;
                $w->read_more_link = $request->read_more_link;
                $w->save();
                return response()->json(['status' => 'success', 'message' => "Added Successfully"], 200);
            }
        } else {
            return response()->json(["status" => "error", "message" => "Please enter valid youtube embed url"], 400);
        }

    }

    public function get_get_to_know_us()
    {
        $res = HomepageSection3::orderby("id", "desc")
            ->first();

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_about_us(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'vision' => ['required'],
            'mission' => ['required'],
            'aim' => ['required']
        ]);


        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($request->id) {
                $w = HomepageSection4::find($request->id);
            } else {
                $w = new HomepageSection4();
            }
            $w->vision = $request->vision;
            $w->mission = $request->mission;
            $w->aim = $request->aim;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Added Successfully"], 200);
        }
    }

    public function get_about_us()
    {
        $res = HomepageSection4::orderby("id", "desc")
            ->first();

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_all_work_packages()
    {
        $res = WorkPackage::orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_work_packages(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required'],
            'package1' => ['required'],
            'package2' => ['required'],
            'package2' => ['required'],
        ]);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            $w = new WorkPackage();
            $w->name = $request->name;
            $w->package1 = $request->package1;
            $w->package2 = $request->package2;
            $w->package3 = $request->package3;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Package Added Successfully"], 200);
        }
    }

    public function get_single_work_packages($id)
    {
        $res = WorkPackage::find($id);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_work_packages(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required'],
            'package1' => ['required'],
            'package2' => ['required'],
            'package2' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = WorkPackage::find($request->id);
            $w->name = $request->name;
            $w->package1 = $request->package1;
            $w->package2 = $request->package2;
            $w->package3 = $request->package3;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Package Updated Successfully"], 200);
        }
    }

    public function delete_work_packages($id)
    {
        WorkPackage::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Package Deleted Successfully"], 200);
    }

    public function get_videos($id)
    {
        $res = SubcategoryVideo::where("subcategory_id", $id)->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_video(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => ['required'],
            'link' => ['required'],
        ]);

        $url_parsed_arr = parse_url($request->link);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (isset($url_parsed_arr['path'])) {
            $path = $url_parsed_arr['path'];
            $path = explode("/", $path);

            if (!isset($url_parsed_arr['host']) || $url_parsed_arr['host'] != "www.youtube.com") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } elseif (!isset($path[1]) || $path[1] != "embed") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } else {
                $w = new SubcategoryVideo();
                $w->subcategory_id = $request->subcategoryId;
                $w->title = $request->title;
                $w->link = $request->link;
                $w->save();
                return response()->json(['status' => 'success', 'message' => "Added Successfully"], 200);
            }
        }
    }

    public function get_single_video($id)
    {
        $res = SubcategoryVideo::find($id);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_video(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => ['required'],
            'link' => ['required'],
        ]);
        $url_parsed_arr = parse_url($request->link);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (isset($url_parsed_arr['path'])) {
            $path = $url_parsed_arr['path'];
            $path = explode("/", $path);

            if (!isset($url_parsed_arr['host']) || $url_parsed_arr['host'] != "www.youtube.com") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } elseif (!isset($path[1]) || $path[1] != "embed") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } else {
                $w = SubcategoryVideo::find($request->id);
                $w->title = $request->title;
                $w->link = $request->link;
                $w->save();
                return response()->json(['status' => 'success', 'message' => "Added Successfully"], 200);
            }
        }
    }

    public function delete_video($id)
    {
        SubcategoryVideo::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Deleted Successfully"], 200);
    }

    public function get_blogs(Request $request)
    {
        $res = Blog::orderby("id", "desc");
        $keyword = $request->keyword;
        if($request->keyword){
            $res = $res->where(function ($query) use ($keyword) {
                $query->where('name', "like", "%$keyword%");
                $query->orWhere(function ($query) use ($keyword) {
                    $query->where('description', "like", "%$keyword%");
                });
            });
        }
        $res = $res->paginate(10);
        foreach ($res as $r) {
            $r->image = url('/') . '/public/storage/blog-images/' . $r->image;
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_blog(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'unique:blogs,name'],
            'description' => ['required'],
            'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            'video' => ['required']
        ]);
        $url_parsed_arr = parse_url($request->video);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (isset($url_parsed_arr['path'])) {
            $path = $url_parsed_arr['path'];
            $path = explode("/", $path);

            if (!isset($url_parsed_arr['host']) || $url_parsed_arr['host'] != "www.youtube.com") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } elseif (!isset($path[1]) || $path[1] != "embed") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);

            } else {

                if ($request->hasfile('image')) {
                    $file = $request->file("image");
                    $front_image = time() . "_" . $file->getClientOriginalName();
                    $path = $file->storeAs('public/blog-images', $front_image);
                }

                $w = new Blog();
                $w->name = $request->name;
                $w->description = $request->description;
                $w->image = $front_image;
                $w->video = $request->video;
                $w->save();
                return response()->json(['status' => 'success', 'message' => "Blog Added Successfully"], 200);
            }
        }
    }

    public function get_single_blog($id)
    {
        $res = Blog::find($id);
        $res->image = url('/') . '/public/storage/blog-images/' . $res->image;
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }


    public function update_blog(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $validator = Validator::make($data, [
                'name' => ['required', 'unique:blogs,name,' . $request->id],
                'description' => ['required'],
                'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'name' => ['required', 'unique:blogs,name,' . $request->id],
                'description' => ['required'],
            ]);
        }
        $url_parsed_arr = parse_url($request->video);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (isset($url_parsed_arr['path'])) {
            $path = $url_parsed_arr['path'];
            $path = explode("/", $path);
            if (!isset($url_parsed_arr['host']) || $url_parsed_arr['host'] != "www.youtube.com") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);
            } elseif (!isset($path[1]) || $path[1] != "embed") {
                return response()->json(["status" => "error", "message" => "Please enter youtube embed url"], 400);
            } else {
                $w = Blog::find($request->id);
                if ($request->hasfile('image')) {
                    unlink(storage_path('app/public/blog-images/' . $w->image));
                    $file = $request->file("image");
                    $front_image = time() . "_" . $file->getClientOriginalName();
                    $path = $file->storeAs('public/blog-images', $front_image);
                } else {
                    $front_image = $w->image;
                }
                $w->name = $request->name;
                $w->description = $request->description;
                $w->image = $front_image;
                $w->video = $request->video;;
                $w->save();
                return response()->json(['status' => 'success', 'message' => "Blog Updated Successfully"], 200);
            }
        }
    }

    public function delete_blog($id)
    {
        Blog::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Blog Deleted Successfully"], 200);
    }

    public function get_total_data()
    {
        $total_users = User::where("user_type", 1)->count();
        $total_works = Work::count();
        $total_categories = Category::count();
        $total_subcategories = SubCategory::count();
        return response()->json(['status' => 'success',
            'total_users' => $total_users,
            'total_works' => $total_works,
            'total_categories' => $total_categories,
            'total_subcategories' => $total_subcategories,
        ], 200);
    }

    public function get_total_users($type)
    {
        if ($type == 1) {
            $today = \Carbon\Carbon::today();
            $week_start = $today->subDays(6)->format("Y-m-d");
            for ($i = 0; $i < 7; $i++) {
                $amt = get_daily_total_users($week_start);
                if ($amt == null) {
                    $amt = 0;
                }
                $dt = strtotime($week_start);
                $date[$i] = $week_start . "(" . date("D", $dt) . ")";
                $amount[$i] = $amt;
                $week_start = date("Y-m-d", strtotime("$week_start +1 day"));
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        } else if ($type == 2) {
            $cur_date = Carbon::now();
            $last_month_num = $cur_date->subMonth()->format('m');
            $last_day = new Carbon('last day of last month');
            $last_day = $last_day->format("Y-m-d");
            $month_arr_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            for ($i = 0; $i < 5; $i++) {
                $carbon = new Carbon('first day of last month');
                $weeks_dates[$i] = $carbon->addWeeks($i + 1)->format('Y-m-d');
            }

            for ($i = 0; $i < sizeof($weeks_dates); $i++) {

                /************ get visits **************/

                $date[$i] = $weeks_dates[$i];
                if ($i < 3) {
                    $amt = get_weekly_total_users($weeks_dates[$i], $weeks_dates[$i + 1]);
                }
                if ($i == 4) {
                    $date[$i] = $last_day;
                    $amt = get_weekly_total_users($weeks_dates[3], $last_day);
                }
                $amount[$i] = $amt;

                $date[$i] = $i + 1 . " Week";

                $last_month = $month_arr_en[$last_month_num - 1];

            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount, "last_month" => $last_month], 200);
        } else if ($type == 3) {
            $now = Carbon::now();
            $year = $now->year;
            $monthName1 = [];
            for ($i = 0; $i <= 11; $i++) {
                $monthName1[$i] = date('F', mktime(0, 0, 0, $i + 1, 10));
            }
            $monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for ($i = 0; $i < sizeof($monthName); $i++) {

                $f = new Carbon("first day of $monthName1[$i] $year");
                $first_day[$i] = $f->format("Y-m-d");

                $l = new Carbon("last day of $monthName1[$i] $year");
                $last_day[$i] = $l->format("Y-m-d");

                /************ get visits **************/

                $date[$i] = $monthName[$i];

                $amt = get_weekly_total_users($first_day[$i], $last_day[$i]);
                $amount[$i] = $amt;
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        }
    }

    public function get_total_works($type)
    {
        if ($type == 1) {
            $today = \Carbon\Carbon::today();
            $week_start = $today->subDays(6)->format("Y-m-d");
            for ($i = 0; $i < 7; $i++) {
                $amt = get_daily_total_works($week_start);
                if ($amt == null) {
                    $amt = 0;
                }
                $dt = strtotime($week_start);
                $date[$i] = $week_start . "(" . date("D", $dt) . ")";
                $amount[$i] = $amt;
                $week_start = date("Y-m-d", strtotime("$week_start +1 day"));
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        } else if ($type == 2) {
            $cur_date = Carbon::now();
            $last_month_num = $cur_date->subMonth()->format('m');
            $last_day = new Carbon('last day of last month');
            $last_day = $last_day->format("Y-m-d");
            $month_arr_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            for ($i = 0; $i < 5; $i++) {
                $carbon = new Carbon('first day of last month');
                $weeks_dates[$i] = $carbon->addWeeks($i + 1)->format('Y-m-d');
            }

            for ($i = 0; $i < sizeof($weeks_dates); $i++) {

                /************ get visits **************/

                $date[$i] = $weeks_dates[$i];
                if ($i < 3) {
                    $amt = get_weekly_total_works($weeks_dates[$i], $weeks_dates[$i + 1]);
                }
                if ($i == 4) {
                    $date[$i] = $last_day;
                    $amt = get_weekly_total_works($weeks_dates[3], $last_day);
                }
                $amount[$i] = $amt;

                $date[$i] = $i + 1 . " Week";

                $last_month = $month_arr_en[$last_month_num - 1];

            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount, "last_month" => $last_month], 200);
        } else if ($type == 3) {
            $now = Carbon::now();
            $year = $now->year;
            $monthName1 = [];
            for ($i = 0; $i <= 11; $i++) {
                $monthName1[$i] = date('F', mktime(0, 0, 0, $i + 1, 10));
            }
            $monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for ($i = 0; $i < sizeof($monthName); $i++) {

                $f = new Carbon("first day of $monthName1[$i] $year");
                $first_day[$i] = $f->format("Y-m-d");

                $l = new Carbon("last day of $monthName1[$i] $year");
                $last_day[$i] = $l->format("Y-m-d");

                /************ get visits **************/

                $date[$i] = $monthName[$i];

                $amt = get_weekly_total_works($first_day[$i], $last_day[$i]);
                $amount[$i] = $amt;
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        }
    }

    public function add_data($type, Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'description' => ['required'],
        ]);


        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($type == 1) {
                if ($request->id) {
                    $w = PrivacyPolicy::find($request->id);
                } else {
                    $w = new PrivacyPolicy();
                }
            } else {
                if ($request->id) {
                    $w = TermsCondition::find($request->id);
                } else {
                    $w = new TermsCondition();
                }
            }
            $w->description = $request->description;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Added Successfully"], 200);
        }
    }

    public function get_data($type)
    {
        if ($type == 1) {
            $res = PrivacyPolicy::first();
        } else {
            $res = TermsCondition::first();
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_faqs()
    {
        $res = Faq::orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_faq(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'question' => ['required'],
            'answer' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = new Faq();
            $w->question = $request->question;
            $w->answer = $request->answer;;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Faqs Added Successfully"], 200);
        }
    }

    public function get_single_faq($id)
    {
        $res = Faq::find($id);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_faq(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'question' => ['required'],
            'answer' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = Faq::find($request->id);
            $w->question = $request->question;
            $w->answer = $request->answer;;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Faq Updated Successfully"], 200);
        }
    }

    public function delete_faq($id)
    {
        Faq::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Faq Deleted Successfully"], 200);
    }

    public function get_careers()
    {
        $res = Career::orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function add_career(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => ['required'],
            'description' => ['required'],
            'post' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = new Career();
            $w->title = $request->title;
            $w->description = $request->description;
            $w->total_post = $request->post;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Job Added Successfully"], 200);
        }
    }

    public function get_single_career($id)
    {
        $res = Career::find($id);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function update_career(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => ['required'],
            'description' => ['required'],
            'post' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $w = Career::find($request->id);
            $w->title = $request->title;
            $w->description = $request->description;
            $w->total_post = $request->post;
            $w->save();
            return response()->json(['status' => 'success', 'message' => "Job Updated Successfully"], 200);
        }
    }

    public function delete_career($id)
    {
        Career::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Job Deleted Successfully"], 200);
    }
}

