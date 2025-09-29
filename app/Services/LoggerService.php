<?php


namespace app\Services;

class LoggerService

{
    private static ?Loggerservice $MyLogger = null;
//  myloger = null or = new Loggerservice
    private string $routeLogger;

//  path for log file
    private function __construct()
    {
        $this->routeLogger = storage_path("logs/route.log");
    }

//    route logger path
    public static function getLogger(): Loggerservice
    {
        if (self::$MyLogger == null) {
            self::$MyLogger = new Loggerservice();
        }

        return self::$MyLogger;
    }

    public function log(string $message)
    {
        $date = date("Y-m-d H:i:s");
        file_put_contents($this->routeLogger, "$date $message\n", FILE_APPEND);


    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }


}
