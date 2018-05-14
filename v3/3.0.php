<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
require 'vendor/autoload.php';
require 'getCurrentQQ.class.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$qq = new getCurrentQQ();
$groupList = $qq->getGroupListArr();
$t1 = microtime(true);
creatExcelAll($groupList,$qq,[]);
$t2 = microtime(true);
echo '耗时'.round($t2-$t1,3).'秒<br>';




function creatExcelAll($groupList,$qq,$num=0){
	
	$length = 1;//count($groupList);
	
	
	for($i=0;$i<$length;$i++){
		$row = $groupList[$i];
		$gc = $row['gc']; //群号
		$gn = $row['gn']; //群名字
		$arrData = $qq->getGroupMemberArr($gc,$gn);
		
		$fileName = ($i+1).'_'.$gn;
		createExcelFromTemp($fileName,$arrData);
	}//for
	
	
}//creatExcel


function FromCustomFocusOne($group,$groupList,$qq){
	
	$length = count($groupList);
	
	$tempArr = [];
	
	$dataArr = [];
	
	for($i=0 ; $i<$length ;$i++){
		$row = $groupList[$i];
		$gc = $row['gc'];
		$gn = $row['gn'];
		$tempArr[$gc] = $gn;

	}//
	$info= '';
	foreach($group as $gc){
		$gn = $tempArr[$gc]??'群号不存在';
		if( $gn!='群号不存在'){
			$info .= "获取群 {$gc} 的信息<br>";
			//TO DO
			$data = $qq->getGroupMemberArr($gc,$gn);
			
			$dataArr = array_merge($dataArr,$data);
		}else{
			echo '没有访问群'.$gc.'的权限<br>';
		}
	}
	echo $info;
	
	return $dataArr;
 }//createExcelFromCustom


function createExcelFromTemp($fileName,$arrData){
	$inputFileName = '.\temp\1.xlsx';

	$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
	
	//$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->fromArray($arrData,NULL,'A2');
	
	$fileName= filter($fileName);
	
	$dir = '.\\myExcel\\';
	$pathFull =  $dir.$fileName.'.xlsx';
	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$writer->setPreCalculateFormulas(false);
	$writer->save($pathFull);
	
	echo "生成文件{$pathFull}<br>";
	
}//createExcelFromTemp


function filter($str):string{
	$arr = [
		'\\','/',':','?','<','>','|','：',
	];
	
	foreach($arr as $value){
		$str=str_replace($value,'_',$str);
	}
	
	return $str;
	
}


//$header = [
//['群名称','群号','角色','QQ号','昵称','群名片','性别','Q龄','入群时间','最后发言时间']
//];



//$arrData = array_merge($header,$data);
