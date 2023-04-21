<?php
/*
pv  => @gholipour3
channel => @botpanelmarzban
*/
include('config.php');
include('botapi.php');
include('apipanel.php');
include('jdf.php');
define('API_KEY', $token);
#-----------------------------#
$update = json_decode(file_get_contents("php://input"), true);
if (isset($update["message"])) {
    $from_id = $update["message"]["from"]["id"];
    $chat_id = $update["message"]["chat"]["id"];
  $Channel_status = $update["message"]["chat"]["type"];
  $text = $update["message"]["text"];
  $first_name = $update["message"]["from"]["first_name"];
} elseif (isset($update["callback_query"])) {
  $chat_id = $update["callback_query"]["message"]["chat"]["id"];
  $data = $update["callback_query"]["data"];
  $query_id = $update["callback_query"]["id"];
  $message_id = $update["callback_query"]["message"]["message_id"];
  $in_text = $update["callback_query"]["message"]["text"];
  $from_id = $update["callback_query"]["from"]["id"];
}
#-----------------------#
 $telegram_ip_ranges = [
   ['lower' => '149.154.160.0', 'upper' => '149.154.175.255'],
   ['lower' => '91.108.4.0',    'upper' => '91.108.7.255']
 ];
 $ip_dec = (float) sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
 $ok = false;
 foreach ($telegram_ip_ranges as $telegram_ip_range) if (!$ok) {
   $lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower']));
   $upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
   if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) $ok = true;
 }
 if (!$ok) die("false");
#-----------------------#
$keyboard = json_encode([
  'keyboard' => [
    [['text' => "📊  اطلاعات سرویس"], ['text' => "🔑 اکانت تست"]]
  ],
  'resize_keyboard' => true
]);
$keyboardadmin = json_encode([
    'keyboard' => [
        [['text' => "📯 تنظیمات کانال"],['text' => "📊 آمار ربات"]],
        [['text' => "👨‍💻 اضافه کردن ادمین"],['text' => "❌ حذف ادمین"]],
        [['text' => "📜 مشاهده لیست  ادمین ها"],['text' => "🖥 تنظیمات پنل مرزبان"]]
    ],
    'resize_keyboard' => true
]);
$keyboardmarzban =  json_encode([
    'keyboard' => [
        [['text' => "➕محدودیت ساخت اکانت تست برای کاربر"]],
        [['text' =>"➕محدودیت ساخت اکانت تست برای همه"]],
        [['text' => '🔌 وضعیت پنل '],['text' => "🖥 اضافه کردن پنل  مرزبان "]],
        [['text' => "🏠 بازگشت به منوی مدیریت"]]
    ],
    'resize_keyboard' => true
]);
$channelkeyboard = json_encode([
    'keyboard' => [
        [['text' => "📣 تنظیم کانال جوین اجباری"]],
        [['text' => "🔑 روشن / خاموش کردن قفل کانال"]],
        [['text' => "🏠 بازگشت به منوی مدیریت"]]
    ],
    'resize_keyboard' => true
]);
$backuser = json_encode([
  'keyboard' => [
    [['text' => "🏠 بازگشت به منوی اصلی"]]
  ],
  'resize_keyboard' => true
]);
$backadmin = json_encode([
    'keyboard' => [
        [['text' => "🏠 بازگشت به منوی مدیریت"]]
    ],
    'resize_keyboard' => true
]);
$namepanel = [];
$marzbnget = mysqli_query($connect, "SELECT * FROM marzban_panel");
while($row = mysqli_fetch_assoc($marzbnget)) {
    $namepanel[] = [$row['name_panel']];
}
$list_marzban_panel = [
    'keyboard' => [],
    'resize_keyboard' => true,
];
$list_marzban_panel['keyboard'][] = [
    ['text' => "🏠 بازگشت به منوی مدیریت"],
];
foreach($namepanel as $button) {
    $list_marzban_panel['keyboard'][] = [
        ['text' => $button[0]]
    ];
}
$json_list_marzban_panel = json_encode($list_marzban_panel);
$list_marzban_panel_users = [
    'keyboard' => [],
    'resize_keyboard' => true,
];
$list_marzban_panel_users['keyboard'][] = [
    ['text' => "🏠 بازگشت به منوی اصلی"],
];
foreach($namepanel as $button) {
    $list_marzban_panel_users['keyboard'][] = [
        ['text' => $button[0]]
    ];
}
$list_marzban_panel_user = json_encode($list_marzban_panel_users);
#-----------------------#
$user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM user WHERE id = '$from_id' LIMIT 1"));
$setting = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM setting"));
$Channel_locka_get = mysqli_fetch_assoc(mysqli_query($connect, "SELECT Channel_lock FROM channels"));
$Channel_locka = $Channel_locka_get['Channel_lock'];
$id_admin = mysqli_query($connect, "SELECT * FROM admin");
while($row = mysqli_fetch_assoc($id_admin)) {
    $admin_ids[] = $row['id_admin'];
}
$id_user = mysqli_query($connect, "SELECT * FROM user");
while($row = mysqli_fetch_assoc($id_user)) {
    $users_ids[] = $row['id'];
}
$Processing_value =  $user['Processing_value'];
#-----------------------#
$channels = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM channels  LIMIT 1"));
if (isset($channels['link'])) {
    $response = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@{$channels['link']}&user_id=" . $chat_id));
    $tch = $response->result->status;
}

#-----------------------#
if (!in_array($tch, ['member', 'creator', 'administrator']) && $Channel_locka == "on" && !in_array($from_id,$admin_ids)) {
    $text_channel = "   
    ⚠️کاربر گرامی ؛ شما عضو چنل ما نیستید
    ❗️@".$channels['link']."
    عضو کانال بالا شوید و مجدد 
    /start
    کنید❤️
    ";
    sendmessage($from_id,$text_channel,null);
    return;
}
    if ($text == "/start") {
        $text = "
        سلام $first_name 
        خوش آمدی
        ";
        sendmessage($from_id, $text, $keyboard);
        $connect->query("INSERT INTO user (id , step,limit_usertest,Processing_value) VALUES ('$from_id', 'none','$limit_usertest','none')");
    }
if ($text == "🏠 بازگشت به منوی اصلی") {
    $textback = "به صفحه اصلی بازگشتید!";
    sendmessage($from_id, $textback, $keyboard);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    return;
}
    if ($text == "📊  اطلاعات سرویس") {
        $textinfo = "
        نام کاربری خود را ارسال نمایید
            
    ⚠️ نام کاربری باید بدون کاراکترهای اضافه مانند @ ، فاصله ، خط تیره باشد. 
    ⚠️ نام کاربری باید انگلیسی باشد
    
        ";
        sendmessage($from_id, $textinfo, $backuser);
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'getusernameinfo';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();
    }
if ($user['step'] == "getusernameinfo"){
    if (!preg_match('~^[a-z][a-z\d_]{3,32}$~i', $text)){
        $textusernameinva = " 
                ❌نام کاربری نامعتبر است
            
            🔄 مجددا نام کاربری خود  را ارسال کنید
                ";
        sendmessage($from_id, $textusernameinva, $backuser);
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'getusernameinfo';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();
        return;
    }

    $stmt = $connect->prepare("UPDATE user SET Processing_value = ? WHERE id = ?");
    $stmt->bind_param("ss", $text, $from_id);
    $stmt->execute();
    sendmessage($from_id, "🌏 موقعیت سرویس خود را انتخاب نمایید.", $list_marzban_panel_user);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'getdata';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}

    if ($user['step'] == "getdata") {
        $marzban_list_get = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM marzban_panel WHERE name_panel = '$text'"));
        $Check_token = token_panel($marzban_list_get['url_panel'],$marzban_list_get['username_panel'],$marzban_list_get['password_panel']);
            $data_useer = getuser($Processing_value,$Check_token['access_token'],$marzban_list_get['url_panel']);
            if ($data_useer['detail'] == "User not found") {
                sendmessage($from_id, "نام کاربری وجود ندارد", $keyboard);
                $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
                $step = 'home';
                $stmt->bind_param("ss", $step, $from_id);
                $stmt->execute();                    return;
                }
                #-------------status----------------#
                $status = $data_useer['status'];
                $status_var = [
                    'active' => '✅فعال',
                    'limited' => '🔚پایان حجم',
                    'disabled' => '❌غیرفعال',
                    'expired' => 'نامشخص'
                ][$status];
                #--------------expire---------------#
                $expirationDate = $data_useer['expire'] ? jdate('Y/m/d', $data_useer['expire']) : "نامحدود";
                #-------------data_limit----------------#
                $LastTraffic = $data_useer['data_limit'] ? formatBytes($data_useer['data_limit']) : "نامحدود";
                #---------------RemainingVolume--------------#
                $output =  $data_useer['data_limit'] - $data_useer['used_traffic'];
                $RemainingVolume = $data_useer['data_limit'] ? formatBytes($output) : "نامحدود";
                #---------------used_traffic--------------#
                $usedTrafficGb = $data_useer['used_traffic'] ? formatBytes($data_useer['used_traffic']) : "مصرف نشده";
                #--------------day---------------#
                $timeDiff = $data_useer['expire'] - time();
                $day = $data_useer['expire'] ? floor($timeDiff / 86400) . " روز" : "نامحدود";
                #-----------------------------#


                $keyboardinfo = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => $data_useer['username'],'callback_data'=>"username"],
                            ['text' => 'نام کاربری :', 'callback_data' => 'username'],
                        ], [
                            ['text' => $status_var, 'callback_data' => 'status_var'],
                            ['text' => 'وضعیت:', 'callback_data' => 'status_var'],
                        ], [
                            ['text' => $expirationDate, 'callback_data' => 'expirationDate'],
                            ['text' => 'زمان پایان:', 'callback_data' => 'expirationDate'],
                        ], [
                        ], [
                            ['text' => $day, 'callback_data' => 'روز'],
                            ['text' => 'زمان باقی مانده تا پایان سرویس:', 'callback_data' => 'day'],
                        ], [
                            ['text' => $LastTraffic, 'callback_data' => 'LastTraffic'],
                            ['text' => 'حجم کل سرویس :', 'callback_data' => 'LastTraffic'],
                        ], [
                            ['text' => $usedTrafficGb, 'callback_data' => 'expirationDate'],
                            ['text' => 'حجم مصرف شده سرویس :', 'callback_data' => 'expirationDate'],
                        ], [
                            ['text' => $RemainingVolume, 'callback_data' => 'RemainingVolume'],
                            ['text' => 'حجم باقی مانده  سرویس :', 'callback_data' => 'RemainingVolume'],
                        ]
                    ]
                ]);
                sendmessage($from_id, "📊  اطلاعات سرویس :", $keyboardinfo);
                sendmessage($from_id, " یک گزینه را انتخاب کنید", $keyboard);
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'home';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();
    }
    if ($text == "🔑 اکانت تست") {
        if ($user['limit_usertest'] == 0) {
                sendmessage($from_id, "⚠️ محدودیت ساخت اشتراک تست شما به پایان رسید.", $keyboard);
                return;
        }
            $textusertest = "
          
            👤برای ساخت اشتراک تست یک نام کاربری انگلیسی ارسال نمایید.
    
    ⚠️ نام کاربری باید دارای شرایط زیر باشد
    
    1- فقط انگلیسی باشد و حروف فارسی نباشد
    2- کاراکترهای اضافی مانند @،#،% و... را نداشته باشد.
    3 - نام کاربری باید بدون فاصله باشد.
    
    🛑 در صورت رعایت نکردن موارد بالا با خطا مواجه خواهید شد
          ";
            sendmessage($from_id, $textusertest, $backuser);
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'selectloc';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();    }
    if ($user['step'] == "selectloc"){
        if (!preg_match('/^[a-zA-Z0-9_]{3,32}$/', $text)) {
            sendmessage($from_id, "⛔️ نام کاربری معتبر نیست", $backuser);
            $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
            $step = 'selectloc';
            $stmt->bind_param("ss", $step, $from_id);
            $stmt->execute();
            return;
        }
        $stmt = $connect->prepare("UPDATE user SET Processing_value = ? WHERE id = ?");
        $stmt->bind_param("ss", $text, $from_id);
        $stmt->execute();
        sendmessage($from_id, "🌏 موقعیت سرویس تست را انتخاب نمایید.", $list_marzban_panel_user);
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'crateusertest';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();
    }
#-----------------------------------#
    if ($user['step'] == "crateusertest") {
        $marzban_list_get = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM marzban_panel WHERE name_panel = '$text'"));
        $Check_token = token_panel($marzban_list_get['url_panel'],$marzban_list_get['username_panel'],$marzban_list_get['password_panel']);
            $Allowedusername = getuser($Processing_value,$Check_token['access_token'],$marzban_list_get['url_panel']);;
            if (empty($Allowedusername['username'])) {
                $date = strtotime("+" . $time . "hours");
                $timestamp = strtotime(date("Y-m-d H:i:s", $date));
                $expire = $timestamp;
                $data_limit = $val * 1000000;
                $config_test = adduser($Processing_value, $expire, $data_limit,$Check_token['access_token'],$marzban_list_get['url_panel']);
                $data_test = json_decode($config_test, true);
                $output_config_link = $data_test['subscription_url'];
                $textcreatuser = "
                    
    🔑 اشتراک شما با موفقیت ساخته شد.
    ⏳ زمان اشتراک تست $time ساعت
    🌐 حجم سرویس تست $val مگابایت
    
    لینک اشتراک شما   :
    ```
    $output_config_link
    ```
                    ";
                sendmessage($from_id, $textcreatuser, $keyboard);
                $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
                $step = 'home';
                $stmt->bind_param("ss", $step, $from_id);
                $stmt->execute();
                $limit_usertest = $user['limit_usertest'] - 1;
                $stmt = $connect->prepare("UPDATE user SET limit_usertest = ? WHERE id = ?");
                $stmt->bind_param("ss", $limit_usertest, $from_id);
                $stmt->execute();
            }
            $count_usertest = $setting['count_usertest']+1;
        $stmt = $connect->prepare("UPDATE setting SET count_usertest = ?");
        $stmt->bind_param("s", $count_usertest);
        $stmt->execute();
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'home';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();
    }

//------------------------------------------------------------------------------



#----------------admin------------------#
if(!in_array($from_id,$admin_ids)) return;
if($text == "panel"){
    sendmessage($from_id,"به پنل ادمین خوش آمدید",$keyboardadmin);
}
if ($text == "🏠 بازگشت به منوی مدیریت"){
    sendmessage($from_id,"به پنل ادمین بازگشتید! ",$keyboardadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    return;
}
if ($text =="🔑 روشن / خاموش کردن قفل کانال"){
if($Channel_locka=="off"){
    sendmessage($from_id,"عضویت اجباری روشن گردید",$channelkeyboard);
    $stmt = $connect->prepare("UPDATE channels SET Channel_lock = ?");
    $Channel_lock = 'on';
    $stmt->bind_param("s", $Channel_lock);
}
else{
    sendmessage($from_id,"عضویت اجباری خاموش گردید",$channelkeyboard);
    $stmt = $connect->prepare("UPDATE channels SET Channel_lock = ?");
    $Channel_lock = 'off';
    $stmt->bind_param("s", $Channel_lock);
    $stmt->execute();
}
}
if($text =="📣 تنظیم کانال جوین اجباری") {
    $text_channel = "
    برای تنظیم کانال عضویت اجباری لطفا آیدی کانال خود را بدون @ وارد نمایید.
    
    کانال فعلی شما: @".$channels['link'];
    sendmessage($from_id, $text_channel, $backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'addchannel';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if($user['step'] == "addchannel"){
    $text_set_channel="
    🔰 کانال با موفقیت تنظیم گردید.
     برای  روشن کردن عضویت اجباری از منوی ادمین دکمه 📣 تنظیم کانال جوین اجباری  را بزنید
    ";
    sendmessage($from_id, $text_set_channel, $keyboardadmin);
    if (isset($channels['link'])) {
        $stmt = $connect->prepare("UPDATE channels SET link = ?");
        $stmt->bind_param("s", $text);
        $stmt->execute();
    }
    else{
        $stmt = $connect->prepare("INSERT INTO channels (link,Channel_lock) VALUES (?)");
        $Channel_lock = 'off';
        $stmt->bind_param("ss", $text, $Channel_lock);
        $stmt->execute();
    }
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();

}
if ($text == "👨‍💻 اضافه کردن ادمین"){
    sendmessage($from_id, "🌟آیدی عددی ادمین جدید را ارسال نمایید.", $backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'addadmin';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if($user['step'] == "addadmin"){
    sendmessage($from_id, "🥳ادمین با موفقیت اضافه گردید", $keyboardadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("INSERT INTO admin (id_admin) VALUES (?)");
    $stmt->bind_param("s", $text);
    $stmt->execute();

}
if($text == "❌ حذف ادمین"){
    sendmessage($from_id, "🛑 آیدی عددی ادمین را ارسال کنید.", $backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'deleteadmin';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if ($user['step'] == "deleteadmin"){
    if (!is_numeric($text) || !in_array($text,$admin_ids))return;
    sendmessage($from_id, "✅ ادمین با موفقیت حذف گردید.", $keyboardadmin);
    $stmt = $connect->prepare("DELETE FROM admin WHERE id_admin = ?");
    $stmt->bind_param("s", $text);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();

}
if ($text == "➕محدودیت ساخت اکانت تست برای کاربر"){
    $text_add_user_admin = "
    ⚜️ آیدی عددی کاربر را ارسال کنید 
توضیحات : در این بخش میتوانید محدودیت ساخت اکانت تست را برای کاربر تغییر دهید. بطور پیشفرض محدودیت ساخت عدد 1 است
    ";
    sendmessage($from_id, $text_add_user_admin, $backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'add_limit_usertest_foruser';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if ($user['step'] == "add_limit_usertest_foruser") {
    if (!in_array($text,$users_ids)){
        sendmessage($from_id,"کاربری با این شناسه یافت نشد",$backadmin);
        return;
    }
    sendmessage($from_id, "آیدی عددی دریافت شد لطفا تعداد ساخت اکانت تست را ارسال کنید", $backadmin);
    $stmt = $connect->prepare("UPDATE user SET Processing_value = ? WHERE id = ?");
    $stmt->bind_param("ss", $text, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'get_number_limit';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if ($user['step'] == "get_number_limit") {
    sendmessage($from_id, "محدودیت برای کاربر تنظیم گردید.", $keyboardmarzban);
    $id_user_set = $text;
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE user SET limit_usertest = ? WHERE id = ?");
    $stmt->bind_param("ss", $text, $Processing_value);
    $stmt->execute();
}
if ($text == "➕محدودیت ساخت اکانت تست برای همه"){
    sendmessage($from_id, "تعداد ساخت اکانت تست را  وارد نمایید.", $backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'limit_usertest_allusers';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if ($user['step'] == "limit_usertest_allusers"){
    sendmessage($from_id, "محدودیت ساخت اکانت برای تمام کاربران تنظیم شد", $keyboardmarzban);
    $stmt = $connect->prepare("UPDATE user SET limit_usertest = ?");
    $limit_usertest = $text;
    $stmt->bind_param("ss", $limit_usertest, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();

}
if($text == "📯 تنظیمات کانال") {
    sendmessage($from_id, "یکی از گزینه های زیر را انتخاب کنید", $channelkeyboard);
}
if ($text == "📊 آمار ربات"){
    $stmt = $connect->prepare("SELECT COUNT(id) FROM user");
    $stmt->execute();
    $result = $stmt->get_result();
    $statistics = $result->fetch_array(MYSQLI_NUM);
    $count_usertest_var =$setting['count_usertest'];
    $text_statistics = "
    👤 تعداد کاربران : $statistics[0]
🖥 تعداد اکانت تست گرفته شده:  $count_usertest_var        ";
    sendmessage($from_id, "$text_statistics", $keyboardadmin);
}
if ($text == "🖥 تنظیمات پنل مرزبان"){
    sendmessage($from_id, "یکی از گزینه های زیر را انتخاب کنید", $keyboardmarzban);
}
if($text == "🔌 وضعیت پنل"){
    sendmessage($from_id, "پنل خود را انتخاب کنید", $json_list_marzban_panel);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'get_panel';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();


}
if ($user['step'] == "get_panel"){
    $stmt = $connect->prepare("SELECT * FROM marzban_panel WHERE name_panel = ?");
    $stmt->bind_param("s", $text);
    $stmt->execute();
    $marzban_list_get = $stmt->get_result()->fetch_assoc();
    $Check_token = token_panel($marzban_list_get['url_panel'],$marzban_list_get['username_panel'],$marzban_list_get['password_panel']);
    if (isset($Check_token['access_token'])){
        $Condition_marzban = "✅ پنل متصل است";
    }
    elseif ($Check_token['detail'] == "Incorrect username or password"){
        $Condition_marzban = "❌ نام کاربری یا رمز عبور پنل اشتباه است";
    }
    else {
        $Condition_marzban= "امکان اتصال به پنل مرزبان وجود ندارد.😔";
    }
    $System_Stats = Get_System_Stats($marzban_list_get['url_panel'],$Check_token['access_token']);
    $active_users = $System_Stats['users_active'];
    $text_marzban= "
    اطلاعات پنل شما👇:
         
🖥 وضعیت اتصال پنل مرزبان  :$Condition_marzban
👤 تعداد کاربران فعال  :$active_users
    ";
    sendmessage($from_id, $text_marzban, $keyboardmarzban);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if ($text =="📜 مشاهده لیست  ادمین ها"){
    foreach ($admin_ids as $admin){
        $List_admin .= "$admin \n";
    }
    $list_admin_text= "
    آیدی عددی ادمین ها: 
    $List_admin";
    sendmessage($from_id, $list_admin_text, $keyboardadmin);
}

if ($text == "🖥 اضافه کردن پنل  مرزبان"){
    $text_add_panel = "
    برای اضافه کردن پنل مرزبان به ربات ابتدا یک نام برای پنل خود ارسال کنید
    
 ⚠️ توجه : نام پنل نامی است که  در هنگام انجام عملیات جستجو  پنل از طریق نام است.
    ";
    sendmessage($from_id , $text_add_panel,$backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'add_name_panel';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
}
if ($user['step'] == "add_name_panel"){
    $stmt = $connect->prepare("INSERT INTO marzban_panel (name_panel) VALUES (?)");
    $name_panel = htmlspecialchars($text);
    $stmt->bind_param("s", $name_panel);
    $stmt->execute();
    $text_add_url_panel= "
        🔗نام پنل ذخیره شد حالا  آدرس  پنل خود ارسال کنید
    
 ⚠️ توجه :
🔸 آدرس پنل باید  بدون dashboard ارسال شود.
🔹 در صورتی که  پورت پنل 443 است پورت را نباید وارد کنید.    
        ";
    sendmessage($from_id , $text_add_url_panel,$backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'add_link_panel';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE user SET  Processing_value = ? WHERE id = ?");
    $stmt->bind_param("ss", $text, $from_id);
    $stmt->execute();
}
if ($user['step'] == "add_link_panel"){
    if (filter_var($text, FILTER_VALIDATE_URL)) {
        sendmessage($from_id, "👤 آدرس پنل ذخیره شد حالا نام کاربری  را ارسال کنید.", $backadmin);
        $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
        $step = 'add_username_panel';
        $stmt->bind_param("ss", $step, $from_id);
        $stmt->execute();
        $stmt = $connect->prepare("UPDATE marzban_panel SET  url_panel = ? WHERE name_panel = ?");
        $stmt->bind_param("ss", $text, $Processing_value);
        $stmt->execute();
    }
    else{
        sendmessage($from_id, "🔗 آدرس دامنه نامعتبر است", $backadmin);

    }
}
if ($user['step'] == "add_username_panel"){
    sendmessage($from_id, "🔑 نام کاربری ذخیره شد  در پایان رمز عبور پنل مرزبان خود را وارد نمایید.", $backadmin);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'add_password_panel';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE marzban_panel SET  username_panel = ? WHERE name_panel = ?");
    $stmt->bind_param("ss", $text, $Processing_value);
    $stmt->execute();
}
if ($user['step'] == "add_password_panel"){
    sendmessage($from_id, "تبریک پنل شما با موفقیت اضافه گردید.", $backadmin);
    sendmessage($from_id, "🥳", $keyboardmarzban);
    $stmt = $connect->prepare("UPDATE user SET step = ? WHERE id = ?");
    $step = 'home';
    $stmt->bind_param("ss", $step, $from_id);
    $stmt->execute();
    $stmt = $connect->prepare("UPDATE marzban_panel SET  password_panel = ? WHERE name_panel = ?");
    $stmt->bind_param("ss", $text, $Processing_value);
    $stmt->execute();
}
