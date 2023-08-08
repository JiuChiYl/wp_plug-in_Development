<?
if ($_POST['code'] == 100) {
    $get['confName'] = $_POST['confName'];
    $cnw['uid'] = $_POST['userid'];
    $cnw['SESSDATA'] = $_POST['keys'];
    $filename = "./config/config.json";
    $fp = fopen($filename, 'rb');
    $nowdata = json_decode(fread($fp, filesize($filename)), true);
    if (array_search($get['confName'] . ".json", $nowdata['more_config']) !== false) {
        $newdata['use_config'] = $get['confName'] . ".json";
        $newdata['more_config'] = $nowdata['more_config'];
        fclose($fp);
        $fpw = fopen($filename, 'wb');
        fwrite($fpw, json_encode($newdata));
        // config write 写配置文件
        $cnfname = "./config/".$get['confName'].".json";
        $fop = fopen($cnfname,'wb');
        fwrite($fop,json_encode($cnw));
        fclose($fpw);
    } else {
        $newdata['use_config'] = $get['confName'] . ".json";
        array_push($nowdata['more_config'], $get['confName'] . ".json");
        $newdata['more_config'] = $nowdata['more_config'];
        fclose($fp);
        $fpw = fopen($filename, 'wb');
        fwrite($fpw, json_encode($newdata));
        // config write 写配置文件
        $cnfname = "./config/" . $get['confName'] . ".json";
        $fop = fopen($cnfname, 'wb');
        fwrite($fop, json_encode($cnw));
        fclose($fpw);
    }
    $retus['message'] = "配置保存成功!";
    $retus['code'] = $newdata['use_config'];
    echo json_encode($retus);
}
if ($_POST['code'] == 200) {
    $red_fop_path = "./config/config.json";
    $red_fop = fopen($red_fop_path, 'rb');
    $fred_fop = json_decode(fread($red_fop, filesize($red_fop_path)), true);
    for ($i = 0; $i < count($fred_fop['more_config']); $i++) {
        $more_file_path[$i] = "./config/" . $fred_fop['more_config'][$i];
        $more_red[$i] = fopen($more_file_path[$i], 'rb');
        $more_rest[] = json_decode(fread($more_red[$i], filesize($more_file_path[$i])), true);
    }
    $zzth = preg_replace("/.json/", "", $fred_fop['more_config']);
    $retus['code'] = $fred_fop['use_config'];
    echo json_encode(array($more_rest, $zzth,$retus));
}
if($_POST['code']==300){
    $remove_config_list_path = "./config/config.json";
    $remove_config_fop = fopen($remove_config_list_path,'rb');
    $remove_config_data = json_decode(fread($remove_config_fop,filesize($remove_config_list_path)),true);
    $remove_name = $_POST['confName'].".json";
    unlink('./config/'.$remove_name);
    array_splice($remove_config_data['more_config'],array_search($remove_name,$remove_config_data['more_config']),1);
    $remove_config_data['use_config'] = $remove_config_data['more_config'][0];
    $remove_data = json_encode($remove_config_data);
    $remove_fw = fopen($remove_config_list_path,'wb');
    fwrite($remove_fw,$remove_data);
    fclose($remove_fw);
    $retus['message'] = "删除成功!";
    $retus['code'] = $remove_config_data['use_config'];
    echo json_encode($retus);
}
if($_POST['code']==20){
    function curl_get_https($url,$sessdata)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        $cookie = "SESSDATA=".$sessdata;
        curl_setopt($curl, CURLOPT_COOKIE, $cookie); //发送cookie
        $tmpInfo = curl_exec($curl); // 返回api的json对象
        curl_close($curl);
        return $tmpInfo; // 返回json对象
    }
    $config_read_path = "./config/config.json";
    $config_open = fopen($config_read_path,'rb');
    $config_read = json_decode(fread($config_open,filesize($config_read_path)),true);
    $useconfig_path = "./config/".$config_read['use_config'];
    $useconfig_open = fopen($useconfig_path,'rb');
    $useconfig_data = json_decode(fread($useconfig_open,filesize($useconfig_path)),true);
    error_reporting(E_ALL ^ E_NOTICE); // 显示除去 E_NOTICE 之外的所有错误信息
    $uid = $useconfig_data['uid'];
    if ($uid != null) {
        $file_contents = curl_get_https('https://api.bilibili.com/x/relation/stat?vmid=' . $uid,$useconfig_data['SESSDATA']);
        $appeal2 = curl_get_https('https://api.bilibili.com/x/space/navnum?mid=' . $uid,$useconfig_data['SESSDATA']);
        $appeal3 = curl_get_https('https://api.bilibili.com/x/space/upstat?mid=' . $uid,$useconfig_data['SESSDATA']);
        $appeal4 = curl_get_https('https://api.bilibili.com/x/space/v2/myinfo' ,$useconfig_data['SESSDATA']);
        $arr['a1'] = json_decode($file_contents, true);
        $arr['a2'] = json_decode($appeal2, true);
        $arr['a3'] = json_decode($appeal3, true);
        $arr['a4'] = json_decode($appeal4,true);
        $rets_bilibili['follwer'] = $arr['a1']['data']['follower'];
        $rets_bilibili['tougao'] = $arr['a2']['data']['video'] + $arr['a2']['data']['album'] + $arr['a2']['data']['audio'] + $arr['a2']['data']['article'];
        $rets_bilibili['view'] = $arr['a3']['data']['archive']['view'];
        $rets_bilibili['likes'] = $arr['a3']['data']['likes'];
        $rets_bilibili['space'] = "https://space.bilibili.com/".$uid;
        $rets_bilibili['myname'] = $arr['a4']['data']['profile']['name'];
        echo json_encode($rets_bilibili);
    }
    
}