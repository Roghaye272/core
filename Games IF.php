<?php
require_once __DIR__ . '/vendor/autoload.php';

use Telegram\Bot\Api;

$telegram = new Api(7299920870:AAGyKW7d2b92cz4VhhmvTuLegr457jgfS_A);

// دریافت آخرین آپدیت‌ها
$updates = $telegram->getWebhookUpdates();

// اگر پیام دریافت شده باشد
if (isset($updates["message"])) {
    $message = $updates["message"];
    $chatId = $message["chat"]["id"];
    $text = $message["text"] ?? '';
    $fromId = $message["from"]["id"];
    
    // بررسی اگر پیام از یک گروه باشد
    if (isset($message["chat"]["type"]) && $message["chat"]["type"] == "group" || $message["chat"]["type"] == "supergroup") {
        // دستور /addmembers
        if (strpos($text, '/addmembers') === 0) {
            // بررسی اینکه کاربر ادمین است یا خیر
            $admins = $telegram->getChatAdministrators(['chat_id' => $chatId]);
            $isAdmin = false;
            
            foreach ($admins as $admin) {
                if ($admin["user"]["id"] == $fromId) {
                    $isAdmin = true;
                    break;
                }
            }
            
            if ($isAdmin) {
                // استخراج آیدی گروه مقصد از دستور
                $parts = explode(' ', $text);
                if (count($parts) >= 2) {
                    $targetGroupId = $parts[1];
                    
                    // دریافت لیست اعضای گروه فعلی
                    $memberCount = $telegram->getChatMembersCount(['chat_id' => $chatId]);
                    $members = [];
                    
                    // محدودیت: تلگرام فقط امکان دریافت 200 عضو را می‌دهد
                    $limit = min($memberCount, 200);
                    
                    // در اینجا باید از متدهای دیگر برای دریافت لیست کامل اعضا استفاده کنید
                    // این یک پیاده‌سازی ساده است
                    
                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "در حال انتقال اعضا به گروه مقصد... (این فرآیند ممکن است زمان‌بر باشد)"
                    ]);
                    
                    // شبیه‌سازی انتقال اعضا
                    // توجه: در واقعیت باید هر کاربر را به گروه مقصد اضافه کنید
                    $successCount = 0;
                    $failedCount = 0;
                    
                    // اینجا باید کد واقعی برای دریافت لیست اعضا و اضافه کردن آنها به گروه مقصد باشد
                    // به دلیل محدودیت‌های API تلگرام، این کار پیچیده است
                    
                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "تعداد {$successCount} عضو با موفقیت منتقل شدند. تعداد {$failedCount} عضو انتقال نیافتند."
                    ]);
                } else {
                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "لطفاً آیدی گروه مقصد را وارد کنید. فرمت صحیح: /addmembers گروه_مقصد_آیدی"
                    ]);
                }
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "شما دسترسی لازم برای این کار را ندارید."
                ]);
            }
        }
    }
    
    // دستور /start
    if ($text == '/start') {
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "به ربات انتقال اعضا خوش آمدید. برای انتقال اعضا از یک گروه به گروه دیگر، در گروه مبدأ دستور /addmembers گروه_مقصد_آیدی را وارد کنید."
        ]);
    }
}

echo 'OK';
?>
