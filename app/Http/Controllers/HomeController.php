<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\Blog;
use App\Models\Career;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\HomepageSection1;
use App\Models\HomepageSection2;
use App\Models\HomepageSection3;
use App\Models\HomepageSection4;
use App\Models\HomepageSection5;
use App\Models\Otp;
use App\Models\PrivacyPolicy;
use App\Models\SubCategory;
use App\Models\SubcategoryVideo;
use App\Models\TermsCondition;
use App\Models\UserPackage;
use App\Models\UserPayments;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $categories = SubCategory::where("category_slider", 1)->orderby("id", "desc")->get();
        $categories2 = SubCategory::where("category_slider2", 1)->orderby("id", "desc")->get();
        $howwework = HomepageSection2::first();
        $section1 = HomepageSection1::all();
        $getToKnow = HomepageSection3::first();
        $aboutus = HomepageSection4::first();

        return view('index', ['categories' => $categories, 'aboutus' => $aboutus, 'categories2' => $categories2, 'howwework' => $howwework, "getToKnow" => $getToKnow, "section1" => $section1]);
    }

    public function categories()
    {
        $categories = SubCategory::orderby("id", "desc")->get();
        return view('categories', ['categories' => $categories]);
    }

    public function get_achievers(Request $request)
    {
        $name = $request->name;
        $city = $request->city;
        $state = $request->state;

        $achievers = UserPayments::join("users", "users.id", "user_payments.user_id")
            ->join("cities", "cities.id", "users.city_id")
            ->join("states", "states.id", "users.state_id")
            ->select("users.name", "user_payments.amount", "users.pin_code", "cities.name as city", "states.name as state", DB::raw("sum(amount) as total_amt"))
            ->where(["status" => 1, "transaction_type" => "CREDIT"]);

        if ($name) {
            $achievers = $achievers->where("users.name", "like", "%$name%");
        }
        if ($state) {
            $achievers = $achievers->where("users.state_id", $request->state);
        }
        if ($city) {
            $achievers = $achievers->where("users.city_id", $request->city);
        }

        $achievers = $achievers->groupby("users.id")
            ->orderby("total_amt", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $achievers], 200);
    }

    public function category_details($id)
    {
        $category = Category::find($id);
        $subcategories = SubCategory::where("category_id", $id)->get();
        return view('category-details', ['subcategories' => $subcategories, "category" => $category]);
    }

    public function subcategory_details($id)
    {
        $subcategory = SubCategory::find($id);
        $videos = SubcategoryVideo::where("subcategory_id", $id)->get();
        // dd($videos->toArray());
        return view('subcategory-details', ['subcategory' => $subcategory, "videos" => $videos]);
    }


    public function user_login()
    {
        return view('login');
    }

    public function blog_details($id)
    {
        $res = Blog::find($id);
        return view('single-blog', ["blog" => $res]);
    }

    public function blogs()
    {
        $res = Blog::all();
        return view('blog', ["blog" => $res]);
    }

    public function faqs()
    {
        $res = Faq::all();
        return view('faq', ["faq" => $res]);
    }

    public function careers()
    {
        $res = Career::all();
        return view('career', ["career" => $res]);
    }

    public function get_blogs()
    {
        $res = Blog::paginate(12);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function privacy_policy()
    {
        $res = PrivacyPolicy::first();
        return view('privacy-policy', ["res" => $res]);
    }

    public function terms_conditions()
    {
        $res = TermsCondition::first();
        return view('terms-and-conditions', ["res" => $res]);
    }


    public function achievers()
    {
        return view('achievers');
    }

    public function user_signup()
    {
        return view('signup');
    }

    public function contact_us()
    {
        return view('contact-us');
    }

    public function about_us()
    {
        return view('about-us');
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

            Auth::login($u);
            Otp::where("phone", "+91" . $request->phone)->delete();

            $otps = rand(100000, 999999);
            $email = $u->email;
            $sub = "Winning Ways | User Registration";
            $type = 1; //registration
            $msg = "You have registered successfully on Winning Ways";
            send_email($email, $sub, $type, $msg);
            send__user_otp("$u->phone", "You have successfully registered on Winning Ways", $otps);
            return response()->json(["status" => "success", "message" => "User registered successfully"], 200);
        }
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

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        } else {

            if (is_numeric($request->get('email'))) {
                if (Auth::attempt(['phone' => '+91' . $request->email, 'password' => $request->password, 'user_type' => $request->userType])) {
                    return response()->json(['status' => 'success', 'message' => 'Success', 'res' => Auth::user()], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Sorry, you entered an incorrect phone or password.'], 400);
                }
            } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => $request->userType])) {
                    return response()->json(['status' => 'success', 'message' => 'Success', 'res' => Auth::user()], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Sorry, you entered an incorrect email or password.'], 400);
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Success', 'res' => Auth::user()], 200);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function send_email(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255']
        ]);
        $u = User::where("email", $request->email)->first();

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (!$u) {
            return response()->json(["status" => "error", "message" => "This email is not registered"], 400);
        } else {
            $msg = "Please use below link to reset your password";
            $token = md5(uniqid(1000, 9999));
            $expiry = time() + (60 * 10);

            DB::table("reset_password")->insert(["email" => $request->email, "token" => $token, "time" => time(), "expiry" => $expiry]);
            Mail::to($request->email)
                ->send(new ForgotPassword($token));
            return response()->json(['status' => 'success', "message" => "We have sent a link on your email, please reset your password using that link"], 200);
        }

    }

    public function reset_password($id)
    {
        return view("reset-password", ["token" => $id]);
    }

    public function change_password(Request $request)
    {
        $res = DB::table("reset_password")->where(["token" => $request->token])->where("expiry", ">", time())->first();
        if ($res) {
            $u = User::where("email", $res->email)->first();
            $u->password = bcrypt($request->npassword);
            $u->save();

            DB::table("reset_password")->where("token", $request->token)->delete();

            Auth::login($u);
            return response()->json(['status' => 'success', 'message' => 'Success'], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => 'Link is invalid or expired'], 400);
        }
    }

    public function change_password_otp(Request $request)
    {
        $otp = $request->otp;
        $phone = "+91" . $request->email;
        $o = Otp::where(["phone" => $phone, "otp" => $otp])->where("expiry", '>', time())->first();

        if ($o) {
            $u = User::where("phone", $o->phone)->orderby("id","desc")->first();
            $u->password = bcrypt($request->npassword);
            $u->save();
            DB::table("otp")->where("phone", $o->phone)->delete();
            Auth::login($u);
            return response()->json(['status' => 'success', 'message' => 'Success'], 200);
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'You have entered wrong otp or Otp is expired'], 400);
        }

    }

    public function check_email(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            return response()->json(["status" => "success", "message" => "email not registered"], 200);

        }
    }

    public function check_otp(Request $request)
    {
        $otp = $request->otp;
        $phone = "+91" . $request->phone;
        $o = Otp::where(["phone" => $phone, "otp" => $otp])->where("expiry", '>', time())->first();
        if ($o) {
            return response()->json(['status' => 'success'], 200);
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
            $phone = "+91" . $request->phone;
            $u = User::where("phone", $phone)->first();
            if (!$u) {
                return response()->json([
                    'status' => 'error', "message" => "Phone not registered"
                ], 400);
            } else {
                $otp = rand(100000, 999999);

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

}
