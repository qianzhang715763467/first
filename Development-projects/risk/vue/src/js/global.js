var globalData = {
	$splice:(arr,id) => { // 删除数组中指定值
		for(let i = arr.length-1;i >= 0; i--){
			if(arr[i].id == id){
				arr.splice(i,1);
			}
		}
	}
}
export default globalData;
