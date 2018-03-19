var globalData = {
	cacheData:{// 进行其他操作时，暂存当前页面所有数据
		status: false, // 进入当前页面的动作（新增 | copy | 添加后返回）
		values:{}
	},
	isHave:(arr,val) => {// 判断数组中是否存在指定值 
		if(arr){
			// 这里拿到的数组可能是一串以 “,”分割的字符串，所以要转一下
			if(arr && arr.indexOf(",")> -1){
				arr = arr.split(',');
			}
		  	for(var i=0; i<arr.length; i++) {
		    	if(arr[i] == val) {
		     		return false;
		    	}else if(i == arr.length-1){
		    		return true;
		    	}
		  	}
		}
	},
	isName:(arr,val,k) => {// 判断object中是否存在指定值 
		for(let i = 0; i < arr.length; i++){
			if(arr[i][k] == val) {
	      		return true;
	    	}
		}
		return false;
	},
	removeVal:(arr,val) => {// 删除数组中的指定值
	  	for(var i=0; i<arr.length; i++) {
	    	if(arr[i] == val) {
		      	arr.splice(i, 1);
		      	break;
	    	}
	  	}
	},
	$splice:(arr,id) => { // 删除数组中指定值
		for(let i = arr.length-1;i >= 0; i--){
			if(arr[i].id == id){
				arr.splice(i,1);
			}
		}
	}
}
export default globalData;
