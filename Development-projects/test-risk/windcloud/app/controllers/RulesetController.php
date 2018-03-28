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


class RulesetController extends Controller {
    public function rulesetGoodsCatagoryAction() {
    	header("Access-Control-Allow-Origin: *");// 跨域请求操作
    	$rm = $this->di->get("rm");//返回到前端的约定规则 。$rm:详见 utils->ResultMessage.php
    	$result = "";
    	$insert_rows = json_decode($this->request->get('insert_rows'));
    	$delete_rows = json_decode($this->request->get('delete_rows'));
		$update_rows = json_decode($this->request->get('update_rows'));
		$select_rows = json_decode($this->request->get('select_rows'));
		// 当前操作执行哪种行为？
		if(($insert_rows != null) && (count($insert_rows) > 0)){
			$this->insert($insert_rows,$rm);
		}
		if(($delete_rows != null) && (count($delete_rows) > 0)){
			$this->delete($delete_rows,$rm);
		}
		if(($update_rows != null) && (count($update_rows) > 0)){
			$this->update($update_rows,$rm);
		}
		if(($select_rows != null) && (count($select_rows) > 0)){
			$this->select($select_rows,$rm);
		}
		
		echo $rm->toJson();
    }
	
	// 增加 规则集
	function insert($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			$obj = $this->formatData($rows);
			$result = $conn->insert("group",$obj['values'],$obj['colums']);
			if($result){
				// relevance:存在当前新创建的规则集的关联规则id数组，  relevance格式：[1,2,3,...]
				if(array_key_exists('associatedData', $rows)){
					$id = $conn->lastInsertId();// 获取规则集表中最后一条数据的主键id
					// 向关联表添加关联字段 (规则集id,规则id,规则状态,状态代号 )
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
			}
			if($result){
				$rm->setMessage ("规则集数据创建成功!");
			}

			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("新增失败 ".$ex);
		}finally{
			$conn = null;
		}
    }
	
	// 删除 规则集&关联规则数据
	function delete($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			// 删除当前规则集？ 先删除规则集表（group）中的数据，再删除关联表（rule_group）中对应的数据。
		 	$sql = "delete from `group` WHERE id=".$rows->group_id;
    		$result = $conn->execute($sql, Db::FETCH_ASSOC);
		 	if($result){
		 		$sql = "delete from rule_group WHERE rule_group.group_id=".$rows->group_id;
    			$result = $conn->execute($sql, Db::FETCH_ASSOC);
				if($result){
					$rm->setMessage ("规则集删除成功!");
				}
			}

			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("删除失败 ".$ex);
		}finally{
			$conn = null;
		}
    }
	
	// 修改 规则集&关联规则数据
	function update($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			// 由规则集详情页面 提交的修改数据。 1.修改规则集关联id数据。2.可能会修改关联表（rule_group）	
			$id = $rows->group_id;
			$obj = $this->formatData($rows);
			$result = $conn->update("group", $obj['colums'], $obj['values'], "id=".$id);
			if($result){
				// 需要修改关联表（group_product）数据 ?先删除所有关联数据，再增加。 (* 删除所有的关联数据的原因在于：不清楚用户是只修改了当前产品关联规则集的状态，还是直接删除|新增当前产品所关联的规则集)
				if(array_key_exists('associatedData', $rows)){
					// 先删除关联表（group_product）中所有当前产品关联的规则集数据。
					$sql = "delete from rule_group WHERE rule_group.group_id=".$id;
            		$result = $conn->execute($sql, Db::FETCH_ASSOC);
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
				}
			}
			if($result){
				$rm->setMessage ("规则集修改成功!");
			}

			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("修改失败 ".$ex);
		}finally{
			$conn = null;
		}
    }

	// 查询 规则集数据
	function select($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			// 指定的一条规则集
			if(array_key_exists('group_id',$rows)){
				$sql = "select g.*,gp.status from `group` AS g  LEFT JOIN group_product AS gp ON gp.group_id =g.id WHERE g.id =".$rows->group_id;
			}else if(array_key_exists("associated",$rows)){// 关联的所有规则
				$sql = "SELECT r.*,rg.enabled AS status FROM rule_group AS rg JOIN rule AS r ON r.id=rg.rule_id WHERE rg.group_id =".$rows->associated;
			}else if(array_key_exists('unused', $rows)){// 未使用过的所有规则集
				$sql = "SELECT g.* FROM `group` AS g LEFT JOIN group_product AS gp ON g.id = gp.group_id WHERE gp.group_id IS NULL";
			}else{// 所有规则集
				$sql = "select g.*,gp.status from `group` AS g LEFT JOIN group_product AS gp ON gp.group_id =g.id";
			}
			$result = $conn->fetchAll($sql, Db::FETCH_ASSOC);
			
			$rm->setMessage($result);
			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("查询失败 ".$ex);
		}finally{
			$conn = null;
		}
    }
	
	function formatData($data){
		$values = [];
		$colums = [];
		foreach($data as $key=>$value){
			if($key == "associatedData" || $key == "group_id"){
			}else{
				array_push($colums,$key);
				array_push($values,$value);
			}
		}
		$obj = array('colums'=>$colums,'values'=>$values);
		return	$obj;
	}
}
