<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Hdb\Controllers;

use Phalcon\Mvc\Controller;
use Hdb\Utils\MySqlDB;
use Phalcon\Db;


class RuleController extends Controller {
    public function ruleGoodsCatagoryAction() {
    	header("Access-Control-Allow-Origin: *");// 跨域请求操作
    	$rm = $this->di->get("rm");//返回到前端的约定规则 。$rm:详见 utils->ResultMessage.php
    	$result = "";
    	$insert_rows = json_decode($this->request->get('insert_rows'));
    	$delete_rows = json_decode($this->request->get('delete_rows'));
		$update_rows = json_decode($this->request->get('update_rows'));
		$select_rows = json_decode($this->request->get('select_rows'));
		// 当前操作执行哪种行为？
		if(($insert_rows != null) && (count($insert_rows) > 0)){
			$result = $this->utaCtionSheet("增加",$insert_rows,$rm);
		}
		if(($delete_rows != null) && (count($delete_rows) > 0)){
			$result = $this->utaCtionSheet("删除",$delete_rows,$rm);
		}
		if(($update_rows != null) && (count($update_rows) > 0)){
			$result = $this->utaCtionSheet("修改",$update_rows,$rm);
		}
		if(($select_rows != null) && (count($select_rows) > 0)){
			$this->select($select_rows,$rm);
		}
		echo $rm->toJson();
    }
	
	// 查询规则
	function select($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			// 指定规则
			if(array_key_exists('rule_id',$rows)){
				$sql = "select * from rule WHERE rule.rule_id = ".$rows->rule_id;
			}else
			// 已使用当前规则的规则集,包含（规则集状态，当前规则状态）
			if(array_key_exists('associated',$rows)){
				$sql = "SELECT DISTINCT a.enabled AS rule_status,gp.`status`,g.*
						FROM(
							select DISTINCT rg.group_id,rg.enabled 
							from rule AS r 
							left JOIN rule_group AS rg ON rg.rule_id=r.id WHERE r.id=".$rows->associated."
						)a
						JOIN `group` AS g 
						LEFT JOIN group_product AS gp
						ON a.group_id = gp.group_id
						WHERE  g.id = a.group_id";
			}else
			// 未使用当前规则的规则集
			if(array_key_exists("notAssociated",$rows)){
				$sql = "SELECT g.*  
					FROM `group` AS g 
					where g.id not in ( 
						SELECT DISTINCT rg.group_id 
						FROM rule AS r 
						LEFT JOIN rule_group AS rg ON rg.rule_id=r.id 
						WHERE r.id=".$rows->notAssociated." AND rg.group_id IS NOT NULL 
					)
					AND 
					g.isCloud = ( 
						SELECT DISTINCT r.isCloud 
						FROM rule AS r 
						WHERE r.id=".$rows->notAssociated." 
					)";		
			}else{// 所有规则
				$sql = "SELECT r.* ,rg.enabled AS status FROM rule AS r LEFT JOIN rule_group AS rg ON r.id = rg.rule_id";
			}
			$result = $conn->fetchAll($sql, Db::FETCH_ASSOC);
			
			$rm->setMessage($result);
			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage($name."失败 ".$ex);
		}finally{
			$conn = null;
		}
    }
	
	
	// 4中行为的具体操作	(行为，参数，返回到前端的约定规则)
	function utaCtionSheet($name,$rows,$rm){
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			if($name == "增加"){
				$obj = $this->formatData($rows);
//				return $rm->setMessage = $obj;
				$result = $conn->insert("rule",$obj['values'],$obj['colums']);
				if($result){
					$id = $conn->lastInsertId();
					$conn->update("rule",["rule_id"],[$id], "id = ".$id);
					$rm->setMessage ("新增成功");
				}
			}
			if($name == "修改"){// 只修改规则表（rule）？ 更新所有可修改字段
				// 将当前规则添加到指定规则集中
				if(array_key_exists('associatedData', $rows)){
					$id = $rows->group_id;
//					return $rm->setMessage = $rows;
					$result = $conn->update("group", ['rule_count'], [$rows->rule_count], "id = ".$id);
					if($result){
						// 再将当前产品关联的所有规则集数据添加进关联表（group_product）中。
						$colums = array("group_id","rule_id","enabled");
						for ($i = 0; $i < count($rows->associatedData); $i++) {
							$val = array(
								$id,
								$rows->associatedData[$i]->id,
								$rows->associatedData[$i]->status
							);	
							$result = $conn->insert("rule_group", $val ,$colums);
						}
					}
				}else{
					$obj = $this->formatData($rows);
					$result = $conn->update("rule", $obj['colums'], $obj['values'], "id=".$rows->rule_id);
				}
				if($result){
					$rm->setMessage ("修改成功");
				}
			}
			if($name == "删除"){
				// 删除当前规则？ 先删除规则表（rule）中的数据，再删除关联表（rule_group）中对应的数据。
	            $result = $conn->delete("rule","id = ".$rows->id);
				if($result){
					$rm->setMessage ("删除成功");
				}
			}
			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage($name."失败 ".$ex);
		}finally{
			$conn = null;
		}
	}
	function formatData($data){
		$values = [];
		$colums = [];
		foreach($data as $key=>$value){
			if($key == "associatedData" || $key == "rule_id"){
			}else{
				array_push($colums,$key);
				array_push($values,$value);
			}
		}
		$obj = array('colums'=>$colums,'values'=>$values);
		return	$obj;
	}
}
