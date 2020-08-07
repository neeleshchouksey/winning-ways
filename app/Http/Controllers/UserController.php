<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExportExcel;
use App\Models\DebitRequest;
use App\Models\Otp;
use App\Models\Package;
use App\Models\SubCategory;
use App\Models\UserPackage;
use App\Models\UserPayments;
use App\Models\Work;
use App\Models\WorkPackage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Excel;
class UserController extends Controller
{


    public function work_post()
    {
        return view('work-post');
    }
    public function claim_work()
    {
        return view('claim-work');
    }

    public function my_works()
    {
        return view('my-works');
    }
    public function my_claimed_works()
    {
        return view('my-claimed-works');
    }

    public function my_account()
    {
        return view('my-account');
    }

    public function debit_requests()
    {
        return view('debit-requests');
    }

    public function packages()
    {
        return view('packages');
    }

    public function select_sub_categories($id){
        $res = SubCategory::where("category_id",$id)->orderby("id", "desc")->get();
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
            ->orderby("id","asc")
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

    public function get_my_works($status)
    {
        $res = Work::select('works.*',"categories.category_name","sub_categories.sub_category_name")
            ->join("categories","categories.id","works.category")
            ->join("sub_categories","sub_categories.id","works.sub_category")
            ->where("user_id", Auth::user()->id)
            ->where("claim",0);
        if ($status != 5) {
            $res = $res->where("status", $status);
        }
        $res = $res->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }

    public function get_my_claim_works($status)
    {
        $res = Work::select('works.*',"categories.category_name","sub_categories.sub_category_name")
            ->join("categories","categories.id","works.category")
            ->join("sub_categories","sub_categories.id","works.sub_category")
            ->where("user_id", Auth::user()->id)
            ->where("claim",1);
        if ($status != 5) {
            $res = $res->where("status", $status);
        }
        $res = $res->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $res], 200);
    }


    public function export_user_account_statement(Request $request)
    {
        $user_id = Auth::user()->id;
        $res = UserPayments::select("created_at","amount","transaction_type")
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

            $new_arr[$key]['id'] = $key+1;
            $new_arr[$key]['date'] = date("d/m/Y h:i A",strtotime($value->created_at));
            $new_arr[$key]['credit'] = $credit;
            $new_arr[$key]['debit']  = $debit;
            $new_arr[$key]['balance']  = $balance;

        }

     //   dd($new_arr);

        $header = ["S. No.","Date","Credit","Debit","Balance"];
        return Excel::download( new ExportExcel ($new_arr,$header),"statement.xlsx");


    }

    public function get_debit_requests(Request $request)
    {
        $user_id = Auth::user()->id;
        $res = DebitRequest::where("debit_requests.user_id", $user_id)
            ->orderby("id","desc")
            ->paginate(10);

        return response()->json(['status' => 'success', 'res' => $res], 200);

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
        return response()->json(['status' => 'success', 'res' => $res, 'selected_plan' => $selected_plan1, "selected_plan_validity" => $selected_plan_validity,"selected_plan_status"=>$selected_plan_status], 200);

    }
    public function get_single_package($id,Request $request)
    {

        $work_package = Package::select("packages.*","package_commission.commission","sub_categories.sub_category_name","package_commission.subcategory_id")
                                ->join("package_commission","package_commission.package_id","packages.id")
                                ->join("sub_categories","package_commission.subcategory_id","sub_categories.id")
                                ->where("packages.id",$id);
        if($request->service){
            $work_package = $work_package->where("sub_categories.sub_category_name","like","%$request->service%");
        }
        $work_package = $work_package->paginate(10);

        return response()->json(['status' => 'success', "work_package"=>$work_package], 200);

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

    public function purchase_packages($id)
    {
        $p = Package::find($id);
        $res = UserPackage::where("user_id", Auth::user()->id)->first();
        if (!$res) {
            $res = new UserPackage();
        }
        $res->user_id = Auth::user()->id;
        $res->package_id = $id;
        $res->status = 0;
        $res->save();
        return response()->json(['status' => 'success', 'message' => "Plan Purchased Successfully"], 200);
    }

    public function work_post_by_user(Request $request)
    {
        $data = $request->all();
        if($request->hasfile('image')) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'category' => ['required'],
                    'subcategory' => ['required'],
                    'description' => ['required'],
                    'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000']
                ]
            );
        }else{
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
            }else{
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
        if($request->hasfile('image')) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'category' => ['required'],
                    'subcategory' => ['required'],
                    'description' => ['required'],
                    'image' => ['mimes:jpeg,jpg,png,JPG,JPEG,PNG|required|max:10000']
                ]
            );
        }else{
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
            }else{
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


}
