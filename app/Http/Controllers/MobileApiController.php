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
use App\Models\Otp;
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


class MobileApiController extends Controller
{
    public function login(Request $request)
    {
        //  dd($request);
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

    public function check_otp(Request $request)
    {
        $otp = $request->otp;

        $phone = "+91" . $request->phone;
        $o = Otp::where(["phone" => $phone, "otp" => $otp])->where("expiry", '>', time())->first();
        if ($o) {
            return response()->json(['status' => 'success', 'phone' => $phone], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'You have entered wrong otp or Otp is expired'], 400);
        }
    }

    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        } else {
            $otp = rand(100000, 999999);
            $phone = "+91" . $request->phone;
            $u = User::where("phone", $phone)->first();
            if ($u) {
                return response()->json([
                    'status' => 'error', "message" => "Phone already registered"
                ], 400);
            } else {
                $msg = "Verification code is " . $otp;

                $res = send__user_otp($phone, $msg, $otp);
                if ($res->type == "success") {
                    $o = new Otp();
                    $o->phone = $phone;
                    $o->otp = $otp;
                    $o->expiry = time() + (60 * 5);
                    $o->save();

                    return response()->json([
                        'status' => 'success', "otp" => $otp
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error', "message" => $res->message
                    ], 400);
                }
            }
        }
    }

    public function send_forget_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        } else {
            $otp = rand(100000, 999999);
            $phone = "+91" . $request->phone;
            $u = User::where("phone", $phone)->first();
            if (!$u) {
                return response()->json([
                    'status' => 'error', "message" => "Phone is not registered"
                ], 400);
            } else {
                $msg = "Verification code is " . $otp;
                $res = send__user_otp($phone, $msg, $otp);
                if ($res->type == "success") {
                    $o = new Otp();
                    $o->phone = $phone;
                    $o->otp = $otp;
                    $o->expiry = time() + (60 * 5);
                    $o->save();

                    return response()->json([
                        'status' => 'success', "otp" => $otp
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error', "message" => $res->message
                    ], 400);
                }
            }
        }
    }

    public function change_password(Request $request)
    {
        $u = User::where("phone", $request->phone)->first();
        $u->password = bcrypt($request->password);
        $u->save();
        Otp::where("phone", "+91" . $request->phone)->delete();
        Auth::login($u);
        return response()->json(['status' => 'success', 'message' => 'Success'], 200);

    }

    public function signup(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'unique:users'],
                'phone' => ['required', 'unique:users'],
                'password' => ['required', 'string', 'max:255'],
                'state' => ['required'],
                'rpassword' => ['required', 'string', 'max:255'],
                'city' => ['required'],
                'pinCode' => ['required'],
                'address' => ['required'],
            ]
        );

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $u = new User();
            $u->name = $request->name;
            $u->phone = $request->phone;
            $u->email = $request->email;
            $u->password = bcrypt($request->password);
            $u->phone = "+91" . $request->phone;
            $u->user_type = $request->userType;
            $u->address = $request->address;
            $u->pin_code = $request->pinCode;
            $u->state_id = $request->state;
            $u->city_id = $request->city;
            $u->save();

            $up = new UserPackage();
            $up->user_id = $u->id;
            $up->package_id = 1;
            $up->expiry = null;
            $up->status = 1;
            $up->save();
            $user = Auth::login($u);
            $tokenResult = $u->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();


            Otp::where("phone", "+91" . $request->phone)->delete();

            $otps = rand(100000, 999999);
            $email = $u->email;
            $sub = "Winning Ways | User Registration";
            $type = 1; //registration
            $msg = "You have registered successfully on Winning Ways";
            send_email($email, $sub, $type, $msg);
            send__user_otp("$u->phone", "You have successfully registered on Winning Ways", $otps);

            return response()->json([
                "status" => "success",
                "message" => "User Registered successfully",
                'access_token' => $tokenResult->accessToken,
                'user' => $u,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 200);

        }
    }

    public function get_cities($state_id)
    {

        $city = DB::table("cities")->where("state_id", $state_id)->get();

        return response()->json(['status' => 'success', 'res' => $city], 200);
    }

    public function get_states()
    {
        $state = "";
        $city = DB::table("states")->where("country_id", 101)->get();
        return response()->json(['status' => 'success', 'res' => $city], 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => 'success'], 200);

    }

    public function get_services()
    {
        $res = get_services(2);
        foreach ($res as $r){
            $r->image = url('/').'/public/storage/category-images/'.$r->image;
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }
    public function subcategory_details($id)
    {
        $subcategory = SubCategory::find($id);
        $videos = SubcategoryVideo::where("subcategory_id", $id)->get();
        if($subcategory) {
            $subcategory->image = url('/') . '/public/storage/sub-category-images/' . $subcategory->image;
            $subcategory->image2 = url('/') . '/public/storage/sub-category-images/' . $subcategory->image2;
            $subcategory->image3 = url('/') . '/public/storage/sub-category-images/' . $subcategory->image3;
            $subcategory->image4 = url('/') . '/public/storage/sub-category-images/' . $subcategory->image4;
        }
        return response()->json(['status' => 'success', 'subcategory' => $subcategory, "videos" => $videos]);

    }
    public function get_service_details($id)
    {
        $category = Category::find($id);
        $category->image = url('/').'/public/storage/category-images/'.$category->image;
        $subcategories = SubCategory::where("category_id", $id)->get();
        foreach ($subcategories as $sc){
            $sc->image = url('/').'/public/storage/sub-category-images/'.$sc->image;
        }
        return response()->json(['status' => 'success', 'subcategories' => $subcategories, "category" => $category]);
    }

    public function work_post_by_user(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'category' => ['required'],
                    'subcategory' => ['required'],
                    'description' => ['required'],
                    'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000']
                ]
            );
        } else {
            $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'category' => ['required'],
                    'subcategory' => ['required'],
                    'description' => ['required'],
                ]
            );
        }
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/work-images', $front_image);
            } else {
                $front_image = "";
            }

            $u = new Work();
            $u->user_id = Auth::user()->id;
            $u->name = $request->name;
            $u->phone = $request->phone;
            $u->category = $request->category;
            $u->sub_category = $request->subcategory;
            $u->min_budget = $request->minBudget;
            $u->max_budget = $request->maxBudget;
            $u->description = $request->description;
            $u->image = $front_image;
            $u->save();

            return response()->json(["status" => "success", "message" => "Work posted successfully"], 200);
        }
    }

    public function claim_work_by_user(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'category' => ['required'],
                    'subcategory' => ['required'],
                    'description' => ['required'],
                    'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000']
                ]
            );
        } else {
            $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'category' => ['required'],
                    'subcategory' => ['required'],
                    'description' => ['required'],
                ]
            );
        }
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/work-images', $front_image);
            } else {
                $front_image = "";
            }

            $u = new Work();
            $u->user_id = Auth::user()->id;
            $u->name = $request->name;
            $u->phone = $request->phone;
            $u->category = $request->category;
            $u->sub_category = $request->subcategory;
            $u->min_budget = $request->minBudget;
            $u->max_budget = $request->maxBudget;
            $u->description = $request->description;
            $u->image = $front_image;
            $u->claim = 1;
            $u->save();

            return response()->json(["status" => "success", "message" => "Work claimed successfully"], 200);
        }
    }

    public function get_my_works($status)
    {
        $res = Work::select('works.*', "categories.category_name", "sub_categories.sub_category_name")
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->where("user_id", Auth::user()->id)
            ->where("claim", 0);
        if ($status != 5) {
            $res = $res->where("status", $status);
        }
        $res = $res->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_my_claim_works($status)
    {
        $res = Work::select('works.*', "categories.category_name", "sub_categories.sub_category_name")
            ->join("categories", "categories.id", "works.category")
            ->join("sub_categories", "sub_categories.id", "works.sub_category")
            ->where("user_id", Auth::user()->id)
            ->where("claim", 1);
        if ($status != 5) {
            $res = $res->where("status", $status);
        }
        $res = $res->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function request_for_debit(Request $request)
    {
        $dbt = new DebitRequest();
        $dbt->user_id = Auth::user()->id;
        $dbt->amount = $request->amount;
        $dbt->comment = $request->comment;
        $dbt->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function get_user_payments(Request $request)
    {
        $user_id = Auth::user()->id;
        $res = UserPayments::select("user_payments.*")
            ->where("user_payments.user_id", $user_id)
            ->where("user_payments.status", "!=", 0)
            ->orderby("id", "asc")
            ->paginate(10);
        $balance = 0;
        foreach ($res as $key => $value) {
            if ($key == 0) {
                $balance = $value->amount;
            } else if ($value->transaction_type == "CREDIT") {
                $balance = $balance + $value->amount;
            } else if ($value->transaction_type) {
                $balance = $balance - $value->amount;
            }
            $value->balance = $balance;
        }

        return response()->json(['status' => 'success', 'res' => $res], 200);

    }

    public function get_debit_requests(Request $request)
    {
        $user_id = Auth::user()->id;
        $res = DebitRequest::where("debit_requests.user_id", $user_id)
            ->orderby("id", "desc")
            ->paginate(10);

        return response()->json(['status' => 'success', 'res' => $res], 200);

    }

    public function get_achievers()
    {
        $achievers = UserPayments::join("users", "users.id", "user_payments.user_id")
            ->select("user_payments.*", "users.name", DB::raw("sum(amount) as total_amt"))
            ->where(["status" => 1, "transaction_type" => "CREDIT"])
            ->groupby("users.id")
            ->orderby("total_amt", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $achievers], 200);
    }
    public function get_packages()
    {
        $res = Package::all();
        $selected_plan = "";
        $selected_plan1 = "";
        $selected_plan_validity = "";
        $selected_plan_status = "";
        if (Auth::user()) {
            $selected_plan = UserPackage::where("user_id", Auth::user()->id)->first();
        }
        if ($selected_plan) {
            $selected_plan1 = $selected_plan->package_id;
            $selected_plan_validity = $selected_plan->expiry;
            $selected_plan_status = $selected_plan->status;
        }

        foreach ($res as $r){
            $r->image = url('/')."/public/storage/package-images/".$r->image;
        }

        return response()->json(['status' => 'success', 'res' => $res, 'selected_plan' => $selected_plan1, "selected_plan_validity" => $selected_plan_validity, "selected_plan_status" => $selected_plan_status], 200);
    }

    public function get_single_package($id)
    {

        $work_package = Package::select("packages.*", "package_commission.commission", "sub_categories.sub_category_name", "package_commission.subcategory_id")
            ->join("package_commission", "package_commission.package_id", "packages.id")
            ->join("sub_categories", "package_commission.subcategory_id", "sub_categories.id")
            ->where("packages.id", $id)
            ->paginate(20);
        foreach ($work_package as $r){
            $r->image = url('/')."/public/storage/package-images/".$r->image;
        }
        return response()->json(['status' => 'success', "work_package" => $work_package], 200);
    }
    public function add_contact_us(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email'],
                'phone' => ['required'],
                'subject' => ['required'],
                'message' => ['required'],
            ]
        );

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $u = new ContactUs();
            $u->name = $request->name;
            $u->email = $request->email;
            $u->subject = $request->subject;
            $u->phone = "+91" . $request->phone;
            $u->message = $request->message;
            $u->save();

            return response()->json(["status" => "success", "message" => "You have successfully submitted details"], 200);
        }
    }

    public function purchase_packages($id)
    {
        $res = UserPackage::where("user_id", Auth::user()->id)->first();
        if (!$res) {
            $res = new UserPackage();
        }
        $res->user_id = Auth::user()->id;
        $res->package_id = $id;
        $res->status = 0;
        $res->save();
        return response()->json(['status' => 'success', 'message' => "Plan Requested Successfully"], 200);
    }

}

