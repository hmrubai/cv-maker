<?php

namespace App\Providers;


use App\Models\PrimaryInfo;
use App\Models\VisitorTrack;
use Illuminate\Support\ServiceProvider;
use Image,Auth;
use App\User;

class MyHelperProvider extends ServiceProvider
{
    // ---------------------- Date Time Start ---------------

    // Numbers
    public static $bn_numbers = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
    public static $en_numbers = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    // Months
    public static $en_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    public static $en_short_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    public static $bn_months = ['জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'অগাস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];

    // Days
    public static $en_days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    public static $en_short_days = ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
    public static $bn_short_days = ['শনি', 'রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহঃ', 'শুক্র'];
    public static $bn_days = ['শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার'];

    // Times
    public static $en_times = array('am', 'pm');
    public static $en_times_uppercase = array('AM', 'PM');
    public static $bn_times = array('এএম', 'পিএম');
    public static $bn_times1 = array('পূর্বাহ্ন', 'অপরাহ্ন');

    // Method - English to Bengali Number
    public static function bn_number($number)
    {
        return str_replace(self::$en_numbers, self::$bn_numbers, $number);
    }

    // Method - Bengali to English Number
    public static function en_number($number)
    {
        return str_replace(self::$bn_numbers, self::$en_numbers, $number);
    }

    // Method - English to Bengali Date
    public static function bn_date($date)
    {
        // Convert Numbers
        $date = str_replace(self::$en_numbers, self::$bn_numbers, $date);

        // Convert Months
        $date = str_replace(self::$en_months, self::$bn_months, $date);
        $date = str_replace(self::$en_short_months, self::$bn_months, $date);

        // Convert Days
        $date = str_replace(self::$en_days, self::$bn_days, $date);
        $date = str_replace(self::$en_short_days, self::$bn_short_days, $date);
        $date = str_replace(self::$en_days, self::$bn_days, $date);
        return $date;
    }

    // Method - English to Bengali Time
    public static function bn_time($time)
    {
        // Convert Numbers
        $time = str_replace(self::$en_numbers, self::$bn_numbers, $time);

        // Convert Time
        $time = str_replace(self::$en_times, self::$bn_times, $time);
        $time = str_replace(self::$en_times_uppercase, self::$bn_times, $time);
        return $time;
    }

    // Method - English to Bengali Date Time
    public static function bn_date_time($date_time)
    {
        // Convert Numbers
        $date_time = str_replace(self::$en_numbers, self::$bn_numbers, $date_time);

        // Convert Months
        $date_time = str_replace(self::$en_months, self::$bn_months, $date_time);
        $date_time = str_replace(self::$en_short_months, self::$bn_months, $date_time);

        // Convert Days
        $date_time = str_replace(self::$en_days, self::$bn_days, $date_time);
        $date_time = str_replace(self::$en_short_days, self::$bn_short_days, $date_time);
        $date_time = str_replace(self::$en_days, self::$bn_days, $date_time);

        // Convert Time
        $date_time = str_replace(self::$en_times, self::$bn_times, $date_time);
        $date_time = str_replace(self::$en_times_uppercase, self::$bn_times, $date_time);
        return $date_time;
    }



    // ---------------------- Date Time Dnd -----------------

     public static function userRole($id=null){

        $user = Role::join('role_user','role_user.role_id','roles.id')
            ->select('roles.slug as role','roles.id as role_id');

        if ($id){
            $user=$user->where('role_user.user_id',$id);
        }else{
            $user=$user->where('role_user.user_id',Auth::user()->id);
        }
        return $user->first();
    }
    public static function bkash()
    {
        $config = [
            "tokenURL" => "https://checkout.pay.bka.sh/v1.2.0-beta/checkout/token/grant",
            "createURL" => "https://checkout.pay.bka.sh/v1.2.0-beta/checkout/payment/create",
            "executeURL" => "https://checkout.pay.bka.sh/v1.2.0-beta/checkout/payment/execute/",
            "queryURL" => "https://checkout.pay.bka.sh/v1.2.0-beta/checkout/payment/query/",
            "searchURL" => "https://checkout.pay.bka.sh/v1.2.0-beta/checkout/payment/search/",
            "script" => "https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js",
            "app_key" => "7g9nt7rrbpqd4kjq3tebfhnta4",
            "app_secret" => "14ejrl7qbf2ohhv7hgkoo7adgvppf5cpl0q2vn9jfm6lo57eaj5d",
            "username" => "ACHIEVEMENTCARE",
            "password" => "aC@1iE9vmEnT012",
        ];

        return (object)$config;
    }


     public static function slugify($text){
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        //$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /* Convert in word taka */
    public static function taka($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . ' Taka Only ' : '');
    }

     public static function multiPhotoUpload($photoData,$folderName,$width=null,$height=null){

         $photoOrgName=self::slugify($photoData->getClientOriginalName());
         $photoType=$photoData->getClientOriginalExtension();

         //$fileType = $photoData->getClientOriginalName();
         $fileName =substr($photoOrgName,0,-4).date('d-m-Y-i-s').'.'.$photoType;
         $bigPhotoPath = 'images/products/big'.date('/Y/m/d/');
         $mediumPhotoPath = 'images/products/medium'.date('/Y/m/d/');
         $smallPhotoPath = 'images/products/small'.date('/Y/m/d/');
         //return $path2;

         if (!is_dir(public_path($bigPhotoPath))) {
             mkdir(public_path($bigPhotoPath), 0777, true);
         }
         if (!is_dir(public_path($mediumPhotoPath))) {
             mkdir(public_path($mediumPhotoPath), 0777, true);
         }

         if (!is_dir(public_path($smallPhotoPath))) {
             mkdir(public_path($smallPhotoPath), 0777, true);
         }


         $photoData->move(public_path($bigPhotoPath),$fileName);
//         $photoData->move(public_path($mediumPhotoPath),$fileName);

         $uploadPaths=[];

         $bigImg= \Image::make(public_path($bigPhotoPath.$fileName));
         $bigImg->encode('webp',75)->resize(750,null, function ($constraint) {
             $constraint->aspectRatio();
         });
         $bigImg->save(public_path("$bigPhotoPath".$fileName));

         $uploadPaths['big']="$bigPhotoPath".$fileName; // ---------------- big

         $mediumImg= \Image::make(public_path($bigPhotoPath.$fileName));
         $mediumImg->encode('webp',75)->resize(350,null, function ($constraint) {
             $constraint->aspectRatio();
         });
         $mediumImg->save(public_path("$mediumPhotoPath".$fileName));

         $uploadPaths['medium']="$mediumPhotoPath".$fileName; // -------------- medium

         $smallImg= \Image::make(public_path($bigPhotoPath.$fileName));
         $smallImg->encode('webp',75)->resize(47,null, function ($constraint) {
             $constraint->aspectRatio();
         });

         $smallImg->save(public_path("$smallPhotoPath".$fileName));

          $uploadPaths['small']="$smallPhotoPath".$fileName;

        return $uploadPaths;

     }


    public static function imageCompressor($photoData,$folderName,$width=null,$height=null) {
        $file = $photoData->file('group_icon');
        $name = time().rand('1000','9999').'.'.$file->getClientOriginalExtension();
        $file->move($folderName,$name);

        $img= \Image::make(public_path("$folderName/".$name));
        $img->encode('webp',75)->fit($width,$height);
        $img->save(public_path("$folderName/".$name));

    }



    public static function photoUpload($photoData,$folderName,$width=null,$height=null){

         $photoOrgName=self::slugify($photoData->getClientOriginalName());
         $photoType=$photoData->getClientOriginalExtension();

         //$fileType = $photoData->getClientOriginalName();
         $fileName =substr($photoOrgName,0,-4).'-'.date('d-m-Y-i-s').'.'.$photoType;
         $path2 = $folderName.date('/Y/m/d/');
         //return $path2;
         if (!is_dir(public_path($path2))) {
             mkdir(public_path($path2), 0777, true);
         }


         $photoData->move(public_path($path2),$fileName);

         if ($width!=null && $height!=null){ // width & height mention-------------------
             $img = \Image::make(public_path($path2.$fileName));
             $img->encode('webp',75)->resize($width, $height);
             $img->save(public_path($path2.$fileName));
             return $photoUploadedPath=$path2 . $fileName;

         }elseif ($width!=null){ // only width mention-------------------

             $img = \Image::make(public_path($path2.$fileName));
             $img->encode('webp',75)->resize($width,null, function ($constraint) {
                $constraint->aspectRatio();
            });
             $img->save(public_path($path2 . $fileName));

             return $photoUploadedPath=$path2 . $fileName;

         }else{
             $img = \Image::make(public_path($path2.$fileName));
             $img->save(public_path($path2 . $fileName));
             return $photoUploadedPath=$path2 . $fileName;
         }


     }

     public static function fileUpload($filedata,$folderName){

        $fileType = $filedata->getClientOriginalExtension();
        $fileName = rand(1, 1000) . date('dmyhis') . "." . $fileType;
        $path2 = $folderName. date('/Y/m/d/');
        //return $path2;
        if (!file_exists(public_path($path2))) {
            mkdir(public_path($path2), 0777, true);
        }
        $img =$filedata->move(public_path($path2),$fileName);
        //$img->resize(400, 330);
        //$img->save($path2. $fileName);
        return $photoUploadedPath=$path2 . $fileName;

    }




     public static function postPhotoUpload($photoData){

        $photoOrgName=self::slugify($photoData->getClientOriginalName());
        $photoType=$photoData->getClientOriginalExtension();

        //$fileType = $photoData->getClientOriginalName();
        $fileName =substr($photoOrgName,0,-4).date('d-m-YH-i-s').'.'.$photoType;
        $bigPhotoPath = 'images/post_photo/big/'. date('Y/m/d/');
        $smallPhotoPath = 'images/post_photo/small/'. date('Y/m/d/');
        //return $path2;
        if (!is_dir($bigPhotoPath)) { mkdir("$bigPhotoPath", 0777, true);}
        if (!is_dir($smallPhotoPath)) { mkdir("$smallPhotoPath", 0777, true);}
        $img = \Image::make($photoData);
        $img->resize(400, 358);
        $img->save($bigPhotoPath . $fileName);

        $img->resize(265, 223);
        $img->save($smallPhotoPath . $fileName);
        return $photoUploadedPath=date('Y/m/d/') . $fileName;


    }


    public static function countVisitor($request)
    {
        $visitUrl=$small = substr($request->url(), 0, 50);;
        $clientIp=$request->ip();
        $today=date('y-m-d');
        $visit=VisitorTrack::where(['visit_url'=>$visitUrl,'ip_address'=>$clientIp,'visit_date'=>$today])->first();

    

        if (!empty($visit))
        {
            $totalVisit=$visit->total_visit;
            $visit->update(['total_visit'=>$totalVisit+1]);
        }else{
            VisitorTrack::create(['visit_url'=>$visitUrl,'ip_address'=>$clientIp,'visit_date'=>$today,'total_visit'=>1]);
        }
    }




    public static function hrmConfig(){
        $data = [
            'in_time'=>'09:00 AM',
            'out_time'=>'06:00 PM',
            'off_days'=>'Friday,Saturday',
        ];
        return $config = (object) $data;
    }

     public static function info(){
        return PrimaryInfo::first();
    }
}
