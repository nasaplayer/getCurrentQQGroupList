<?php
include 'set.php';
include 'mycurl.php';
include 'myDB.class.php';

$gc = 169352216;//自定义群号

oneGC($gc,$bkn,$Cookie);


function oneGC($gc,$bkn,$Cookie){
	$search_count = 4000;//最大4000 超过也无效

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
	
	if(!empty($jsonObj->mems)){
		$mems = $jsonObj->mems;
	
		$length = count($mems);
		
		//$db = new myDB('qqMember'); //插入数据库操作，请建立数据表，配置myDB.class.php
		for($i=0 ; $i<$length ; $i++ ){
			$row = $mems[$i];
			$oneRow = setRow($row,$gc);
			//$db->add($oneRow); //插入数据库操作，请建立数据表，配置myDB.class.php
			$data[] = $oneRow;
		}
		showVal($data);
		
		
	}else{
		echo $json;
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
