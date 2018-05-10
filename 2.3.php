<?php

$json ='';

$gc =186144206;
$qq = new getCurrentQQ();
$json = $qq->getGroupMembers($gc);

$qq->echoJSON($json);



class getCurrentQQ{
	
	protected $bkn='';
	protected $Cookie='';
	
public function __construct(){
	$token = $this->getToken();
	
	$uin = $this->pt_get_uins($token);
	
	$clientkey = $this->pt_get_st($uin,$token);
	
	$arr = $this->jump($token,$uin,$clientkey);
	$skey = $arr['skey'];
	$richURL = $arr['url'];
	
	$p_skey =$this->getPSkey($richURL,$skey);
	$this->bkn = $this->getBkn($skey);
	$this->Cookie = "Cookie: p_skey={$p_skey};uin=o{$uin} ;skey={$skey}";
		
}


public function getGroupMembers($gc){//$bkn,$Cookie,$gc
	$bkn = $this->bkn;
	$Cookie = $this->Cookie;
	$url = 'https://qun.qq.com/cgi-bin/qun_mgr/search_group_members';
	
	//顺序也有关系
	$post = [
		'bkn'=>$bkn,
		'end'=>4000,//结束数量
		'gc'=>$gc, //群号
		'sort'=>0,
		'st' =>0,//起始数量
	];
	$header =[
		$Cookie,
	];
	$json = $this->oneCURL($url,$header,$post);
	return $json;
}


public function oneCURL($url,$header,$post){
	$optionArr = [
		CURLOPT_URL=>$url,
		CURLOPT_HEADER=>0,//将头文件的信息作为数据流输出
		CURLOPT_HTTPHEADER=>$header,
		CURLOPT_POSTFIELDS=>http_build_query($post),
		CURLOPT_RETURNTRANSFER=>1,//完全静默，为0 返回页面
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0,
	];
    $ch = curl_init();
	curl_setopt_array ( $ch , $optionArr);
	$json = curl_exec($ch);
	
	curl_close($ch);
	return $json;
}

public function echoJSON($json){
	echo '<pre>';
	print_r( json_decode($json,false) );
	echo '</pre>';
}

public function getGroupList(){//$bkn,$Cookie
	$bkn = $this->bkn;
	$Cookie = $this->Cookie;
	$url = 'https://qun.qq.com/cgi-bin/qun_mgr/get_group_list';
	$post = [
		'bkn'=>$bkn
	];
	$header =[
		$Cookie,
	];
	
	$json = $this->oneCURL($url,$header,$post);
	return $json;
}

public function charCodeAt($str, $index){
    $char = mb_substr($str, $index, 1, 'UTF-8');
 	$value = null;
    if (mb_check_encoding($char, 'UTF-8')){
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        $value = hexdec(bin2hex($ret));
    }
    return $value;
}


public function getBkn($skey) {
    $hash = 5381;
    for ($i = 0, $len = strlen($skey); $i < $len; ++$i){
    	$hash +=($hash <<5) + $this->charCodeAt($skey, $i);
    	
    }
    return $hash & 2147483647;
}


public function jump($token,$uin,$clientkey){
$url =<<<EOD
https://ssl.ptlogin2.qq.com/jump?clientuin={$uin}&keyindex=9&pt_aid=715030901&daid=73&u1=https%3A%2F%2Fqun.qq.com%2Fmember.html%23&pt_local_tk={$token}&pt_3rd_aid=0&ptopt=1&style=40
EOD;

$Referer =<<<EOD
https://xui.ptlogin2.qq.com/cgi-bin/xlogin?appid=715030901&daid=73&hide_close_icon=1&pt_no_auth=1&s_url=https%3A%2F%2Fqun.qq.com%2Fmember.html%23
EOD;

$Cookie =<<<EOD
Cookie: pt_local_token={$token};clientkey={$clientkey};
EOD;

$header =[
	$Referer,
	$Cookie,
];

	$optionArr = [
		CURLOPT_URL=>$url,
		CURLOPT_HEADER=>1,//将头文件的信息作为数据流输出
		CURLOPT_HTTPHEADER=>$header,
		//CURLOPT_FOLLOWLOCATION=>1,
		CURLOPT_RETURNTRANSFER=>1,//完全静默，为0 返回页面
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0,
		//CURLINFO_HEADER_OUT=>1,
	];
	
    $ch = curl_init();
	curl_setopt_array ( $ch , $optionArr);
	
	$content = curl_exec($ch);
	
	list($header, $body) = explode("\r\n\r\n", $content); 
	// 解析COOKIE 
	
	preg_match("/https:(.*?)rd_aid=0/", $body, $matches); 
	
	$url = $matches[0];
	
	$skey =$this->getCookie($header,'skey');

	$this->tellMe(__FUNCTION__,$header,$body);
	
	
	curl_close($ch);

	$arr = [
		'url'=>$url,
		'skey'=>$skey,
	];

	return $arr;	
}

//ClientKey、clientuin
public function pt_get_st($uin,$token){
$url =<<<EOD
https://localhost.ptlogin2.qq.com:4301/pt_get_st?clientuin={$uin}&callback=ptui_getst_CB&r=0.4266647630782271&pt_local_tk={$token}
EOD;

$Referer =<<<EOD
Referer: https://xui.ptlogin2.qq.com/cgi-bin/xlogin?appid=715030901&daid=73&hide_close_icon=1&pt_no_auth=1&s_url=https%3A%2F%2Fqun.qq.com%2Fmember.html%23
EOD;

$Cookie =<<<EOD
Cookie: pt_local_token={$token};
EOD;

$header =[
	$Referer,
	$Cookie,
];

	$optionArr = [
		CURLOPT_URL=>$url,
		CURLOPT_HEADER=>1,//将头文件的信息作为数据流输出
		CURLOPT_HTTPHEADER=>$header,
		CURLOPT_RETURNTRANSFER=>1,//完全静默，为0 返回页面
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0,
		CURLINFO_HEADER_OUT=>1,
	];
	
    $ch = curl_init();
	curl_setopt_array ( $ch , $optionArr);
	
	$content = curl_exec($ch);
	
	list($header, $body) = explode("\r\n\r\n", $content); 
	// 解析COOKIE 
	
	$clientkey =$this->getCookie($header,'clientkey');
	
	$this->tellMe(__FUNCTION__,$header,$body);

	curl_close($ch);
	
	return $clientkey;	
}

public function pt_get_uins($token){
$url =<<<EOD
https://localhost.ptlogin2.qq.com:4301/pt_get_uins?callback=ptui_getuins_CB&r=0.0760575656488639&pt_local_tk={$token}
EOD;

$Referer =<<<EOD
Referer: https://xui.ptlogin2.qq.com/cgi-bin/xlogin?appid=715030901&daid=73&hide_close_icon=1&pt_no_auth=1&s_url=https%3A%2F%2Fqun.qq.com%2Fmember.html%23
EOD;

$Cookie =<<<EOD
Cookie: pt_local_token={$token};
EOD;

$header =[
	$Referer,
	$Cookie,
];

	$optionArr = [
		CURLOPT_URL=>$url,
		CURLOPT_HEADER=>1,//将头文件的信息作为数据流输出
		CURLOPT_HTTPHEADER=>$header,
		//CURLOPT_FOLLOWLOCATION=>1,
		CURLOPT_RETURNTRANSFER=>1,//完全静默，为0 返回页面
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0,
		CURLINFO_HEADER_OUT=>1,
	];
	
    $ch = curl_init();
	curl_setopt_array ( $ch , $optionArr);
	
	$content = curl_exec($ch);
	
	list($header, $body) = explode("\r\n\r\n", $content); 
	// 解析COOKIE 
	
	preg_match("/var_sso_uin_list=(.*?);ptui_getuins_CB/", $body, $matches); 
	$json =$matches[1];
	$jsonObj = json_decode($json, false);
	
	$user = $jsonObj[0];//使用第一个QQ快速登录
	$uin = $user->uin;

	$this->tellMe(__FUNCTION__,$header,$body);
	curl_close($ch);
	return $uin;	
}

public function getToken(){
$url =<<<EOD
https://xui.ptlogin2.qq.com/cgi-bin/xlogin?appid=715030901&daid=73&hide_close_icon=1&pt_no_auth=1&s_url=https%3A%2F%2Fqun.qq.com%2Fmember.html%23
EOD;

	$optionArr = [
		CURLOPT_URL=>$url,
		CURLOPT_HEADER=>1,//将头文件的信息作为数据流输出

		CURLOPT_RETURNTRANSFER=>1,//完全静默，为0 返回页面
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0
	];
	
    $ch = curl_init();
	curl_setopt_array( $ch,$optionArr);
	
	$content = curl_exec($ch);
	
	list($header, $body) = explode("\r\n\r\n", $content); 

	$pt_local_token =$this->getCookie($header,'pt_local_token');
	
	
	$this->tellMe(__FUNCTION__,$header);
	curl_close($ch);
	return $pt_local_token;
}//end stepOne


public function getCookie($header,$name){
	//"/pt_local_token=(.*?);/"
	$rule = "/{$name}=(.*?);/";
	preg_match($rule, $header, $matches); 

	$value =$matches[1];
	return $value;
}


public function tellMe($text,$header,$body=null){

echo '<div style="maring:10px;padding:5px;border:1px solid pink;overflow:hidden;">';
	echo '<h3>'.$text.'响应头</h3>';
		echo '<pre>';
			echo $header;
		echo '</pre>';
	
	if($body){
		echo '<h3>'.$text.'响应体</h3>';
		echo '<pre>';
			echo $body;
		echo '</pre>';
	}
	
echo '</div>';


}//end tellMe


public function getPSkey($richURL,$skey){
	$optionArr = [
		CURLOPT_URL=>$richURL,
		CURLOPT_HEADER=>1,//将头文件的信息作为数据流输出
		CURLOPT_RETURNTRANSFER=>1,//完全静默，为0 返回页面
		CURLOPT_SSL_VERIFYPEER=>0,
		CURLOPT_SSL_VERIFYHOST=>0
	];
	
    $ch = curl_init();
	curl_setopt_array ( $ch , $optionArr);
	$content = curl_exec($ch);
	
	list($header, $body) = explode("\r\n\r\n", $content); 
	// 解析COOKIE 
	
	$p_skey =$this->getCookie($header,'p_skey');
	
	
	$this->tellMe(__FUNCTION__,$header,$body);
	curl_close($ch);
	
	return $p_skey;	
}//end stepTwo

}//getCurrentQQ