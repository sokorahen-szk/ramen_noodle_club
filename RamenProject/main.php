<?php
include_once VENDOR_DIRECTORY_FULL_PATH_NAME;

use App\Services\BusinessHoursService;
use App\Lib\SheetSearch;
use App\Lib\SheetAlphabet;
use App\Lib\RamenShopCategories;
use App\Lib\DiscordClient;
use App\Lib\SlackClient;
use Dotenv\Dotenv;
use Noodlehaus\Config;
use GuzzleHttp\ClientInterface as IHttpClient;
use GuzzleHttp\Client as HttpClient;

define("DEFAULT_TIMEZONE", date_default_timezone_get());

$dotenv = Dotenv::createImmutable(__DIR__); //.envのパスが異なる場合は変更する
$dotenv->load();

$config = new Config(dirname(__FILE__) . "/config/config.json");
$httpClient = new HttpClient();

$businessHoursService = new BusinessHoursService(
    $config,
    new SheetSearch(),
    new SheetAlphabet(2),
    new RamenShopCategories()
);

$response = [];

try {
    changeTimeZone("Asia/Tokyo");

    //プロセス開始日時
    $startedDatetime = date("Y-m-d H:i:s");

    //結果
    $result = $businessHoursService->run();

    if($result->status) {
        $response = $result->data;
    }

    //プロセス終了日時
    $endedDatetime = date("Y-m-d H:i:s");

    changeTimeZone(DEFAULT_TIMEZONE);

    sendProcessSuccessedNotification($response, $startedDatetime, $endedDatetime, $config, $httpClient);

} catch(\Exception $e) {
    // 例外を出力させない
}

function changeTimeZone($timezone) {
    date_default_timezone_set($timezone);
}

function sendMessageExternalService(string $messageJson, string $type, Config $config, IHttpClient $httpClient) {
        /**
         * @var App\Interfaces\INotificationClient
         */
        $notificationClient = null;
        switch (getenv("NOTIFICATION_SERVICE_NAME")) {
            case "slack":
                $notificationClient = new SlackClient($config, $httpClient);
                break;
            case "discord":
                $notificationClient = new DiscordClient($config, $httpClient);
                break;
            default:
                throw new \Exception("NOTIFICATION_SERVICE_NAME slack or discord を設定してください。");
        }

        $notificationClient->pushNotification($messageJson, $type);
}

function sendProcessSuccessedNotification(
        array $response,
        string $startedDatetime,
        string $endedDatetime,
        Config $config,
        IHttpClient $httpClient
    ) {
    $limit = 30;

    if (count($response) === 0) return;
    if (getenv("CHANGE_CATEGORIES_NOTIFICATION_DEBUG")) {
        $loopCount = ceil(count($response)/$limit);

        for ($i = 0; $i < $loopCount; $i++) {
            $data = array_slice($response, $i * $limit, $limit);

            $responseToEncodeByJson = json_encode([
                "startedDatetime" => $startedDatetime,
                "endedDatetime" => $endedDatetime,
                "response" => $data,
            ]);

            sendMessageExternalService(
                sprintf("処理が完了しました。(%d/%d)\n ```%s```", $i + 1, $loopCount, $responseToEncodeByJson),
                "success",
                $config,
                $httpClient
            );
        }
    }
}
?>
