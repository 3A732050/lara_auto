<?php
require '../vendor/autoload.php';

use \Demo\HelloWorld as World;
use Demo\Hello\Lara;
use Demo\Hello;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;

$world = new World();
$lara = new Lara();
$vincent= new Hello\Someone('Vincent');

// 以下Someone類別的使用可以不用use Demo\Hello;
$mary= new \Demo\Hello\Someone('Mary');
$john= new Demo\Hello\Someone('John');

// create a log channel
$log = new Logger('laravel');
$log->pushHandler(new StreamHandler('../log/my.log', Logger::WARNING));

// add records to the log
$log->warning('Foo');
$log->error('Bar');

// 測試 Carbon
echo '<hr>';
printf("<p>Right now is %s （預設時區）", Carbon::now());
printf("<br>Right now in Toronto is %s（多倫多，GMT-5）", Carbon::now('America/Toronto'));
printf("<br>Right now in Paris is %s（巴黎，GMT+1）", Carbon::now('Europe/Paris'));
printf("<br>Right now in Honolulu is %s（檀香山，GMT-10）", Carbon::now('Pacific/Honolulu'));
printf("<br>Right now in London is %s（倫敦，GMT+1）", Carbon::now('Europe/London'));
printf("<br>Right now in Moscow is %s（莫斯科，GMT+3）", Carbon::now('Europe/Moscow'));
echo '<br>以下3種只是時間表示的不同：';
printf("<br>Right now in Taipei is %s（臺北，GMT+8）", Carbon::now('Asia/Taipei'));
printf("<br>Right now in Taipei is %s（臺北，GMT+8）", Carbon::now('Asia/Taipei')->toDateString());
printf("<br>Right now in Taipei is %s（臺北，GMT+8）", Carbon::now('Asia/Taipei')->toDateTimeString());
echo '<br>';
printf("<br>Right now in Tokyo is %s（東京，GMT+9）", Carbon::now('Asia/Tokyo'));
echo '<hr>';

$tomorrow = Carbon::now('Asia/Taipei')->addDay();
echo '<p>明天時間為：', $tomorrow;

$lastWeek = Carbon::now('Asia/Taipei')->subWeek();
echo '<br>上週時間為：', $lastWeek;

$nextSummerOlympics = Carbon::createFromDate(2018)->addYears(4);
echo '<br>4年後2018年的時間（預設時區）為：', $nextSummerOlympics;

$officialDate = Carbon::now('Europe/London')->toRfc2822String();
$officialDate1 = Carbon::now('Asia/Tokyo')->toRfc2822String();
echo '<p>', $officialDate. '（倫敦目前時間，+0100意思為跟GMT差1小時）';
echo '<br>', $officialDate1. '（東京目前時間，+0900意思為跟GMT差9小時）';

// 使用createFromDate()建立2000/03/11這個時間，接著跟目前時間比較，看差多久時間
$howOldAmI = Carbon::createFromDate(2000, 3, 11,'Asia/Taipei')->age;
echo '<p>', $howOldAmI, '歲';

$noonTodayLondonTime = Carbon::createFromTime(12, 0, 0, 'Pacific/Honolulu');
echo '<p>';

$internetWillBlowUpOn = Carbon::create(2038, 01, 19, 3, 14, 7);

// 設定2000/01/01給setTestNow()
Carbon::setTestNow(Carbon::createFromDate(2000, 1, 1));
// gte為前者是否大於等於後者
if (Carbon::now()->gte($internetWillBlowUpOn)) {
    die();
}
// 因為使用setTestNow()，所以上面if裡面的Carbon::now()只會得到2000/01/01，而不是取得目前時間
Carbon::setTestNow();

echo '判斷目前時間是否為週末，是的話回傳Party!，否則回傳Work!<br>';
if (Carbon::now('Asia/Taipei')->isWeekend())
    echo 'Party!';
else
    echo 'Work!';
echo '<hr>';

// diffForHumans()第二個參數設"true"代表把'ago'、'前'
echo "<p>", Carbon::now()->subMinutes(2)->diffForHumans(); // '2 minutes ago'
echo "<br>", Carbon::now()->subMinutes(2)->diffForHumans(null, true); // 2 minutes
echo "<br>", Carbon::now()->subMinutes(2)->locale('zh_TW')->diffForHumans(); // '2分鐘前'
echo "<br>", Carbon::now()->subMinutes(2)->locale('zh_TW')->diffForHumans(null, true); // '2分鐘'
echo "<br>", Carbon::now()->subDays(5)->locale('ja_JP')->diffForHumans(); // '5日前'
echo "<br>", Carbon::now()->subDays(5)->locale('ja_JP')->diffForHumans(null, true); // '5日'
echo "<br>", Carbon::now()->addMonths(10)->locale('zh_TW')->diffForHumans(); // '9個月後'
echo '<p>以下3種只是時間表示的不同：';
echo "<br>", Carbon::parse('2020-10-11 14:51')->isoFormat('llll'); // Sun, Oct 11, 2020 2:51 PM
echo "<br>", Carbon::parse('2020-10-11 14:51')->isoFormat('dddd'); // Sunday
echo "<br>", Carbon::parse('2020-10-11 14:51')->isoFormat('LLLL'); // Sunday, October 11, 2020 2:51 PM
echo '<br>';
echo "<br>", Carbon::parse('2019-07-23 14:51')->locale('ja_JP')->isoFormat('LLLL'), '（日本時間表示）'; // 2019年7月23日 火曜日 14:51
echo "<br>", Carbon::parse('2019-07-23 14:51')->locale('th_TH')->isoFormat('LLLL'), '（泰國時間表示）'; // Lันอังคารที่ 23 กรกฎาคม 2019 เวลา 14:51
echo "<br>兩週後的時間（預設時區）為：", Carbon::parse()->addWeeks(2);
echo "<br>兩週後的時間為：", Carbon::now('Asia/Taipei')->addWeeks(2);

// 建立時間戳記
$TimeSinceEpoch = Carbon::createFromTimestamp(0)->toDateTimeString();
$TimeSinceEpochTW = Carbon::createFromTimestamp(0, 'Asia/Taipei')->toDateTimeString();
$secondsSinceEpoch = Carbon::createFromTimestamp(0)->diffInSeconds();
$minutesSinceEpoch = Carbon::createFromTimestamp(0)->diffInMinutes();
$hoursSinceEpoch = Carbon::createFromTimestamp(0)->diffInHours();
$daysSinceEpoch = Carbon::createFromTimestamp(0)->diffInDays();
$monthsSinceEpoch = Carbon::createFromTimestamp(0)->diffInMonths();
$yearsSinceEpoch = Carbon::createFromTimestamp(0)->diffInYears();
echo '<p></p>建立時間戳記：<br>'. $TimeSinceEpoch. '（預設時區）<br>'. $TimeSinceEpochTW. '（臺北）<br>';
echo '（預設時區）距離目前時間的秒數：'. $secondsSinceEpoch. '秒<br>（預設時區）距離目前時間的分鐘數：'. $minutesSinceEpoch. '分<br>（預設時區）距離目前時間的小時數：'. $hoursSinceEpoch. '小時<br>';
echo '（預設時區）距離目前時間的天數：'. $daysSinceEpoch. '天<br>'. '（預設時區）距離目前時間的月數：'. $monthsSinceEpoch. '月<br>（預設時區）距離目前時間的年數：'. $yearsSinceEpoch. '年';

