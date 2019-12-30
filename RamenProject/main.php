<?php

include_once VENDOR_DIRECTORY_FULL_PATH_NAME;

use App\Services\BusinessHoursService;

use App\Exceptions\BusinessHourSheetEmptyException;
use App\Exceptions\ResultResponseMismatchException;
use App\Exceptions\BusinessHourStatusChangeFailureException;
use Dotenv\Dotenv;

//Slack
use App\Lib\SlackClient;

define("END_OF_CELL", "No value");
define("DEFAULT_TIMEZONE", date_default_timezone_get());

$dotenv = Dotenv::createImmutable(__DIR__); //.envのパスが異なる場合は変更する
$dotenv->load();

$slackClient = new SlackClient("success");

function env($EnvPathName, $defaultValue = null) {
    global $dotenv;
    return @getenv($EnvPathName) ?: $defaultValue;
}

function changeTimeZone($timezone) {
    date_default_timezone_set($timezone);
}

$startDateTime = null;
$endDatetime = null;
$response = null;
$responseFormat = null;

$businessHoursService = new BusinessHoursService();
try {

    changeTimeZone("Asia/Tokyo");

    //プロセス開始日時
    $startDateTime = date("Y-m-d H:i:s");

    //結果
    $result = $businessHoursService->run();

    if($result->status) {
        $response = $result->data;
    }

    //プロセス終了日時
    $endDatetime = date("Y-m-d H:i:s");

    changeTimeZone(DEFAULT_TIMEZONE);

    if(CHANGE_CATEGORIES_NOTIFICATION_DEBUG) {
        $responseToEncodeByJson = json_encode([
            "startDateTime"     =>      $startDateTime,
            "endDatetime"       =>      $endDatetime,
            "response"          =>      $response
        ]);

        $slackClient->pushMessage(
            "処理が完了しました。\n" . $responseToEncodeByJson
        );
    }

} catch(BusinessHourSheetEmptyException $e) {
    //
} catch(ResultResponseMismatchException $e) {
    //
} catch(BusinessHourStatusChangeFailureException $e) {
    //
}
?>
