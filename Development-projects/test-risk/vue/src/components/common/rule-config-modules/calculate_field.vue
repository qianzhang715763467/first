<template>
	<div id="calculate-field">
		<div class="interfaceList-wrap" @click="close($event)">
			<section class="interfaceList-main">
				<span class="interfaceList-close">X</span>
				<h6 class="interfaceList-title">{{pageData.title}}</h6>
				<ul class="bar">{{select_text}}
					<li class="bar-item" :class="{'select':select_text == '只显示中文'}">只显示中文</li>
					<li class="bar-item" :class="{'select':select_text == '不显示中文'}">不显示中文</li>
				</ul>
				<div class="interfaceList-content">
					<ol class="items">
						<li class="item" v-for="row in pageData.values" v-show="regular.test(row.val)" :data-key="row.key">{{row.val}}</li>
					</ol>
					<p class="interfaceList-closing">没有数据了...</p>
				</div>
			</section>
		</div>
	</div>
</template>

<script>
	export default{
		name:'calculate-field',
		data(){
			return{
				regular:/^/,// 过滤字段正则
				select_text:false,//选中过滤样式
				pageData:{
					title:"计算字段列表",
					values:[]
				}
			}
		},
		mounted(){
		},
		methods:{
			close(e){
				let $className = e.target.className; 
				if($className.indexOf('bar-item') > -1){ // 过滤字段
					this.fieldFilter(e.target.innerText);
					return;
				}else if($className.indexOf('interfaceList-wrap') > -1 || $className.indexOf('interfaceList-close') > -1){
					this.$emit('upup',{
						'key':'',
						'val':'',
						'state':false
					});
				}else if($className.indexOf('item') > -1){
					this.$emit('upup',{
						'key':e.target.getAttribute('data-key'),
						'val':e.target.innerHTML ,
						'state':false
					});
				}
			},
			fieldFilter(msg){console.log(1)
				// 取消过滤
				if(this.select_text && this.select_text == msg){
					this.regular = /^/;
					this.select_text = false;
				}else{// 指定过滤字段
					this.select_text = msg;
					if(msg == '只显示中文'){
						this.regular = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
					}else{
						this.regular = /^[a-zA-Z0-9]+$/;
					}
				}
			}
		}
	}
</script>

<style lang="less" type="text/less" scoped>
	#calculate-field{
		.bar{
			left: auto !important;
			padding: 0 !important;
			width: -webkit-max-content !important;
			line-height: initial !important;
			right: 60px !important;
			font-size:0 !important;
			.bar-item{
				display: inline-block;
				margin-right: 5px;
				padding:0 10px;
				height: 40px;
				color:#666;
				line-height: 40px;
				font-size: 13px;
				background-color: #F3F3f3;
				cursor: pointer;
				transition: all .2s;
				&:hover{
					color: #fff;
					background-color: #009688;
				}
				&.select{
					color: #fff;
					background-color: #009688;
				}
			}
		}
	}
</style>