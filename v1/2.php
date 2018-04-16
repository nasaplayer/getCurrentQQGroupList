<?php
include 'set.php';
include 'mycurl.php';
include 'myDB.class.php';


$config = [
	'bkn'=>$bkn,
	'Cookie'=>$Cookie,
];

myGroup($config);


function myGroup($config){
	$json = searchGroup($config);
	$jsonObj = json_decode($json,FALSE);
	
	if($jsonObj->ec === 0){
	
		echo $json;	
	
	//请正确配置数据库打开注释
	//addGroupList($jsonObj);
	
	}else{
		echo $json;
	}
	

}

function addGroupList($jsonObj){
	$create = $jsonObj->create;
	$join = $jsonObj->join;
	$manage = $jsonObj->manage;
	
	addGroup($create);
	addGroup($join);
	addGroup($manage);	
}



function addGroup($objData){
	
	$length = count($objData);
	if( $length>0 ){
		$db = new myDB('qqGroup');
		
		if(!$db){
			echo '[database is error]没有正确配置数据库';
			die;
		}
		
		for($i=0;$i<$length; $i++){
			$row = $objData[$i];
			$data = [
				'gc'=>$row->gc,
				'gn'=>$row->gn,
				'owner'=>$row->owner,
			];
			$db->add($data);
		}
	}
	
}


function oneGC(){
$search_count = 1241;
$gc = 154981146;//群号
$bkn = 604955085;
$Cookie =<<<EOD
Cookie: pgv_pvid=5320112910; tvfe_boss_uuid=1f06fa95b8c07166; pgv_pvi=5179461632; pt2gguin=o2919386060; RK=UPfzz08fW1; ptcz=4190295bc9e96a39ea945697d64f15eb4b1fe33ca00796141ea68ed6acf3e050; _ga=GA1.2.854798566.1487326333; o_cookie=250516693; pac_uid=1_2919386060; eas_sid=h1b5L1n723C0n1f1k1O4x9i7m1; ADTAG_KEY=EXTERNAL.MEDIA.ANALYSIS_296; _qpsvr_localtk=0.47070776692253324; pgv_si=s2247405568; uin=o2919386060; skey=@0RWORZsjw; ptisp=ctc; p_uin=o2919386060; pt4_token=tEz6TMEEo1P-u0AkdczdjDnDWQkRuFZ0oh9cwK6bCpg_; p_skey=ray168CjPuOU-KWqdaeiRw3tsOKlUp82l4UU8VIfv4U_
EOD;
$st =0;

$config = [
	'gc'=>$gc,
	'bkn'=>$bkn,
	'Cookie'=>$Cookie,
	'st'=>$st,
	'end'=>$search_count,
];
$json= searchMember($config);
$jsonObj = json_decode($json,FALSE);
$mems = $jsonObj->mems;

$length = count($mems);

	$db = new myDB('qqMember');
	for($i=0 ; $i<$length ; $i++ ){
		$row = $mems[$i];
		$data = setRow($row,$gc);
		$db->add($data);
	}


 }
 


	
function setRow($row,$gc){
	$level = $row->lv->level;
	$point = $row->lv->point;
	$add_time = date('Y-m-d H:i',$row->join_time);
	$last_time = date('Y-m-d H:i',$row->last_speak_time);
	
	$row->lv =json_encode(	$row->lv , JSON_FORCE_OBJECT);
	
	$data = [
		'card'=>$row->card,
		'flag'=>$row->flag,
		'g'=>$row->g,
		'join_time'=>$row->join_time,
		'last_speak_time'=>$row->last_speak_time,
		'lv'=>$row->lv,
		'level'=>$level,
		'point'=>$point,
		'nick'=>$row->nick,
		'qage'=>$row->qage,
		'role'=>$row->role,
		'tags'=>$row->tags,
		'uin'=>$row->uin,
		
		'gc'=>$gc,
		'add_time'=>$add_time,
		'last_time'=>$last_time,
	];
	return $data;
}
