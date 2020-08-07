<?php

use App\Mail\SendEmail;

use App\Models\Category;
use App\Models\HomepageSection5;
use App\Models\PackageCommission;
use App\Models\Work;
use App\User;
use Illuminate\Support\Facades\Auth;




if (!function_exists('get_business_slider')) {
    function get_business_slider()
    {
        return $section5 = HomepageSection5::all();

    }
}
if (!function_exists('get_daily_total_users')) {
    function get_daily_total_users($date)
    {
        return $res = User::whereDate("created_at", $date)->count();

    }
}

if (!function_exists('get_weekly_total_users')) {
    function get_weekly_total_users($date1, $date2)
    {
        return $res = User::whereDate("created_at", ">=", $date1)
            ->whereDate("created_at", "<=", $date2)
            ->count();

    }
}
if (!function_exists('get_daily_total_works')) {
    function get_daily_total_works($date)
    {
        return $res = Work::whereDate("created_at", $date)->count();

    }
}

if (!function_exists('get_weekly_total_works')) {
    function get_weekly_total_works($date1, $date2)
    {
        return $res = Work::whereDate("created_at", ">=", $date1)
            ->whereDate("created_at", "<=", $date2)
            ->count();

    }
}

if (!function_exists('get_services')) {
    function get_services($type)
    {
        if($type == 2){
            $res = Category::get();
        }elseif($type == 1){
            $res = Category::where("online_services",1)->get();
        }else{
            $res = Category::where("offline_services",1)->get();
        }
        return $res;

    }
}
if (!function_exists('get_sub_checked')) {
    function get_sub_checked($sid, $pid)
    {
        $sub = PackageCommission::where(["package_id" => $pid, "subcategory_id" => $sid])
            ->first();
        if ($sub) {
            return 1;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_commission')) {
    function get_commission($sid, $pid)
    {
        $sub = PackageCommission::where(["package_id" => $pid, "subcategory_id" => $sid])
            ->first();
        if ($sub) {
            return $sub->commission;
        } else {
            return 0;
        }
    }
}
if (!function_exists('send_email')) {
    function send_email($email, $sub, $type, $msg)
    {
        return Mail::to($email)
            ->send(new SendEmail($type, $msg, $sub));
    }
}
if (!function_exists('send__user_otp')) {
    function send__user_otp($phone, $msg, $otp)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://control.msg91.com/api/sendotp.php?email=deepikabarve26@gmail.com&template=&otp=$otp&otp_length=6&otp_expiry=&sender=WORKPORTAL&message=$msg&mobile=$phone&authkey=281145A66wkWU75d09fab3",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            return $response;
        }
    }
}
function send_msg($msisdn,$msg)
{

    $sid = "VDNSIN";
    $msg = urlencode($msg);
    $type = "txt";
    $url12 = "http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=Chetan%20Prajapat&password=Chetan@0640&msisdn=$msisdn&sid=VDNSIN&msg=$msg&fl=0&gwid=2";
//step1
    $cSession = curl_init();
//step2
    curl_setopt($cSession,CURLOPT_URL,$url12);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession,CURLOPT_HEADER, false);
//step3
    $result=curl_exec($cSession);
//step4
    curl_close($cSession);
//step5
    return $result;

}
