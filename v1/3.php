<?php

include 'set.php';
include 'mycurl.php';
include 'myDB.class.php';

$qqGroup = new myDB('qqGroup');

$mysqli = $qqGroup->db();

//为了避免首次运行时间过长，这里限制为2个群 全部获取去掉LIMIT 0,2:SELECT * FROM qqGroup 

$sql = 'SELECT * FROM qqGroup LIMIT 0,2';

$result_obj = $mysqli->query($sql);
$arr = [];
while($row=$result_obj->fetch_object()){
	$gc = $row->gc;
	$search_count = 5000;//$row->search_count;
	$config = [
		'gc'=>$gc,
		'bkn'=>$bkn,
		'Cookie'=>$Cookie,
		'st'=>$st,
		'end'=>$search_count,
	];
	$json =searchMember($config);
	
	$jsonObj = json_decode($json,FALSE);
	$mems = $jsonObj->mems;
	addMember($gc,$mems);
	$temp = [
		'gc'=>$gc,
		'count'=>$jsonObj->count,
		'max_count'=>$jsonObj->max_count,
		'search_count'=>$jsonObj->search_count,
		'svr_time'=>$jsonObj->svr_time,
		'vecsize'=>$jsonObj->vecsize,
	];
	
	$arr[] =$temp;
}

updateGroup($arr,$mysqli);


function addMember($gc,$mems){
	$db = new myDB('qqMember');
	$length = count($mems);
	for($i=0 ; $i<$length ; $i++ ){
		$row = $mems[$i];
		
		$data = setRow($row,$gc);
		$db->add($data);
	}

}

function setRow($row,$gc){
	$level = $row->lv->level;
	$point = $row->lv->point;
	$join_date = date('Y-m-d H:i',$row->join_time);
	$last_speak_date = date('Y-m-d H:i',$row->last_speak_time);
	$add_time = date('Y-m-d H:i',time() );
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
		
		'join_date'=>$join_date,
		'last_speak_date'=>$last_speak_date,
		'add_time'=>$add_time,
		
	];
	return $data;
}




function updateGroup($arr,$mysqli){
	$length = count($arr);
	for($i=0;$i<$length ;$i++){
		$row = $arr[$i];
		extract($row);
		$update_time = date('Y-m-d H:i',time());
		$create_time = date('Y-m-d H:i',$row['svr_time']);
		
		$sql = "UPDATE qqGroup SET 
		count = {$count},
		max_count = {$max_count},
		search_count = {$search_count},
		svr_time = {$svr_time},
		vecsize = {$vecsize},
		update_time = '{$update_time}',
		create_time = '{$create_time}'
		WHERE gc={$gc}";
		echo $sql;
		$mysqli->query($sql);
	}
	
}

//$qqGroup->co($arr);






