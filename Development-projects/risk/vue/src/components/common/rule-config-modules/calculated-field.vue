<template>
	<div id="calculated-field">
		<div class="config-wrap" @click="close($event)">{{aaa}}
			<section class="config-main">
				<h6 class="config-title config-bar">{{pageData.title}}</h6>
				<ul class="config-bar toolbar">
					<li class="bar-item search">
						<input type="text" id="search" placeholder="请输入搜索关键字" value="" v-model="searchText"/>
						<div class="search-but" @click="filtration('search')">Search</div>
					</li>
					<li class="bar-item" :class="{'select':textFiltering == '中文'}" @click="filtration('中文')">中文</li>
					<li class="bar-item" :class="{'select':textFiltering == '非中文'}" @click="filtration('非中文')">非中文</li>
				</ul>
				<div class="config-content">
					<ol class="items">
						<li class="item select-itme" v-for="row in pageData.values" :class="{'select-item':(row.key+row.val) == select_field}"
							v-if="row.val !=null && row.val.length > 1" v-show="regular.test(row.val)" @click="selectField(row)" >
							{{row.val}}
						</li>
					</ol>
					<p class="config-closing">没有数据了...</p>
				</div>
			</section>
		</div>
	</div>
</template>

<script>
	export default{
		name:'calculated-field',
		data(){
			return{
				regular:/^/,
				searchText:"",
				textFiltering:false,
				select_field:false,
				aaa:[],
				pageData:{
					title:"计算字段",
					values:[]
				}
			}
		},
		mounted(){
		},
		methods:{
			selectField(msg){
				this.select_field = msg.key+msg.val;
				this.$emit('upup',{
					'key':msg.key,
					'val':msg.val,
					'state':false
				});
			},
			close(e){
				let $className = e.target.className;
				if($className.indexOf('config-wrap') > -1){
					this.$emit('upup',{
						'key':'',
						'val':'',
						'state':false
					});
				}
				return;
			},
			filtration(msg){
				// 取消过滤
				if(this.textFiltering && this.textFiltering == msg){
					this.regular = /^/;
					this.textFiltering = false;
				}else{// 指定过滤字段
					this.textFiltering = msg;
					if(msg == '中文'){
						this.regular = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
					}else if(msg == '非中文'){
						this.regular = /^[a-zA-Z0-9]+$/;
					}else{
						if(this.searchText.length < 1){
							this.regular = /^/;
							this.textFiltering = false;
						}else{
							// 匹配指定文本
							this.regular = new RegExp(this.searchText);
						}
					}
				}
			}
		}
	}
</script>

<style lang="less" type="text/less" scoped>
	#calculated-field{
		.config-main{
			width: 100%;
			height: 100%;
			border-radius: 0;
			.config-bar.toolbar{
				position: absolute;
				top: 4px;
				right: 5px;
				.bar-item{
					display: inline-block;
					margin-right: 5px;
					padding:0 10px;
					height: 90%;
					line-height: 27px;
					color:#666;
					font-size: 13px;
					border-radius: 5px;
					background-color: #F3F3f3;
					cursor: pointer;
					transition: all .2s;
					&.search{
						padding-left: 0;
						font-size: 0;
						background-color: #fff !important;
						input{
							padding: 6px 10px;
							width:200px;
							height: 100%;
							font-size: 13px;
							color: #666;
							border-top-left-radius: 5px;
							border-bottom-left-radius: 5px;
							outline: none;
							border: none;
							background-color: #F2F2F2;
						}
						.search-but{
							display: inline-block;
							padding:0 8px;
							height: 100%;
							font-size: 12px;
							color: #fff;
							border-top-right-radius: 5px;
							border-bottom-right-radius: 5px;
							border-left: 1px solid #ccc;
							box-sizing: border-box;
							background-color: #F2F2F2;
							&:hover{
								color: #fff;
								background-color: #009688;
							}
						}
						
					}
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
	}
</style>