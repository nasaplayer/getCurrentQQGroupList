<?php
class myDB{
    public $tableName = '';
    public function __construct($tableName){
        $this->tableName = $tableName;
    }
    public function db(){
        $config = array(
            'host'=>'127.0.0.1',
            'user'=>'root',
            'password'=>'root',
            'database'=>'myQQ',
            'port'=>3309,
        );
        $set = array_values($config);
        $mysqli = new mysqli(...$set);
        $query = 'SET NAMES UTF8';
        $mysqli->query($query);
        if($mysqli->errno){
            printf("连接数据库错误<br/> %s",$mysqli->error);
            exit;
        }
        return $mysqli;
    }
    public function add($data){
        $mysqli = $this->db();
        $tableName = $this->tableName;
        $dataArr = $this->filterFromTable($tableName,$data);
        $this->co($dataArr);
        //获得$sql ,$typeList,$needData
        extract($dataArr);
        $stmt = $mysqli->stmt_init();  
        $stmt->prepare($sql);
        $stmt->bind_param($typeList,...$needData);
        if ( $result = $stmt->execute() ){ 
            $insert_id = $stmt->insert_id;
            echo "成功插入ID".$insert_id;
        }else {  
            echo "执行失败".$stmt->errno;
            echo '</br>';
            echo $stmt->error;  
            $insert_id = 0;
        }
        return $insert_id;
    }   
    public function filterFromTable($tableName,$data){
        $sqlField = '';//字段:  a,b,c
        $sqlQ = '';//问号:  ?,?,?
        $typeList = '';//字段的类型: ssdib 
        $needData = array();
        //根据表结构获取字段类型列表
        $fieldTypeArr = $this->fieldTypeArr($tableName);
        $this->co(['fieldTypeArr'=>$fieldTypeArr]);
		
    //第一种、循环Table存在字段
        foreach($fieldTypeArr as $field=>$type){
            //$param = @$data[$field]?:'no-set';//传入参数存在该字段
           $param = @$data[$field]??'no-set';
            if($param !== 'no-set'){
                $sqlField .= $field.',';
                $sqlQ .= '?,';
                $typeList .= $type;
                $needData[] = $param;
            }
        }
        $sqlField = substr( $sqlField,0,strlen($sqlField)-1 ); 
        $sqlQ = substr( $sqlQ,0,strlen($sqlQ)-1 ); 
        $sql = "INSERT INTO {$tableName}({$sqlField}) 
        VALUES({$sqlQ})";
        $dataArr = array(
            'sql'=>$sql,
            'typeList'=>$typeList,
            'needData'=>$needData,
        );
        return $dataArr;
    }
    public function fieldTypeArr($tableName){
        $arr= array();
        $mysqli = $this->db();
        $sql = "DESC {$tableName}";
        $result_obj = $mysqli->query($sql);
        while($row = $result_obj->fetch_object() ){
            $type = $row->Type;
            $field= $row->Field;
            $str = $this->oneFieldType($type);
            $arr[$field] = $str;
        }
        $mysqli->close();
        return $arr;
    }
    public function oneFieldType($type){
        $str = '';
        if(strstr($type,'int')){
            $str = 'i';
        }else if( strstr($type,'float') || strstr($type,'decimal') || strstr($type,'double')   ){
            $str = 'd';
        }else if( strstr($type,'blob') ){
            $str = 'b';
        }else{
            $str = 's';
        }
        return $str;
    }
    public function co($value){
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}