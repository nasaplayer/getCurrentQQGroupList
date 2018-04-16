<?php
set_time_limit(0);

function searchGroup($config){
extract($config);
$url = 'https://qun.qq.com/cgi-bin/qun_mgr/get_group_list';

//顺序也有关系
$post = [
	'bkn'=>$bkn
];

$header =[
	'POST /cgi-bin/qun_mgr/get_group_list HTTP/1.1',
	'Host: qun.qq.com',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
	'Accept: application/json, text/javascript, */*; q=0.01',
	'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
	//'Accept-Encoding: gzip, deflate, br',
	'Referer: https://qun.qq.com/member.html',
	'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
	'X-Requested-With: XMLHttpRequest',
	//'Content-Length: 46',
	//'Cookie: pgv_pvid=5320112910; tvfe_boss_uuid=1f06fa95b8c07166; pgv_pvi=5179461632; pt2gguin=o0250516693; RK=UPfzz08fW1; ptcz=4190295bc9e96a39ea945697d64f15eb4b1fe33ca00796141ea68ed6acf3e050; _ga=GA1.2.854798566.1487326333; o_cookie=250516693; pac_uid=1_2919386060; eas_sid=h1b5L1n723C0n1f1k1O4x9i7m1; pgv_si=s1145650176; _qpsvr_localtk=0.6255258512917101; ptisp=ctc; p_uin=o0250516693; pt4_token=quskWg5JDr*8e0F00z4owhZlytYnai-AdjKOpUzFJbo_; p_skey=u*PnDnSzcWu0LKYrkdzM-yTd-NuEG98nkMnIX8QgSHw_; uin=o0250516693; skey=@vYOXgn07F',
	$Cookie,
	'Connection: keep-alive',
	'Pragma: no-cache',
	'Cache-Control: no-cache',
];

	$json = myCURL($url,$header,$post);

	return $json;
}



function searchMember($config){
extract($config);

$url = 'https://qun.qq.com/cgi-bin/qun_mgr/search_group_members';

//顺序也有关系
$post = [
	'bkn'=>$bkn,
	'end'=>$end,//结束数量
	'gc'=>$gc, //群号
	'sort'=>0,
	'st' =>$st,//起始数量
];


$header =[
	'POST /cgi-bin/qun_mgr/search_group_members HTTP/1.1',
	'Host: qun.qq.com',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
	'Accept: application/json, text/javascript, */*; q=0.01',
	'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
	'Referer: https://qun.qq.com/member.html',
	'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
	'X-Requested-With: XMLHttpRequest',
	$Cookie,
	'Connection: keep-alive',
	'Pragma: no-cache',
	'Cache-Control: no-cache',
];

$json = myCURL($url,$header,$post);
	
	return $json;
}



function myCURL($url,$header,$post){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HEADER,0);
//请求头
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 

//POST参数
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1 ); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10); 
	$res = curl_exec($ch); 
	return $res;
	
}


function showVal($val){
	echo '<pre>';
	print_r($val);
	echo '</pre>';
}
