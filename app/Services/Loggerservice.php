<?php


namespace app\Services;

class Loggerservice

{
    private static ?Loggerservice $MyLogger = null;
    // یعنی متغیر میتونه هم مقدارش برابر با null باشه یا یک نمونه ای از کلاس  Loggerservice
    private string $routeLogger;

    //متغیر برای مسیر ثبت logg

    private function __construct()
    {
        $this->routeLogger = storage_path("logs/route.log");
        //مسیر ثبت logg ها
    }

    public static function getLogger(): Loggerservice
    {
        if(self::$MyLogger == null) {
            self::$MyLogger = new Loggerservice();
        }
        //شرط برای برسی این که ایا نمونه ای درست شده است یا نه
        // اگر نمونه ای ساخته نشده باشه یک نمونه درست میکنه و اگر نمونه از قبل ساخته شده باشه همون رو بر میگردونه
        // اینطوری همیشه یک نمونه از کلاس وجود داره

        return self::$MyLogger;
    }

    public function log(string $message)
    {
        $date = date("Y-m-d H:i:s");
        file_put_contents($this->routeLogger, "$date $message\n", FILE_APPEND);
        //متد file_append برای این استفاده میشه که لوگ ها اضافه یشه به انتهای فایل لاگ نه که همه رو پاک کنه
        // php_eol برای این استفاده میشه که هر لاگ در خط جدید باشه

    }
    public function __clone(){}
    public function __wakeup(){}



}

