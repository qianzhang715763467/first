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
	
	// 新增 产品
	function insert($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			
			// 向product表中添加一条 新创建的产品数据。默认不存在关联规则集
			$obj = $this->formatData($rows);
			$result = $conn->insert("product",$obj['values'],$obj['colums']);
			if($result){
				$id = $conn->lastInsertId(); // 获取当前添加到产品表（product）的主键id
				if(array_key_exists('associatedData', $rows)){
					// 向关联表添加关联字段 (产品id,规则集id,规则集状态 )
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

	// 删除 产品与关联规则集数据
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
			$rm->setMessage("删除失败 ".$ex);
		}finally{
			$conn = null;
		}
    }

	// 修改 产品与关联规则集数据
	function update($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			$id = $rows->product_id;
			$obj = $this->formatData($rows);
			// 1) 修改产品表
			$result = $conn->update("product", $obj['colums'], $obj['values'], "id = ".$id);
			if($result){
				// 1) 修改关联规则集表。（先删除，再重新添加）
				if(array_key_exists('associatedData', $rows)){
					$colums = array("product_id","group_id","status");
					$sql = "delete from group_product WHERE group_product.product_id=".$id;
					// 先删除关联表（group_product）中所有当前产品关联的规则集数据。
            		$result = $conn->execute($sql, Db::FETCH_ASSOC);
					// 再将当前产品关联的所有规则集数据添加进关联表（group_product）中。
					for ($i = 0; $i < count($rows->associatedData); $i++) {
						$val = array(
							$id,
							$rows->associatedData[$i]->id,
							$rows->associatedData[$i]->status
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
			$rm->setMessage("修改失败 ".$ex);
		}finally{
			$conn = null;
		}
    }

	// 查询 产品数据
	function select($rows,$rm) {
		$result = ""; // 行为操作完成时的返回值
		$conn	= null;
		try{
			$conn = MySqlDB::getConnection();
	        $conn->begin();
			// 指定产品
			if(array_key_exists('product_id',$rows)){
				$sql = "select * from product where product.id=".$rows->product_id;
			}else if(array_key_exists("associated",$rows)){// 关联的规则集
				$sql = "SELECT g.*,gp.status FROM group_product AS gp JOIN `group` AS g ON g.id = gp.group_id 
						WHERE gp.product_id =".$rows->associated;
			}else{// 所有产品
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
