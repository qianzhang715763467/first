function download(url) {
	var loads = layer.load(1);
	var xhh = new XMLHttpRequest();

	page_url = url;
	xhh.open("post", page_url);

	xhh.responseType = 'blob';
	xhh.onreadystatechange = function() {
		layer.close(loads);
		if(xhh.readyState === 4 && xhh.status === 200) {
			var name = xhh.getResponseHeader("Content-disposition");
			/*var filename = name.substring(20,name.length);*/
			var filename = 'B' + new Date().getTime() + '.xls';
			var blob = new Blob([xhh.response], {
				type: 'text/xls'
			});
			var csvUrl = URL.createObjectURL(blob);
			var link = document.createElement('a');
			link.href = csvUrl;
			link.download = filename;
			link.click();

		}
	};
	xhh.send();
}

function download(url) {
	var loads = layer.load(1);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true); // 也可以使用POST方式，根据接口
	xhr.responseType = "blob"; // 返回类型blob

	// 定义请求完成的处理函数
	xhr.onload = function() {
		layer.close(loads);
		// 请求完成
		if(this.status === 200) {
			// 返回200
			var blob = this.response;
			var reader = new FileReader();
			reader.readAsDataURL(blob); // 转换为base64，可以直接放入a表情href
			reader.onload = function(e) {
				// 转换完成，创建一个a标签用于下载
				var a = document.createElement('a');
				a.download = 'B' + new Date().getTime() + '.xls';
				a.href = e.target.result;
				$("body").append(a); // 修复firefox中无法触发click
				a.click();
				$(a).remove();
			}
		}
	};
	// 发送ajax请求
	xhr.send()
}