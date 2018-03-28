<template>
	<div id="ruleConfig" v-if="action">
		<!-- headr ber-->
		<header class="addRulesBtnBox">
			<add-button v-show="!$route.query.type" :params="{val:'新增规则+',name:'create-rule',action:'create'}" ></add-button>
			<back v-show="$route.query.type" :params="{}"></back>
			<div class="rule-search">
				<input type="text" id="rule-search" placeholder="请输入规则名" value="" v-model="search.field_val"
					@keydown="ruleAction({'action':'search','ev':$event})" @blur="ruleAction({action:'search'})"/>
				<div class="rule-search-but" @click="ruleAction({action:'search'})">Search</div>
			</div>
		</header>
		<section class="tableContainer">
			<!-- 规则列表tab -->
			<div class="tableBox">
				<table border="" class="rule-tab">
					<tr>
						<th>操作</th>
						<th v-for="(item,key) in pageData.rule_thead">{{item}}</th>
					</tr>
					<tr v-for="(row,index) in pageData.rule_data" @dblclick="ruleAction({action:'details',val:row})">
						<td class="rule-tools">
							<rule-toolber v-on:rule-action="ruleAction" :params="{'name':$route.query.type,'id':$route.query.id,'values':row}"></rule-toolber>
						</td>
						<td v-for="(item,key) in pageData.rule_thead" v-if="key == 'rule_name'" v-html="row[key]"></td>
						<td v-else>{{row[key]}}</td>
					</tr>
				</table>
			</div>
			<!-- 要添加到的规则集列表  -->
			<div class="addToRuleset-wrap" v-show="ruleset_list_show">
				<p class="ruleset-name">规则集名</p>
				<section class="addToRuleset-main">
					<ul class="ruleset-items">
						<li class="ruleset-item" v-for="row in pageData.not_associated_data" @click="selected.group_id = row.id ;selected.rule_count = row.rule_count">
							{{row.group_name}}
							<svg-icon id="icon-2xuanzhong" style="color: green;"  v-show="row.id == selected.group_id"></svg-icon>
						</li>
					</ul>
				</section>
				<p class="addRule-confirm">
					<span class="confirm-cancel confirm-tiem" @click="ruleAction({action:'addRuleset'})">取消</span>
					<span class="confirm-add confirm-tiem" @click="ruleAction({action:'addRuleset',val:true})">确定</span>
				</p>
			</div>
		</section>
		<!-- 规则详情 -->
		<rule-details ref="rulesDetails" v-show="rules_details_show"></rule-details>
		<!-- 新增规则 -->
		<router-view></router-view>
	</div>
</template>

<script type="text/ecmascript-6">
	import RuleToolber		from "./../common/rule-config-modules/rule-toolber.vue"// 规则工具栏
	import RuleDetails		from "./../common/rule-config-modules/rule-details.vue" // 规则详情
	export default {
		name: 'ruleConfig',
		data() {
			return {
				action:false, // view渲染开关
				responseMessage:false,// 异步请求响应状态
				rules_details_show:false, // 规则详情渲染
				ruleset_list_show:false, //  可添加规则集列表
				selected:{//将当前规则添加到（可添加规则集列表）中指定的规则集
					rule_id:-1,  // 选中的 可添加规则 id
					group_id:-1,// 选中的 可添加规则集 id
					rule_count:0 // 包含规则个数
				},
				search:{// 搜索
					field_name:'rule_name',//字段名
					field_val:'' //字段值
				},
				pageData:{
					rule_data:[],
					associated_data:[],// 关联的规则集数据--> 规则详情(包含当前规则的规则集)
					not_associated_data:[],// 为关联的规则集数据--> 可添加的规则集列表
					rule_thead: {
						"id": "规则ID",
						"rule_name": "规则编号",
						"desc": "规则内容",
						"approve_action": "执行动作",
						"threshold": "参数"
					}
				}
			}
		},
		watch:{
			responseMessage(newVal,oldVal){
				if(newVal.code == 1){
					// 所有规则数据
					if(newVal.name == 'rule'){
						this.pageData.rule_data = newVal.message;
						if(this.$route.query.type){
							this.$global.cacheData.values.pageData.varData.values.map((item,i) => {
	        					this.$global.$splice(this.pageData.rule_data,item.id);
	        				})
						}
						this.action = true;
					}else
					// 关联规则集数据
					if(newVal.name == 'associated'){
						this.$refs.rulesDetails.pageData.correlation.values = newVal.message;
					}else
					// 未关联规则集数据
					if(newVal.name == 'notAssociated'){
						this.pageData.not_associated_data = newVal.message;
						this.ruleset_list_show = true;
					}else{
						alert(newVal.message);
						this.ready();
					}
				}
			},
			$route(newVal,oldVal){
				if(newVal.path != oldVal.path){
					this.ready();
				}
			}
		},
		mounted(){
			this.ready();
		},
		components:{
			"rule-toolber":RuleToolber,
			"rule-details":RuleDetails
		},
		methods: {
			ready(){
				// （创建规则集||规则集详情）页面跳转进来的，不需要请求关联表&规则集表
				if(this.$route.query.type){
					// 暂存数据为空，路径改回到当期页面
					if(this.$global.cacheData.values.pageData){
						this.fullPath = this.$route.fullPath;// 记录当前url
					}else{
						this.$router.push("/riskStrategy/ruleConfig");
					}
				}
				// 规则全部数据
				this.sendRequest(this,'/rule/ruleGoodsCatagory?select_rows={}','responseMessage',"rule");
			},
			ruleAction(msg){// 当前页面总的点击事件源头，所有事件由此分发
				if(msg.action == 'list'){// 显示可添加的规则集列表 
					this.sendRequest(this,'/rule/ruleGoodsCatagory?select_rows={"notAssociated":'+msg.id+'}','responseMessage',"notAssociated");
					this.selected.rule_id = msg.id;
					this.selected.group_id  = -1;
					return;
				}
				if(msg.action == 'addRuleset'){//添加到"可添加的规则集列表 "中选中的规则集
					this.addRuleset(msg.val);
					return;
				}
				if(msg.action == 'add'){//添加到指定规则集
					this.$global.cacheData.values.pageData.varData.values.push(JSON.parse(JSON.stringify(msg.val)));
					return;
				}
				if(msg.action == 'cancel'){//取消 “添加到指定规则集”的规则
					this.$global.$splice(
                		this.$global.cacheData.values.pageData.varData.values,
                		msg.id
                	);
					return;
				}
				if(msg.action == 'details'){//查看规则集详情
					this.viewRuleDetails(msg.val);
					return;
				}
				if(msg.action == 'modify'){// 修改当前规则数据
					this.$router.push({
                        path:"/riskStrategy/ruleConfig/create-rule",
                        query:{action:'modify',id:msg.id}
                    });
                    return;
				}
				if(msg.action == 'remove'){//删除当前规则
					if(confirm('确定要删除当前规则？')){
						this.sendRequest(this,'/rule/ruleGoodsCatagory?delete_rows={"id":'+msg.id+'}','responseMessage');
					}
					return;
				}
				if(msg.action == 'search'){// 搜索
					if(!msg.ev || msg.ev.keyCode == 13){
						this.searchFn();
					}
					return;
				}
			},
			viewRuleDetails(msg){// 双击查看规则集详情
				// 查询关联数据
				this.sendRequest(this,'/rule/ruleGoodsCatagory?select_rows={"associated":'+msg.id+'}','responseMessage',"associated");
				this.$refs.rulesDetails.pageData.values.map((row,i) => {
					if(msg[row.field] != undefined){
						row.val = msg[row.field];
					}
				});
				this.rules_details_show = true;
			},
			addRuleset(msg){// 添加到规则集操作
				if(msg && this.selected.group_id > -1){ // 确认 
					let upData = {
						group_id:this.selected.group_id,
						rule_count:parseInt(this.selected.rule_count)+1,
						associatedData:[
							{
								id:this.selected.rule_id,
								status:0
							}
						]
					};
					this.sendRequest(this,'/rule/ruleGoodsCatagory?update_rows='+JSON.stringify(upData),'responseMessage');
				}
				this.ruleset_list_show = false;
			},
			searchFn(){// 搜索
				if(this.search.field_name){
					let name = this.search.field_name;// 字段名
					let val = this.search.field_val; // 字段值
					for(let i = 0;i < this.pageData.rule_data.length; i++){
						let row = this.pageData.rule_data[i];
						// 清空所有标记
						if(row[name].indexOf('<i style="background-color:yellow;">') > -1){// 当前规则名已经存在查询过的标记？清洗掉标记
							row[name] = row[name].replace('<i style="background-color:yellow;">',"").replace('</i>',"")
						}
						// 根据规则内容搜索相同字段
						if( row[name].indexOf(val)> -1){
							row[name] = row[name].replace(new RegExp(val),'<i style="background-color:yellow;">'+val+'</i>');
						}
					}
				}
			}
		}
	}
</script>

<style lang="less" type="text/less">
	@import url("./../../less/common");
	#ruleConfig{ 
		position:relative; 
		padding:15px 10px 15px 15px;
		width:100%;
		height:100%;
		border-radius:5px; 
		background:#fff; 
		.addRulesBtnBox{ 
			width:100%;
			height:6%;
			.rule-search{
				width: 300px;
				float: right;
				font-size: 0;
				text-align: right;
				#rule-search{
					width: 200px;
					padding: 5px;
					border-radius: 5px;
					outline: none;
					border: 1px solid #ccc;
					font-size: 13px;
				}
				.rule-search-but{
					display: inline-block;
					padding:3px 5px;
					margin-left: 10px;
					border-radius: 3px;
					font-size: 14px;
					background-color: #3BA9A9;
					cursor: pointer;
					&:hover{
						color:white;
					}
				}
			}
		}
		.tableContainer{
			.w(100%);
			.h(94%);
			padding-bottom:10px;
			margin-top:15px;
			overflow: hidden;
			.tableBox{
				.w(calc(~"100% + 10px"));
				.h(100%);
				overflow:auto;
				.rule-tab{
					.w(100%);
					text-align:center;
					border-spacing: 0;
					border-collapse: collapse;
					border: 1px solid #ccc;
					tr{
						&:nth-of-type(odd){
							background:#FAFAFA;
						}
						&:hover{
							color: #34b2c9;
						}
						th{
							padding: 7px;
							font-size: 13px;
							color:#fff;
							background:#607d8b;
						}
						td{
							padding:6px;
							font-size: 12px;
						}
					}
				}
			}
			/* 可添加的规则集列表 模块*/
			.addToRuleset-wrap{
				position: absolute;
				z-index: 9;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				margin: auto;
				width: 300px;
				height: 400px;
				font-size: 12px;
				border-radius: 5px;
				box-shadow: 0 0 10px 2px #607D8B;
				background-color: #fff;
				overflow: hidden;
				.ruleset-name{
					padding:10px 0 ;
					color: #fff;
					font-size:13px;
					text-align:center;
					background-color:#009688;
				}
				.addToRuleset-main{
					width: 100%;
					height: calc(~"100% - 72px");
					overflow: hidden;
					.ruleset-items{
						padding:0 15px 20px;
						width:calc(~"100% + 17px");
						height: 100%;
						overflow-y: scroll;
						.ruleset-item{
							position: relative;
							padding: 5px ;
							border-bottom: 1px solid #ccc;
							cursor:pointer;
							&:last-child{
								border: none;
							}
							.icon{
								position:relative;
								float:right;
								top:2px;
							}
						}
					}
				}
				.addRule-confirm{
					width:100%;
					height:35px;
					text-align:center;
					font-size:0px;
					cursor: pointer;
					.confirm-tiem{
						display:inline-block;
						width:50%;
						height:100%;
						line-height:35px;
						font-size:13px;
						&.confirm-cancel{
							color:#999;
							background-color:#f2f2f2;
						}
						&.confirm-add{
							color:#333;
							background-color:#2ab2ca;
						}
						&.confirm-cancel:hover{
							color: #333;
							background-color:#dedede;
						}
						&.confirm-add:hover{
							color: #fff;
						}
					}
				}
			}
		}
		/*  新增规则表格 */
		#rule-wrap{ 
			position: absolute; 
			overflow: hidden;
			z-index: 9; 
			top: 0;
			left: 0;
			padding: 15px;
			width: 100%;
			height: 100%;
			border-radius: 4px;
			background-color: #fff;
			box-shadow: 0 0 10px 3px #ccc;  
			#ruleModule{ 
				overflow: auto;
				width: 100%;
				height: 100%; 
				.ruleModule-items{
					display:flex;
					width:100%; 
					height: 100%;
					border-spacing: 0;
					border-collapse: collapse;
					border: 1px solid #ccc;
					tbody{
						.w(100%);
						.h(100%);
					}
				} 
			} 
			#JSONoutput{ 
				position: absolute; 
				z-index: 2; 
				top: 0;
				left: 0; 
				width: 100%; 
				height: 100%; 
				padding: 15px;
				background-color: #fff;
				overflow: auto; 
			} 
			#ruleModule, #JSONoutput{ 
				ul{ 
					padding-left: 30px;
				}
				li{
					.w(100%);
					.h(25px);
					.l-h(25px);
					padding-left:10px;
				}
				tr{
					display: block;
					.w(100%);
					.h(30px);
					.l-h(30px);
					/*padding: 0px 5px; */
					background-color: white;
					&:hover{
						td,th{
							color:#34b2c9;
						}
					}
					&:nth-child(even){
						background:#eee;
					}
					&:nth-child(odd){
						background:#fff;
					}
					td,th{
						display: inline-block;
						width: 50%;
						height: 100%;
						text-align: left;
						font-size: 12px;
						color:#000;
						background-color: transparent;
						overflow: hidden;
						text-overflow:ellipsis;
						white-space: nowrap;
					}
					&.tr-head{
						.w(100%);
						.h(40px);
						.l-h(40px);
						/*padding: 8px 10px;*/
						background-color: #34b2c9;
						th{
							font-size: 16px;
							text-align:center;
							color:#fff;
							i{
								font-size: 12px;
								color: white;
							}
						}
					}
				}
			}
		} 
	}
</style>