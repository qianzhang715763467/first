<template>
	<div id="rules-details">
		<div class="interfaceList-wrap" @click="close($event)">
			<section class="interfaceList-main">
				<span class="interfaceList-close">X</span>
				<h6 class="interfaceList-title">{{pageData.title}}</h6>
				<div class="interfaceList-content details-content">
					<ol class="items">
						<li class="item" v-for="row in pageData.values" :data-id="row.id">
							<div class="item-name item-text">{{row.name}}</div>
							<div class="item-val item-text">{{row.val}}</div>
						</li>
					</ol>
				</div>
				<h6 class="interfaceList-title correlation-title">{{pageData.correlation.title}}</h6>
				<div class="interfaceList-content tab-main" v-if="pageData.correlation.values.length > 0">
					<div class="tab-head tab">
						<table border="" cellspacing="0" cellpadding="0">
							<colgroup>
								<col v-for="val in Object.getOwnPropertyNames(pageData.correlation.values[0]).length-2"/>
							</colgroup>
							<tr>
								<th>规则集名称</th>
								<th>规则集状态</th>
								<th>当前规则状态</th>
							</tr>
						</table>
					</div>
					<div class="tab-body tab">
						<table border="" cellspacing="0" cellpadding="0">
							<colgroup>
								<col v-for="val in Object.getOwnPropertyNames(pageData.correlation.values[0]).length-2"/>
							</colgroup>
							<tr v-for="row in pageData.correlation.values">
								<td>{{row.group_name}}</td>
								<td :class="row.status == 1?'execution':(row.status == 2 ?'test':'non-execution')">
									{{row.status == 1?'执行中':(row.status == 2 ?'并行测试':'未执行')}}
								</td>
								<td :class="row.rule_status == 1 ?'execution':'non-execution'">
									{{row.rule_status == 1 ?'启用':'未启用'}}
								</td>
							</tr>
						</table>
					</div>
					<p class="interfaceList-closing">没有数据了...</p>
				</div>
				<p class="interfaceList-closing correlationList-closing" v-else>当前规则未被使用</p>
			</section>
		</div>
	</div>
</template>
<script type="text/ecmascript-6">
	export default{
		name:'rules-details',
		data(){
			return{
				pageData:{
					title:'规则详情',
					values:[
						{'name':'规则ID','field':'id','val':''},
						{'name':'规则名称','field':'rule_name','val':''},
						{'name':'创建人','field':'create_author','val':''},
						{'name':'最后修改人','field':'modify_author','val':''},
						{'name':'规则描述','field':'desc','val':''},
						{'name':'命中动作','field':'approve_action','val':''},
						{'name':'提交字段','field':'submit_field','val':''},
						{'name':'计算字段','field':'calculate_field','val':''},
						{'name':'计算依赖字段类型','field':'cal_depend_field_type','val':''},
						{'name':'计算依赖字段','field':'cal_depend_field','val':''},
						{'name':'匹配模式','field':'match_mode','val':''},
						{'name':'比较','field':'calculate_type','val':''},
						{'name':'阈值','field':'threshold','val':''},
						{'name':'权重值 1','field':'base_weight','val':''},
						{'name':'权重值 2','field':'cfit_weight','val':''}
					],
					correlation:{
						title:'包含当前规则的规则集',
						values:[]
					}
				}
			}
		},
		mounted(){
		},
		methods:{
			close(e){// 点击关闭，页面回到初始化
				let $className = e.target.className; 
				if($className.indexOf('interfaceList-wrap') > -1 || $className.indexOf('interfaceList-close') > -1){
					this.pageData.correlation.values.length = 0;
					for(let i = 0; i < this.pageData.values.length; i++){
						this.pageData.values[i].val = '';
					}
					this.$parent.rules_details_show = false;
				}
			}
		}
	}
</script>

<style type="text/less" lang="less">
#rules-details{
	.interfaceList-main{
		height: 80%;
		max-width:600px;
		.correlation-title{
			padding-top: 8px;
		    font-size: 15px;
		    color: #00BCD4;
		}
		.correlationList-closing{
			padding-top: 40px;
			font-size: 16px;
		}
		.interfaceList-content{
			width: calc(~"100% + 22px");
			height: calc(~"50% - 35px");
			overflow-y: auto;
			&.details-content{
				padding-top:15px;
				max-height: 300px;
				.item{
					display:flex;
					padding-top:0;
					.item-text{
						padding-top: 4px;
						padding-bottom: 4px;			
						&.item-name{
							padding-right: 10px;
							width: 25%;
							min-width: 130px;
							text-align: right;
							border-right: 1px solid #ccc;
						}
						&.item-val{
							width: 72%;
							padding-left: 10px;
							overflow: hidden;
							text-indent: 0;
							word-break: break-all;
						}
					}
					
				}
			}
			&.tab-main{
			    display: flex;
			    flex-direction: column;
			    padding-top: 5px;
			    width: 100%;
			    height:auto;
		    }
		}
	}
}
</style>