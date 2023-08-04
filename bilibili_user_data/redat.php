<?

error_reporting(E_ALL ^ E_NOTICE); // 显示除去 E_NOTICE 之外的所有错误信息
$uid = $_POST["uid"];
if ($uid != null) {
    $file_contents = curl_get_https('https://api.bilibili.com/x/relation/stat?vmid=' . $uid);
    $appeal2 = curl_get_https('https://api.bilibili.com/x/space/navnum?mid=' . $uid);
    $appeal3 = curl_get_https('https://api.bilibili.com/x/space/upstat?mid=' . $uid);
    $arr['a1'] = json_decode($file_contents, true);
    $arr['a2'] = json_decode($appeal2, true);
    $arr['a3'] = json_decode($appeal3, true);
    $rets_bilibili['follwer'] = $arr['a1']['data']['follower'];
    $rets_bilibili['tougao'] = $arr['a2']['data']['video'] + $arr['a2']['data']['album'] + $arr['a2']['data']['audio'] + $arr['a2']['data']['article'];
    $rets_bilibili['view'] = $arr['a3']['data']['archive']['view'];
    $rets_bilibili['likes'] = $arr['a3']['data']['likes'];
    echo json_encode($rets_bilibili);
}

function curl_get_https($url)
{
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    $cookie = "SESSDATA=" . $_POST['sessdata'];
    curl_setopt($curl, CURLOPT_COOKIE, $cookie); //发送cookie
    $tmpInfo = curl_exec($curl); // 返回api的json对象
    curl_close($curl);
    return $tmpInfo; // 返回json对象
}