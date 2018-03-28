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


class ProductController extends Controller {
    public function productGoodsCatagoryAction() {
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
	
	
	// 增加规则集数据
	function insert($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			$id = $conn->lastInsertId(); // 获取当前添加到产品表（product）的主键id
			// newVal:存在当前新创建的产品的关联规则集id数组，  relevance格式：[1,2,3,...]
			if(array_key_exists('associatedData', $rows)){
				// 向关联表添加关联字段 (产品id,规则集id,规则集状态,状态代号 )
				$colums = array("product_id","group_id","status");
				for ($i = 0; $i < count($rows->associatedData); $i++) {
					$val = array(
						$id,
						$rows->associatedData[$i]->id,
						$rows->associatedData[$i]->state
					);	
					$result = $conn->insert("group_product",$val,$colums);
	            }
			}
			if($result){
				$rm->setMessage("创建产品成功！");
			}
			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("创建产品失败 ".$ex);
		}finally{
			$conn = null;
		}
	}
	// 删除规则集数据
	function delete($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			// 删除当前产品？ 先删除产品表（product）中的数据，再删除关联表（group_product）中对应的数据。
			$sql = "delete from product WHERE product.id=".$rows->product_id;
    		$result = $conn->execute($sql, Db::FETCH_ASSOC);
		 	if($result){
		 		$sql = "delete from group_product WHERE group_product.product_id=".$rows->product_id;
    			$result = $conn->execute($sql, Db::FETCH_ASSOC);
				if($result){
					$rm->setMessage ("产品删除成功!");
				}
		 	}
			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("产品删除失败 ".$ex);
		}finally{
			$conn = null;
		}
	}
	// 修改规则集数据
	function update($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			$obj = $this->formatData($rows);
			$result = $conn->update("product", $obj['colums'], $obj['values'], "id = ".$rows->product_id);
			if($result){
				// 需要修改关联表（group_product）数据 ?先删除所有关联数据，再增加。 (* 删除所有的关联数据的原因在于：不清楚用户是只修改了当前产品关联规则集的状态，还是直接删除|新增当前产品所关联的规则集)
				if(array_key_exists('associatedData', $rows)){
					$colums = array("product_id","group_id","status");
					// 先删除关联表（group_product）中所有当前产品关联的规则集数据。
					$sql = "delete from group_product WHERE group_product.product_id=".$rows->product_id;
            		$result = $conn->execute($sql, Db::FETCH_ASSOC);
					// 再将当前产品关联的所有规则集数据添加进关联表（group_product）中。
					for ($i = 0; $i < count($rows->associatedData); $i++) {
						$val = array(
							$rows->product_id,
							$rows->associatedData[$i]->id,
							$rows->associatedData[$i]->state
						);	
						$conn->insert("group_product", $val ,$colums);
		            }
				}
				if($result){
					$rm->setMessage("产品修改成功!");
				}
			}
			$conn->commit();
			$rm->setCode(1);// 向前端返回当前行为操作的状态
		}catch(\Exception $ex){
			$conn->rollback();
			$rm->setCode(0);// 向前端返回当前行为操作的状态
			$rm->setMessage("产品修改失败 ".$ex);
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
			// 查询当前产品对应的关联规则集数据 ？通过产品id 获取 关联表（group_product）中对应的规则集id，通过查询到的关联规则集id去 规则集表（group）中取到对应的整条数据。
			if(array_key_exists('associated', $rows)){
				// 关联规则集数据
				$sql = "SELECT g.*,gp.status FROM group_product AS gp JOIN `group` AS g ON g.id = gp.group_id 
					WHERE gp.product_id =".$rows->associated;
			}else{ // 查询整张表
				$sql = "select * from product";
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
			if($key == "associatedData" || $key == 'product_id'){
			}else{
				array_push($colums,$key);
				array_push($values,$value);
			}
		}
		$obj = array('colums'=>$colums,'values'=>$values);
		return	$obj;
	}
}
