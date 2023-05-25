<?php
global $connect;
require_once 'config.php';
//-----------------------------------------------------------------
try {
    $result = $connect->query("SHOW TABLES LIKE 'user'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE user (
        id varchar(500)  PRIMARY KEY,
        limit_usertest int(100) NOT NULL,
        roll_Status bool NOT NULL,
        Processing_value  varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        Processing_value_one varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        Processing_value_tow varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        step varchar(1000) NOT NULL,
        description_blocking TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
        number varchar(2000) NOT null ,
        Balance int(255) NOT null ,
        User_Status varchar(500) NOT NULL,
        pagenumber int(10) NOT NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table User".mysqli_error($connect);
        } else {
            echo "Table 'user' created successfully!"."</br>";
        }
    }
    else {
        $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'Processing_value'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE user ADD Processing_value VARCHAR(1000)");
            $connect->query("UPDATE user SET Processing_value = 'none'");
            echo "The Processing_Value field was added ✅";
        }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'Processing_value_tow'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD Processing_value_tow VARCHAR(1000)");
                $connect->query("UPDATE user SET Processing_value_tow = 'none'");
                echo "The Processing_value_tow field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'Processing_value_one'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD Processing_value_one VARCHAR(1000)");
                $connect->query("UPDATE user SET Processing_value_one = 'none'");
                echo "The Processing_value_one field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'Balance'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD Balance int(255)");
                $connect->query("UPDATE user SET Balance = '0'");
                echo "The Balance field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'number'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD number VARCHAR(1000)");
                $connect->query("UPDATE user SET number = 'none'");
                echo "The number field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'roll_Status'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD roll_Status bool");
                $connect->query("UPDATE user SET roll_Status = false");
                echo "The roll_Status field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'description_blocking'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD description_blocking VARCHAR(5000)");
                echo "The description_blocking field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'User_Status'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD User_Status VARCHAR(500)");
                echo "The User_Status field was added ✅";
            }
            $Check_filde = $connect->query("SHOW COLUMNS FROM user LIKE 'pagenumber'");
            if (mysqli_num_rows($Check_filde) != 1) {
                $connect->query("ALTER TABLE user ADD pagenumber int(10)");
                echo "The page_number field was added ✅";
            }
        }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {
    $result = $connect->query("SHOW TABLES LIKE 'help'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE help (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name_os varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        Media_os varchar(5000) NOT NULL,
        type_Media_os varchar(500) NOT NULL,
        Description_os TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table help".mysqli_error($connect);
        } else {
            echo "Table 'help' created successfully!"."</br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {
    $result = $connect->query("SHOW TABLES LIKE 'setting'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE setting (
        Bot_Status varchar(200)  CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        help_Status varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        roll_Status varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        get_number varchar(200)  CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        iran_number varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        sublink varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        configManual varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NULL,
        Channel_Report varchar(600)  NULL,
        limit_usertest_all varchar(600)  NULL,
        time_usertest varchar(600)  NULL,
        val_usertest varchar(600)  NULL,
        count_usertest varchar(5000) NOT NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table setting".mysqli_error($connect);
        } else {
            echo "Table 'setting' created successfully!"."</br>";
        }
        $active_bot_text = "✅  ربات روشن است";
        $active_roll_text = "❌ تایید قوانین خاموش است";
        $active_phone_text = "❌ احرازهویت شماره تماس غیرفعال است";
        $active_phone_iran_text = "❌ بررسی شماره ایرانی غیرفعال است";
        $active_help = "❌ آموزش غیرفعال است";
        $sublink = "✅ لینک اشتراک فعال است.";
        $configManual = "❌ ارسال کانفیگ دستی خاموش است";
$connect->query("INSERT INTO setting (count_usertest,Bot_Status,roll_Status,get_number,limit_usertest_all,time_usertest,val_usertest,help_Status,iran_number,sublink,configManual) VALUES ('0','$active_bot_text','$active_roll_text','$active_phone_text','1','1','100','$active_help','$active_phone_iran_text','$sublink','$configManual')");
    } else {
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'configManual'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD configManual VARCHAR(200)");
            echo "The configManual field was added ✅";
        }
                $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'sublink'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD sublink VARCHAR(200)");
            echo "The sublink field was added ✅";
        }
                $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'iran_number'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD iran_number VARCHAR(200)");
            echo "The iran_number field was added ✅";
        }
         $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'get_number'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD get_number VARCHAR(200)");
            echo "The get_number field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'time_usertest'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD time_usertest VARCHAR(600)");
            echo "The time_usertest field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'val_usertest'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD val_usertest VARCHAR(600)");
            echo "The val_usertest field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'help_Status'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD help_Status VARCHAR(600)");
            echo "The help_Status field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'limit_usertest_all'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD limit_usertest_all VARCHAR(600)");
            echo "The limit_usertest_all field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'Channel_Report'");
        if (mysqli_num_rows($Check_filde) != 1) {
              $connect->query("ALTER TABLE setting ADD Channel_Report VARCHAR(200)");
            echo "The Channel_Report field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'Bot_Status'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD Bot_Status VARCHAR(200)");
            echo "The Bot_Status field was added ✅";
        }
        $Check_filde = $connect->query("SHOW COLUMNS FROM setting LIKE 'roll_Status'");
        if (mysqli_num_rows($Check_filde) != 1) {
            $connect->query("ALTER TABLE setting ADD roll_Status VARCHAR(200)");
            $connect->query("UPDATE setting SET roll_Status = '✅ روشن '");
            echo "The roll_Status field was added ✅";
        }
        $sublink = "✅ لینک اشتراک فعال است.";
        $active_phone_iran_text = "❌ بررسی شماره ایرانی غیرفعال است";
        $configManual = "❌ ارسال کانفیگ دستی خاموش است";
        $stmt = $connect->prepare("UPDATE setting SET configManual = ?");
        $stmt->bind_param("s", $configManual);
        $stmt->execute();
        $stmt = $connect->prepare("UPDATE setting SET iran_number = ?");
        $stmt->bind_param("s", $active_phone_iran_text);
        $stmt->execute();
        $stmt = $connect->prepare("UPDATE setting SET sublink = ?");
        $stmt->bind_param("s", $sublink);
        $stmt->execute();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

//-----------------------------------------------------------------
try {
    $result = $connect->query("SHOW TABLES LIKE 'admin'");
    $table_exists = ($result->num_rows > 0);
    if ($table_exists) {
        $id_admin = mysqli_query($connect, "SELECT * FROM admin");
        while ($row = mysqli_fetch_assoc($id_admin)) {
            $admin_ids[] = $row['id_admin'];
        }
        if (!in_array($adminnumber, $admin_ids)) {
            $connect->query("INSERT INTO admin (id_admin) VALUES ('$adminnumber')");
            echo "table admin update✅</br>";
        }
    } else {
        $result =  $connect->query("CREATE TABLE admin (
        id_admin varchar(5000) NOT NULL)");
        $connect->query("INSERT INTO admin (id_admin) VALUES ('$adminnumber')");
        if (!$result) {
            echo "table admin".mysqli_error($connect);
        } else {
            echo "Table 'admin' created successfully!"."</br>";
        }     }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'channels'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result =  $connect->query("CREATE TABLE channels (
Channel_lock varchar(200) NOT NULL,
link varchar(200) NOT NULL )");
        if (!$result) {
            echo "table channels".mysqli_error($connect);
        } else {
            echo "Table 'channels' created successfully!"."</br>";
        }    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//--------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'marzban_panel'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE marzban_panel (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name_panel varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
        url_panel varchar(2000) NULL,
        username_panel varchar(200) NULL,
        password_panel varchar(200) NULL )
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table marzban_panel".mysqli_error($connect);
        } else {
            echo "Table 'marzban_panel' created successfully!"."</br>";
        }    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'product'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE product (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name_product varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
        price_product varchar(2000) NULL,
        Volume_constraint varchar(2000) NULL,
        Service_time varchar(200) NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table product".mysqli_error($connect);
        } else {
            echo "Table 'product' created successfully!"."</br>";
        }    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'invoice'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE invoice (
        id_invoice varchar(200) PRIMARY KEY,
        id_user varchar(200) NULL,
        username varchar(2000) NULL,
        Service_location varchar(2000) NULL,
        name_product varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
        price_product varchar(2000) NULL,
        Volume varchar(2000) NULL,
        Service_time varchar(200) NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table invoice".mysqli_error($connect);
        } else {
            echo "Table 'invoice' created successfully!"."</br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'Payment_report'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result =  $connect->query("CREATE TABLE Payment_report (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_user varchar(200),
        id_order varchar(2000),
        time varchar(200)  NULL,
        price varchar(2000) NULL,
        dec_not_confirmed varchar(2000) NULL,
        payment_Status varchar(2000) NULL)");
        if (!$result) {
            echo "table Payment_report".mysqli_error($connect);
        } else {
            echo "Table 'Payment_report' created successfully!"."</br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'Discount'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result =  $connect->query("CREATE TABLE Discount (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code varchar(2000) NULL,
        price varchar(200) NULL)");
        if (!$result) {
            echo "table Discount".mysqli_error($connect);
        } else {
            echo "Table 'Discount' created successfully!"."</br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {

    $result = $connect->query("SHOW TABLES LIKE 'Giftcodeconsumed'");
    $table_exists = ($result->num_rows > 0);

    if (!$table_exists) {
        $result =  $connect->query("CREATE TABLE  Giftcodeconsumed (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code varchar(2000) NULL,
        id_user varchar(200) NULL)");
        if (!$result) {
            echo "table Giftcodeconsumed".mysqli_error($connect);
        } else {
            echo "Table 'Giftcodeconsumed' created successfully!"."</br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
//-----------------------------------------------------------------
try {
    $result = $connect->query("SHOW TABLES LIKE 'textbot'");
    $table_exists = ($result->num_rows > 0);
    $text_info = "
    نام کاربری خود را ارسال نمایید
            
    ⚠️ نام کاربری باید بدون کاراکترهای اضافه مانند @، فاصله، خط تیره باشد. 
    ⚠️ نام کاربری باید انگلیسی باشد
      ";
    $support_dec = "
    پیام خود را ارسال کنید:
        ⚠️ برای دریافت پیام حتما باید فوروارد حساب کاربری تان باز باشد تا پاسخ ادمین را دریافت کنید.
    ";
    $text_roll = "
♨️ قوانین استفاده از خدمات ما

1- به اطلاعیه هایی که داخل کانال گذاشته می شود حتما توجه کنید.
2- در صورتی که اطلاعیه ای در مورد قطعی در کانال گذاشته نشده به اکانت پشتیبانی پیام دهید
3- سرویس ها را از طریق پیامک ارسال نکنید برای ارسال پیامک می توانید از طریق ایمیل ارسال کنید.
    ";
    $text_dec_fq = " 
 💡 سوالات متداول ⁉️

1️⃣ فیلترشکن شما آیپی ثابته؟ میتونم برای صرافی های ارز دیجیتال استفاده کنم؟

✅ به دلیل وضعیت نت و محدودیت های کشور سرویس ما مناسب ترید نیست و فقط لوکیشن‌ ثابته.

2️⃣ اگه قبل از منقضی شدن اکانت، تمدیدش کنم روزهای باقی مانده می سوزد؟

✅ خیر، روزهای باقیمونده اکانت موقع تمدید حساب میشن و اگه مثلا 5 روز قبل از منقضی شدن اکانت 1 ماهه خودتون اون رو تمدید کنید 5 روز باقیمونده + 30 روز تمدید میشه.

3️⃣ اگه به یک اکانت بیشتر از حد مجاز متصل شیم چه اتفاقی میافته؟

✅ در این صورت حجم سرویس شما زود تمام خواهد شد.

4️⃣ فیلترشکن شما از چه نوعیه؟

✅ فیلترشکن های ما v2ray است و پروتکل‌های مختلفی رو ساپورت میکنیم تا حتی تو دورانی که اینترنت اختلال داره بدون مشکل و افت سرعت بتونید از سرویستون استفاده کنید.

5️⃣ فیلترشکن از کدوم کشور است؟

✅ سرور فیلترشکن ما از کشور  آلمان است

6️⃣ چطور باید از این فیلترشکن استفاده کنم؟

✅ برای آموزش استفاده از برنامه، روی دکمه «📚 آموزش» بزنید.

7️⃣ فیلترشکن وصل نمیشه، چیکار کنم؟

✅ به همراه یک عکس از پیغام خطایی که میگیرید به پشتیبانی مراجعه کنید.

8️⃣ فیلترشکن شما تضمینی هست که همیشه مواقع متصل بشه؟

✅ به دلیل قابل پیش‌بینی نبودن وضعیت نت کشور، امکان دادن تضمین نیست فقط می‌تونیم تضمین کنیم که تمام تلاشمون رو برای ارائه سرویس هر چه بهتر انجام بدیم.

9️⃣ امکان بازگشت وجه دارید؟

✅ امکان بازگشت وجه در صورت حل نشدن مشکل از سمت ما وجود دارد.

💡 در صورتی که جواب سوالتون رو نگرفتید میتونید به «پشتیبانی» مراجعه کنید.";
    $cart_to_cart_dec = "
برای افزایش موجودی به صورت دستی، مبلغ دلخواه را به شماره‌ی حساب زیر واریز کنید 👇🏻

==================== 
6037000000000000 - bank
====================

🌅 عکس رسید خود را در این مرحله ارسال نمایید. 

⚠️ حداکثر واریز مبلغ 10 میلیون تومان می باشد.
⚠️ امکان برداشت وجه از کیف پول  نیست.
⚠️ مسئولیت واریز اشتباهی با شماست.
";
    $text_channel = "   
        ⚠️ کاربر گرامی؛ شما عضو چنل ما نیستید
از طریق دکمه زیر وارد کانال شده و عضو شوید
پس از عضویت متن زیر را ارسال کنید
/start";
    if (!$table_exists) {
        $result = $connect->query("CREATE TABLE textbot (
        id_text varchar(600) PRIMARY KEY NOT NULL,
        text TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_bin");
        if (!$result) {
            echo "table textbot".mysqli_error($connect);
        } else {
            echo "Table 'textbot' created successfully!"."</br>";
        }
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_start','سلام خوش آمدید') ");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_usertest','🔑 اکانت تست')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_Purchased_services','🛍 مشاهده سرویس های خریداری شده')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_dec_info','$text_info')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_support','☎️ پشتیبانی')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_dec_support','$support_dec')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_help','📚 آموزش')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_bot_off','❌ ربات خاموش است، لطفا دقایقی دیگر مراجعه کنید')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_roll','$text_roll')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_fq','❓ سوالات متداول')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_dec_fq','$text_dec_fq')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_account','👨🏻‍💻 مشخصات کاربری')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_sell','🔐 خرید اشتراک')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_Add_Balance','💰 افزایش موجودی')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_cart_to_cart','$cart_to_cart_dec')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_channel','$text_channel')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_Discount','🎁 کد هدیه')");
    }
    else{
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_start','سلام خوش آمدید')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_usertest','🔑 اکانت تست')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_Purchased_services','🛍 مشاهده سرویس های خریداری شده')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_dec_info','$text_info')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_support','☎️ پشتیبانی')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_dec_support','$support_dec')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_help','📚 آموزش')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_bot_off','❌ ربات خاموش است، لطفا دقایقی دیگر مراجعه کنید')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_roll','$text_roll')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_fq','❓ سوالات متداول')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_dec_fq','$text_dec_fq')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_account','👨🏻‍💻 مشخصات کاربری')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_sell','🔐 خرید اشتراک')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_Add_Balance','💰 افزایش موجودی')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_cart_to_cart','$cart_to_cart_dec')");
        $connect->query("INSERT IGNORE INTO textbot (id_text,text) VALUES ('text_channel','$text_channel')");
        $connect->query("INSERT INTO textbot (id_text,text) VALUES ('text_Discount','🎁 کد هدیه')");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
