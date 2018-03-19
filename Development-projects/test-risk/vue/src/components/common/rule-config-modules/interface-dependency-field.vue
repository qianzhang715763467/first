<template>
	<div id="dependency-field">
		<div class="interfaceList-wrap" @click="close($event)">
			<section class="interfaceList-main">
				<span class="interfaceList-close">X</span>
				<h6 class="interfaceList-title">{{interfaceData.title}}</h6>
				<div class="interfaceList-content" v-html="interfaceData.htmlText">
					<p class="interfaceList-closing">没有数据了...</p>
				</div>
			</section>
		</div>
	</div>
</template>

<script>
	export default{
		name:'dependency-field',
		data(){
			return{
				interfaceData:{
					title:"计算依赖字段列表",
					htmlText:''
				}
			}
		},
		mounted(){
		},
		methods:{
			each_fieldList(data,tier,curTypeof){
				if(this.interfaceData.htmlText.length < 1){
					this.interfaceData.htmlText = '<ul class="items">';
				}
				// tier = true;说明当前操作处于循环调用当中，curTypeof 表明当前ul展示数据的类型
				if(tier){
					this.interfaceData.htmlText += '<ul class="items" data-instanceof="'+curTypeof+'">';
				}
				for(var i = 0; i < data.length; i++){
					if(data[i].key == 'children'){// 还是数组？继续循环
						this.each_fieldList(data[i].val,true,(data[i-1].val == '{'?'Object':'Array'));
					}else{
						if(data[i].val == ']' || data[i].val == '}'){
							this.interfaceData.htmlText += '<li>'+data[i].val+'</li>';
						}else{
							// 格式化 key
							var key = data[i].key;
							// 包含默认字段 json
							if(key.indexOf('json') > -1){
								key = key.substring(4,key.length);
							}
							// 当前key为数组格式
							if(key.indexOf('[') > 0 && key.indexOf(']') > 0){
								key = key.substring(key.indexOf(']')+1,key.length);
							}
							// 当前key为obj格式
							if(key.lastIndexOf('.') > -1){
								key = key.substring(key.lastIndexOf('.')+1,key.length);
							}
							// 长度 > 0
							if(key.length > 0){
								key += " : ";
								key = key.fixed();
							}
							let li = '';
							if(data[i].val == '{'){// 如果是 object格式 没有类名、索引、类型
								li = '<li data-key="'+data[i].key+'">'+key+data[i].val+'</li>';
							}else if(data[i].val == '['){// 如果是 Aarray格式 设置类名、索引、类型
								li = '<li class="item"  data-key="'+data[i].key+'" data-index="'+ i+'" data-instanceof="Array">'+key+data[i].val+'</li>';
							}else{// 其他 设置类名、索引
								li = '<li class="item"  data-key="'+data[i].key+'" data-index="'+ i+'">'+key+data[i].val+'</li>';
							}
							this.interfaceData.htmlText += li;
						}
					}
				}
				if(tier){
					this.interfaceData.htmlText += '</ul>';
				}
			},
			close(e){// 关闭当前模块（ 只关闭 | 关闭同时返回值）
				let $className = e.target.className || e.target.parentNode.className; 
				// 只关闭
				if($className.indexOf('interfaceList-wrap') > -1 || $className.indexOf('interfaceList-close') > -1){
					this.$emit('upup',{
						'state':false
					});
				// 同时返回值
				}else if($className.indexOf('item') > -1){
					if(e.target.nodeName == 'TT'){// 当前点击的是 key, 当前li拥有子级 <tt>，说明存在键值对
						this.captureBehavior(e.target.parentNode);
					}else{
						this.captureBehavior(e.target);
					}
				}
			},
			captureBehavior(self){// 捕捉 对计算依赖字段的操作 self == li
				let $val = '',
					$key = '',
					$type= '';// 0: 只计算数组的长度,1: 打开依赖字段筛选模块，2： 直接输入
				// 当前点击对象li 的值为 "数组 :["
				if(self.getAttribute('data-instanceof')){
					$val = self.innerText.split(' :')[0];
					$key = self.getAttribute('data-key');
					$type= 1;
				}else{
					// 当前点击对象父辈ul 所展示数据的类型为 Array，并且是一组有序的一维数组。 只计算数组长度
					if(self.parentNode.getAttribute('data-instanceof') == 'Array'){
						let k = self.getAttribute('data-key').split('[')[0]; 
						$val = k.substring(k.lastIndexOf('.')+1,k.length);
						$key = k;
						$type= 1;
					}else
					// 当前点击对象第二个祖辈ul 所展示数据的类型为 Array
					if(self.parentNode.parentNode.getAttribute('data-instanceof') == 'Array'){
						// 点击数组中某个objde 的key
						let k = self.getAttribute('data-key').split('[')[0]; 
						$val = k.substring(k.lastIndexOf('.')+1,k.length);
						$key = k;
						$type= 2;
					}else{
						// 点击非数组的内容，直接输出
						$val = self.innerText.split(' :')[0];
						$key = self.getAttribute('data-key');
						$type= 0;
					}
				}
				this.$emit('upup',{'type':$type,'val':$val,'key':$key,'state':false});
			}
		}
	}
</script>
<style lang="less" type="text/less">

	#dependency-field{
		.items{
			margin-left: 0;
			padding-left: 20px;
			.item{
				list-style: none;
			}
		}
	}
</style>