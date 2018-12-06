<?php
/*
$GLOBALS['GAME_ROOT'] = dirname(__FILE__) . "/";
echo $GLOBALS['GAME_ROOT'];die();
echo phpinfo();
*/
/*
$url = 'https://www.baidu.com';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($curl);
curl_close($curl);
var_dump($response);
*/
/** curl 获取 https 请求
* @param String $url 请求的url
* @param Array $data 要?送的??
* @param Array $header 请求时发送的header
* @param int $timeout 超时时间，默认30s
*/
/*
function curl_https($url, $data=array(), $header=array(), $timeout=30){
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 跳过证书检查
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

$response = curl_exec($ch);
print_r($response);
if($error=curl_error($ch)){
die($error);
}

curl_close($ch);

return $response;

}

// 调用
//$url = 'https://www.example.com/api/message.php';
$url = 'https://www.baidu.com';
$data 	= array('name'=>'fdipzone');
$header = array();

$response = curl_https($url, $data, $header, 5);

echo $response;
*/
/*
function curlPost($url, $data, $timeout = 30)
 {
     $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
     $ch = curl_init();
     $opt = array(
             CURLOPT_URL     => $url,
             CURLOPT_POST    => 1,
             CURLOPT_HEADER  => 0,
             CURLOPT_POSTFIELDS      => (array)$data,
             CURLOPT_RETURNTRANSFER  => 1,
             CURLOPT_TIMEOUT         => $timeout,
             );
     if ($ssl)
     {
         $opt[CURLOPT_SSL_VERIFYHOST] = 1;
         $opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
     }
     curl_setopt_array($ch, $opt);
     $data = curl_exec($ch);
     curl_close($ch);
     return $data;
 }
 $data = curlPost('https://www.111cn.net', array('p'=>'hello'));
 echo ($data);
*/
/*
error_reporting ( E_ERROR | E_WARNING | E_PARSE );
$path = "F:/phpstudy/PHPTutorial/WWW/ll/shizi/addons/hs_video/core/web/uploads/video/20181109102215/15417301358d595bbd2d882920.";
var_dump($path);die();
unlink($path);
$str = "IWAP01080524A2232.9806N11404.9355E000.1061830323.8706000908000102,460,0,9520,3671,Home|74-DE-2B-44-88-8C|97& Home1|74-DE-2B-44-88-8C|97&Home2|74-DE-2B-44-88-8C|97& Home3|74-DE-2B-44-88-8C|97#";
$hello = explode(',',$str);
echo date('YmdHis', time());
var_dump($hello);die();
$recv_data_string = "";
sscanf($str, "IWAP%s#", $recv_data_string);
if(strpos($recv_data_string, "01") === 0)
{
    echo "1";
}
*/
/*
$url = 'http://api.cellocation.com:81/loc/?cl=460,0,4173,33452,-15;460,0,4173,63939,-22&wl=00:87:36:05:5d:eb,-23;00:19:e0:e7:5e:b4,-13&output=json';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, false);// 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
$response = curl_exec($curl);
curl_close($curl);
var_dump($response);return;
*/
// 指定允许其他域名访问  
header("Access-Control-Allow-Origin: *");
$arr = array(
	array('word'=>'依,山,傍,水','miss'=>2,'choose'=>'月,山,日,田','correct'=>'2'),
	array('word'=>'耳,目','miss'=>2,'choose'=>'目,日,山,田','correct'=>'1'),
	array('word'=>'天,空','miss'=>2,'choose'=>'目,日,空,田','correct'=>'3'),
);

echo json_encode($arr);
	
?>
