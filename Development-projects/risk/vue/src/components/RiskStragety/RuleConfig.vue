<template>
	<div id="ruleConfig">
		<loding v-if="!action"></loding>
		<template v-if="action">
			<!-- headr ber-->
			<header class="addRulesBtnBox">
				<add-button @click.native="formAction()" 
					v-show="String(action) == 'true'" :params="{val:'新增规则+',route:'true'}" ></add-button>
				<back v-show="String(action) != 'true'" :params="{name:action,modalShow:''}"></back>
				<div class="rule-search">
					<input type="text" id="rule-search" placeholder="请输入规则名" value="" v-model="rulePageAllData.searchField" @keydown="ruleAction({'action':'search','val':rulePageAllData.searchField,'ev':$event})"/>
					<div class="rule-search-but"  @click="ruleAction({'action':'search','val':rulePageAllData.searchField})">Search</div>
				</div>
			</header>
			<section class="tableContainer">
				<!-- 规则列表tab -->
				<div class="tableBox">
					<table border="" class="rule-tab">
						<tr>
							<th>操作</th>
							<th v-for="(item,key) in rulePageAllData.tableData.thead">{{item}}</th>
						</tr>
						<tr v-for="(row,index) in ruleAllData"  @dblclick="rule_details(row)">
							<td class="rule-tools">
								<rule-toolber v-on:rule-action="ruleAction" :params="{'name':action,'values':row}"></rule-toolber>
							</td>
							<td v-for="(item,key) in rulePageAllData.tableData.thead" v-if="key == 'rule_name'" v-html="row[key]" ></td>
						 	<td v-else-if="key == 'isCloud'">
								<svg-icon :id="(!row.isCloud?'icon-afsshujufengkong':'icon-shenhe')" class="cloudAmountIcon"></svg-icon>
						 	</td>
							<td v-else>{{row[key]}}</td>
						</tr>
					</table>
				</div>
				<!-- 要添加到的规则集列表  -->
				<div class="addToRuleset-wrap" v-show="rulePageAllData.associationRulesetList.ruleSetModuleShow">
					<p class="ruleset-name">规则集名</p>
					<section class="addToRuleset-main">
						<ul class="ruleset-items">
							<li class="ruleset-item" v-for="row in notAssociatedData" @click="selected.group_id = row.id ;selected.rule_count = row.rule_count">
								{{row.group_name}}
								<svg-icon id="icon-2xuanzhong" style="color: green;" v-show="row.id == selected.group_id"></svg-icon>
							</li>
							<p class="footer-tip"> 没有可添加的规则集了...</p>
						</ul>
					</section>
					<p class="addRule-confirm">
						<span class="confirm-cancel confirm-tiem" @click="rulePageAllData.associationRulesetList.ruleSetModuleShow = false">取消</span>
						<span class="confirm-add confirm-tiem" @click="ruleAction({'action':''})">确定</span>
					</p>
				</div>
			</section>
			<!--  新增规则form  -->
			<section class="modal" v-show="rulePageAllData.formData.state">
				<p class="bar toolbar" style="text-align: center;line-height: 250%;">
					<a href="javascript:;" @click="formAction('back')"class="toolbar-but">
						<back :params="{name:'ruleConfig'}"></back>
					</a>
					{{ rulePageAllData.formData.state == 'newRule'? '新增规则' : '修改规则'}}
					<a href="#javascript:;" @click="formAction()" class="toolbar-but reset icon-hover">
						<svg-icon class="icons" id="icon-zhongzhi1"></svg-icon>
					</a>
				</p>
				<form>
					<ul class="select-items">
						<li class="select-itme">
							<label class="select-itme-label">规则名称  :</label>
							<div class="select-itme-inputbox">
								<input type="text" placeholder="请输入规则名" v-model="rulePageAllData.formData.modifyData.rule_name" style="text-align: center;" />
							</div>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">规则类型 :</label>
							<select class="select-itme-inputbox" v-model="rulePageAllData.formData.modifyData.isCloud" 
								@change="rulePageAllData.formData.defaultMatchingMode = rulePageAllData.formData.modifyData.match_mode = (rulePageAllData.formData.modifyData.isCloud == 0? '布尔函数':'匹配')">
								<option value="0" selected="selected">风控</option>
								<option value="1" >核额</option>
							</select>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">规则描述 :</label>
							<select class="select-itme-inputbox" v-model="rulePageAllData.formData.modifyData.desc">
								<option value="命中匹配字段" selected="selected">命中匹配字段</option>
								<option value="未命中匹配字段">未命中匹配字段</option>
							</select>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">命中动作 :</label>
							<select class="select-itme-inputbox" v-model="rulePageAllData.formData.modifyData.approve_action">
								<option value="通过" selected="selected">通过</option>
								<option value="拒绝">拒绝</option>
								<option value="人工审核">人工审核</option>
							</select>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">提交字段 :</label>
							<select class="select-itme-inputbox" v-model="rulePageAllData.formData.modifyData.submit_field">
								<option value="手机号" selected="selected">手机号</option>
								<option value="身份证">身份证</option>
							</select>
						</li>
						<li class="select-itme">
							<label class="select-itme-label ">计算字段 :</label>
							<div class="select-itme-inputbox select-itme-countField">
								<input type="text" placeholder="点击右侧按钮输入" id="query-rule" v-model="rulePageAllData.formData.modifyData.calculate_field" readonly="readonly"/>
								<div class="searchBox calculate_field_search" @click.stop="interfaceChange(0)">
									<svg-icon class="query-rule queryIcon" id="icon-xingtaiduICON_sousuo--"></svg-icon>
								</div>
							</div>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">计算依赖字段类型 :</label>
							<div class="select-itme-inputbox" style="background-color: #f2f2f2;">
								<input type="text"  id="depend-field-type" v-model="rulePageAllData.formData.modifyData.cal_depend_field_type" style="text-align: center;background-color: #f2f2f2;" readonly/>
							</div>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">计算依赖字段 :</label>
							<div class="select-itme-inputbox  select-itme-countField">
								<input type="text" placeholder="点击右侧按钮输入" id="selection-rule"  v-model="rulePageAllData.formData.modifyData.cal_depend_field" readonly="readonly"
									data-model="rulePageAllData.formData.modifyData"  :data-key="rulePageAllData.formData.modifyData.cal_depend_field_key"/>
								<div class="selection-rule query-button" @click.stop="interfaceChange('depend')"></div>
							</div>
						</li>
						<li class="select-itme">
							<label class="select-itme-label">计算模式 :</label>
							<select class="select-itme-inputbox" v-model="rulePageAllData.formData.modifyData.match_mode && rulePageAllData.formData.defaultMatchingMode">
								<option value="布尔函数"	v-show="rulePageAllData.formData.modifyData.isCloud == 0">布尔函数</option>
								<option value="加权计算"	v-show="rulePageAllData.formData.modifyData.isCloud == 0">加权计算</option>
								<option value="匹配"		v-show="rulePageAllData.formData.modifyData.isCloud == 1">匹配</option>
							</select>
						</li>
						<li class="select-itme" v-show="rulePageAllData.formData.modifyData.match_mode == '匹配'">
							<label class="select-itme-label">匹配值:</label>
							<div class="select-itme-inputbox">
								<input type="text" placeholder="请输入匹配值..." v-model="rulePageAllData.formData.modifyData.match_value"/>
							</div>
						</li>
						<li class="select-itme"  v-show="rulePageAllData.formData.modifyData.match_mode == '匹配'">
							<label class="select-itme-label">加/减 :</label>
							<select class="select-itme-inputbox" @change="" v-model="rulePageAllData.formData.modifyData.equation" style="text-align: center;  text-align-last: center;">
								<option value="+" selected="selected">+</option>
								<option value="-">-</option>
							</select>
						</li>
						<li class="select-itme" v-show="rulePageAllData.formData.modifyData.match_mode == '匹配'">
							<label class="select-itme-label">额度:</label>
							<div class="select-itme-inputbox">
								<input type="text" placeholder="请输入额度..." v-model="rulePageAllData.formData.modifyData.limit"/>
							</div>
						</li>
						
						<li class="select-itme"  v-show="rulePageAllData.formData.modifyData.match_mode == '加权计算'">
							<label class="select-itme-label">比较 :</label>
							<select class="select-itme-inputbox" @change="" v-model="rulePageAllData.formData.modifyData.calculate_type" style="text-align: center;  text-align-last: center;">
								<option value=">" selected="selected">></option>
								<option value=">=">>=</option>
								<option value="<"> < </option>
								<option value="<="><=</option>
								<option value="=">=</option>
								<option value="!=">!=</option>
								<option value="===">===</option>
							</select>
						</li>
						<li class="select-itme" v-show="rulePageAllData.formData.modifyData.match_mode == '加权计算'">
							<label class="select-itme-label">阈值:</label>
							<div class="select-itme-inputbox">
								<input type="text" placeholder="请输入阈值..." v-model="rulePageAllData.formData.modifyData.threshold"/>
							</div>
						</li>
						<li class="select-itme"  v-show="rulePageAllData.formData.modifyData.match_mode == '加权计算'">
							<label class="select-itme-label">权重:</label>
							<div class="select-itme-inputbox select-itme-weight">
								<input type="text" placeholder="请输入..." v-model="rulePageAllData.formData.modifyData.base_weight"/> 
								+
								<input type="text" placeholder="请输入..." v-model="rulePageAllData.formData.modifyData.cfit_weight"/> 
								x<i>  返回值</i>
							</div>
						</li>
						<li class="select-itme">
							<div class="select-itme-inputbox save marginTop">
								<input type="button" id="rule-submit" @click="formAction('submit')" value="保存" />
							</div>
						</li>
					</ul>
				</form>
			</section>
			<!-- 规则详情 -->
			<rule-details ref="rulesDetails" v-show="rules_details_show"></rule-details>
			<!-- 接口列表  -->
			<interface-list   v-show="interfaceShow"ref="Interface"  @upup="interfaceChange"></interface-list>
			<rule-config v-show="rule_config_show" ref="RuleConfig" @upup="ruleConfig"></rule-config>			
		</template>
	</div>
</template>

<script type="text/ecmascript-6">
	import RuleToolber		from "./../common/rule-config-modules/rule-toolber.vue"// 规则工具栏
	import RuleDetails		from "./../common/rule-config-modules/rule-details.vue" // 规则详情
	import InterfaceList	from "./../common/rule-config-modules/interface.vue" // 接口列表组件
	import RuleConfig	from "./../common/rule-config-modules/rule-config.vue" // 规则配置
	export default {
		name: 'ruleConfig',
		data() {
			return {
				rule_config_show:false,
				rules_details_show:false,
				interface_response_data:{},
				interfaceRes:false,// 接口请求
				interfaceData:{},
				modify_rule:false,
				responseMessage:false,
				configuration_data:false, // Formatted interface configuration data
				interfaceShow:false,//端口列表是否显示
				'action': false, // （rule | create | details） 进入到当前规则页面的动作，默认从导航"规则"按钮进入。同时也是控制view渲染的的开关。只有当其存在值的时候view才会被渲染。
				'fullPath': false, // 记录当前路径，用于再次点击'规则'导航时对比两次路径是否一致
				numberSuccessfulReq: 0, // 请求成功计数器。（该页面有多个ajax请求。为了保证view视图初始化时请求的数据已经返回，所以这里需要个计数器）
				'rulesAllData': '', // 全部规则数据
				'rulesetsAllData': '', // 全部规则集数据
				'associationTableData': '', // 关联表数据
				"responseStatus": '-1', // ajax请求返回状态 （0：失败 | 1：成功）
				selected:{//将当前规则添加到（可添加规则集列表）中指定的规则集
					rule_id:-1,  // 选中的 可添加规则 id
					group_id:-1,// 选中的 可添加规则集 id
					rule_count:0 // 包含规则个数
				},
				notAssociatedData:false,// 可添加当前规则的规则集列表数据
				'rulePageAllData': {
					'searchField': '', // 搜索字段
					'groupAllData': [], // 所有规则集
					'associationRulesetList': { // 添加到规则集列表数据
						ruleSetModuleShow: false,
						selectedRuleSet: '',
						selectedRule_id:null,
						values: []
					},
					'tableData': { // 规则展示table列表数据
						'thead': {
							"id": "规则ID",
							"rule_name": "规则编号",
							"desc": "规则内容",
							"approve_action": "执行动作",
							"threshold": "参数",
							"isCloud":"规则类型"
						},
						'values': []
					},
					'formData':{// 表单数据
						state: false, // 当前保存按钮的动作。新增:newRule || 修改:modifyRule
						defaultMatchingMode:'布尔函数',// 默认匹配模式 (布尔函数 | 加权计算)
						rawData : { // 原始数据
							'rule_name': '', // 规则名
							'rule_id': '', // 规则id
							'interface_id':-1,
							'desc': '命中匹配字段', // 描述
							'create_author': '小张', // 创建人
							'modify_author': '', // 修改人
							'approve_action': '拒绝', // 命中动作
							'submit_field': '手机号', // 提交字段
							'calculate_field': '', // 计算字段
							'calculate_field_key':'',// 计算字段值对应 数据链key
							'cal_depend_field': '', // 计算依赖字段
							'cal_depend_field_type': '', // 计算依赖字段类型
							'cal_depend_field_key': '',// 计算字段值对应 数据链key
							'cal_type_2_cal_field': '', // 计算依赖字段类型2
							'cal_type_3_cal_field': '', // 计算依赖字段类型3
							'cal_type_3_cal_dep_field': '', // 计算依赖字段类型3
							'match_mode': '布尔函数', // 匹配模式
							'match_value':'', // 匹配值
							'equation':'+', // 调额公式
							'limit':'', // 调额
							"calculate_type": '>', // 比较
							"threshold": '', // 阈值
							"base_weight": '', // 第一个权重值
							"cfit_weight": '', // 第二个权重值
							"enabled":0,//当前规则执行状态 (执行中：1 || 未执行：0)
							"isCloud":0,
							'state': '' // 状态
						},
						modifyData:{}// 修改数据
					}
				},
				cloudIcon:`<span class="cloudAmountIcon">
							<svg class="icon" aria-hidden="true">
  							  <use xlink:href="#icon-afsshujufengkong"></use>
						    </svg>
						  </span>`,
				amountIcon:`<span class="cloudAmountIcon" style="color:red">
							<svg class="icon" aria-hidden="true">
  							  <use xlink:href="#icon-shenhe"></use>
						    </svg>
						  </span>`
			}
		},
		watch:{
			rulesAllData(newVal,oldval){ 
				if(newVal.code == '1'){// 请求成功,计数器+1
					this.ruleAllData = newVal.message.JSONunique();
					// 要添加关联规则，过滤掉当前规则集已存在关联的规则
					if(this.$route.query.type != undefined){
						this.ruleFilter(this.ruleAllData );
					}else{
						this.action = true;
					}
				}
			},
			notAssociatedData(newVal,oldval){ 
				if(newVal.code == '1'){// 请求成功,计数器+1
					this.notAssociatedData = newVal.message; 
				}
			},
			responseMessage(newVal,oldval){
				if(newVal.name && newVal.name == 'associated'){
					this.$refs.rulesDetails.pageData.correlation.values = newVal.message;
				}
			},
			responseStatus(curVal,oldVal){// 每次修改完成都要重新初始化数据，做到view及时更新
				alert(curVal.message);
				if(curVal.message == '修改成功' || curVal.message == '新增成功'){
					this.formAction('back');
				}
				this.numberSuccessfulReq = 0;
				this.ready();
			},
			interfaceRes(curVal,oldVal){// 接口列表请求
				if(curVal.code == 1){
					if(curVal.name == 'create'){
						this.$refs.Interface.interfaceData.values = curVal.message;
						curVal.message.map((item) => {
							this.interfaceData[item.id] = item;
						})
					}else{
						this.interface_response_data = JSON.parse(curVal.message[0].interface_json);
						this.$refs.RuleConfig.initData = this.interface_response_data;
						this.formAction('modify',this.modify_rule.val);
					}
				}
			},
			$route(currentVal,oldval){// 监听路由的目的： 当前规则页面由其他页面跳转进来，这时候再次点击导航"规则"数据没有初始化
				if(this.fullPath && this.fullPath != currentVal.path ){
					this.numberSuccessfulReq = 0;
					this.ready();
				}
			},
			numberSuccessfulReq:'formatRuleData'
		},
		computed:{
			ruleTypeSwitch(){
			}
		},
		mounted(){
			this.ready();
		},
		components:{
			"rule-toolber":RuleToolber,
			"rule-details":RuleDetails,
			"interface-list":InterfaceList,
			'rule-config':RuleConfig
		},
		methods: {
			ready(){
				if(this.$route.query.actionName == 'create' || this.$route.query.actionName == 'add-ruleset'){ // 其它页面跳转进来的不需要请求关联表&规则集表，直接给请求成功计数器+2
					this.fullPath = this.$route.fullPath;
				}
				this.sendRequest(this,'/rule/ruleGoodsCatagory?select_rows={}','rulesAllData'); // 全部规则数据
			},
			interfaceChange(msg){// interface show change
				if(msg == 0){// 发送请求
					this.interfaceShow = true;
					this.sendRequest(this,'/interface/interfaceGoodsCatagory?select_rows={}','interfaceRes','create');
				}else if(msg == 'depend'){// 点击“计算依赖字段按钮”
					// 点击了其他空的接口
					if(Object.getOwnPropertyNames(this.interface_response_data).length < 2){
						let str = this.interfaceData[this.modify_rule.val.interface_id].interface_json;
						this.interface_response_data = JSON.parse(str);
						this.$refs.RuleConfig.initData = this.interface_response_data;
					}
					let str = this.rulePageAllData.formData.modifyData.calculate_field_key;
					// 计算字段 已选
					if(str.length > 1){
						// 格式化到关闭前的样式
						this.rule_config_show = true;
						this.$refs.RuleConfig.formatStyle(true);					
					}else{
						alert('请先选择计算字段');
					}
				}else{
					this.interfaceShow = msg.state;
					if(msg.id > -1){// 获取对应接口的数据
						this.rule_config_show = true;
						this.rulePageAllData.formData.modifyData.interface_id = msg.id;
						// 格式化计算字段用的数据，并赋给计算字段列表 。用于之后的eval()转义
						if(msg.interface_json == null){
							this.interface_response_data = {};
						}else{
							this.interface_response_data = JSON.parse(msg.interface_json);
						}
						this.$refs.RuleConfig.initData = this.interface_response_data;
					}
				}
			},
			ruleConfig(msg){
				this.rule_config_show = false;
				if(msg){
					this.rulePageAllData.formData.modifyData.calculate_field 		= msg.calculate_v;
					this.rulePageAllData.formData.modifyData.calculate_field_key 	= msg.calculate_k;
					this.rulePageAllData.formData.modifyData.cal_depend_field_type 	= msg.depend_type;
					if(msg.depend_type == 1){
						this.rulePageAllData.formData.modifyData.cal_depend_field 		= msg.type_1_v;
						this.rulePageAllData.formData.modifyData.cal_depend_field_key 	= msg.type_1_k;
					}else
					if(msg.depend_type == 2){
						this.rulePageAllData.formData.modifyData.cal_depend_field 		= msg.type_2_v;
						this.rulePageAllData.formData.modifyData.cal_depend_field_key 	= msg.type_2_k;
						this.rulePageAllData.formData.modifyData.cal_type_2_cal_field   = msg.type_2_v;
					}else
					if(msg.depend_type == 3){
						this.rulePageAllData.formData.modifyData.cal_depend_field 		= msg.type_3_v;
						this.rulePageAllData.formData.modifyData.cal_depend_field_key 	= msg.type_3_k;
						this.rulePageAllData.formData.modifyData.cal_type_3_cal_field   = msg.type_3_v.split('=>')[0]; // 计算依赖字段类型3
						this.rulePageAllData.formData.modifyData.cal_type_3_cal_dep_field= msg.type_3_v.split('=>')[1]; // 计算依赖字段类型3
					}else{
						this.rulePageAllData.formData.modifyData.cal_depend_field 		= msg.depend_k.substring(msg.depend_k.lastIndexOf('.')+1,msg.depend_k.length);
						this.rulePageAllData.formData.modifyData.cal_depend_field_key 	= msg.depend_k;
					}
				}
			},
			rule_details(row){// 查看规则详细信息
				this.rules_details_show = true;
				for(let i = 0;i < this.$refs.rulesDetails.pageData.values.length; i++){
					let key = this.$refs.rulesDetails.pageData.values[i].field;
					this.$refs.rulesDetails.pageData.values[i].val = row[key];
				}
				// 关联规则集
				// 查询关联数据
				this.sendRequest(this,'/rule/ruleGoodsCatagory?select_rows={"associated":'+row.id+'}','responseMessage',"associated");
			},
			formatRuleData(val,oldval){ // 格式化规则数据
				if(val == 3){
					if(!this.$route.query.actionName){ // 当前页面从导航进来的，需要格式化
						this.associationTableData.sort((a,b) => {return b.group_id - a.group_id});// 关联表排序
						// 格式化出添加到规则集列表所需的所有字段
						for(var i = 0; i < this.rulesetsAllData.length; i++){
							this.rulePageAllData.groupAllData[i] = {
								'group_id'			: this.rulesetsAllData[i].id,
								'group_name'		: this.rulesetsAllData[i].group_name,
								'associationRule_id': [],
								'correlation':[],
								'enabled':this.rulesetsAllData[i].enabled,
								'count'				: this.rulesetsAllData[i].rule_count
							};
							for(var n = 0;n < this.associationTableData.length; n++){
								if(this.rulesetsAllData[i].id == this.associationTableData[n].group_id){
									// 规则集关联规则数据
									this.rulePageAllData.groupAllData[i].correlation.push({
										'rule_id':this.associationTableData[n].rule_id,
										'enabled':this.associationTableData[n].enabled
									})
									this.rulePageAllData.groupAllData[i].associationRule_id.push(this.associationTableData[n].rule_id);
								}
							}
						};
						for(var i = 0; i < this.rulesAllData.length; i++){// 为每一条规则数据添加一个可添加的规则集数组 associationRuleset_id = []
							let $id = this.rulesAllData[i].id;
							this.rulesAllData[i].correlation_ruleset = [];
							let association = 0;// 用于判断当前规则是否存在关联规则集
							// 所有规则集添加到当前规则的可添加规则集列表中 
							this.rulesAllData[i].associationRuleset_id = JSON.parse(JSON.stringify(this.rulePageAllData.groupAllData));
							for(var b = 0; b < this.associationTableData.length; b++){// 筛选：关联表中规则id == 当前规则id
								if($id == this.associationTableData[b].rule_id){
									association = 1;
									// 二次筛选 ： 关联表中当前规则集id == 规则集列表中规则集的id？ 删除当前规则集
									for(var c = this.rulePageAllData.groupAllData.length-1; c > -1; c--){
										//过滤可添加的规则集 （删除"已关联当前规则"的"规则集"）
										if(this.rulePageAllData.groupAllData[c].group_id == this.associationTableData[b].group_id){
											// 另存 "已关联当前规则"的"规则集",查看规则详情时需要展示在“已包含当前规则的规则集”中
											this.rulesAllData[i].correlation_ruleset.push(this.rulesAllData[i].associationRuleset_id[c]);
											// 可添加的规则集 列表中 删除当前规则集
											this.rulesAllData[i].associationRuleset_id.splice(c,1);
										}
									}
								}
							}
							// 再次过滤可添加的规则集 （只保留“为执行”状态的规则集）
							for(let n = this.rulesAllData[i].associationRuleset_id.length-1; n >= 0; n--){
								if(this.rulesAllData[i].associationRuleset_id[n].enabled > 0){
									this.rulesAllData[i].associationRuleset_id.splice(n,1);
								}
							}
							this.rulesAllData[i].associationRuleset_id.sort((a,b) => {a.rule_id = $id;return a.group_id - b.group_id});
							this.rulesAllData[i].removeBtn = (association == 0 ? true:false); // 当前规则是否可 删除&修改
						}
						this.rulePageAllData.tableData.values = this.rulesAllData; 
						this.action = true;
					}else{
						// 获取关联规则id数据
						this.associationTableData = [];
						if(this.$route.query.actionName == 'create'){
							this.associationTableData = this.createData.rawData.correlation_id;
						}else{
							this.associationTableData = this.detailData.variableData.correlation_id;
						}
						for(var i = 0; i < this.rulesAllData.length; i++){
							// 区分哪些规则可以被添加到当前规则集
							this.rulesAllData[i].added = (this.associationTableData.length == 0 ? true : this.isHave(this.associationTableData , this.rulesAllData[i].id) );
						}
						this.rulePageAllData.tableData.values = this.rulesAllData; 
						this.action = this.$route.query.actionName;
					}
					this.numberSuccessfulReq = 0;
				}
			},
			ruleAction(rule){// 规则动作
				this.rulePageAllData.associationRulesetList.ruleSetModuleShow = false;
				switch(rule.action){
					case 'add': // 为规则集增加一个规则
                        if(this.$route.query.actionName =='create'){
                            // 新增规则到创建页
		                   	var row = JSON.parse(JSON.stringify(rule.val));
		                   		row.enabled = "0";
		                   		row.status = "0";
		                   	this.createData.rawData.values.push(row);
                        }
                        if(this.$route.query.actionName=='add-ruleset'){
                        	// 新增规则到详情页
		                   	var row = JSON.parse(JSON.stringify(rule.val));
		                   		row.enabled = "0";
		                   		row.status = "0";
		                   	this.detailData.variableData.values.push(row);
						}
						break;
					case 'remove': // 删除规则
						if(confirm("确定要删除当前规则吗？")){
							// 删除当前规则表数据 ， 删除关联表数据，修改对应规则集数据
							this.sendRequest(this,'/rule/ruleGoodsCatagory?delete_rows={"id":'+ rule.id +'}','responseStatus');
						}
						break;
					case 'modify': // 修改规则
					this.rulePageAllData.formData.state = true;
						this.modify_rule = rule;
						this.sendRequest(this,'/interface/interfaceGoodsCatagory?select_rows={"id":'+rule.val.interface_id+'}','interfaceRes');
						break;
					case 'cancel': // 取消增加
                        if(this.$route.query.actionName =='create'){
                        	for(var i = 0; i < this.createData.rawData.values.length; i++){
		        				if(this.createData.rawData.values[i].id == rule.id){
		        					this.createData.rawData.values.splice(i,1);
		        					break;
		        				}
		        			}
						}else if(this.$route.query.actionName=='add-ruleset'){
							for(var i = 0; i < this.detailData.variableData.values.length; i++){
		        				if(this.detailData.variableData.values[i].id == rule.id){
		        					this.detailData.variableData.values.splice(i,1);
		        					break;
		        				}
		        			}
						}
						break;
					case 'search':// 搜索规则
						// ev 属性没有设置 | 设置了ev属性 & 按下了回车键
						if(!rule.ev || rule.ev.keyCode == 13){
							this.search(rule.val);
						}
						break;
					case 'list': // 打开可添加规则集列表
						this.sendRequest(this,'/rule/ruleGoodsCatagory?select_rows={"notAssociated":'+rule.id+'}','notAssociatedData');
						this.selected.rule_id = rule.id;
						this.selected.group_id  = -1;
						this.rulePageAllData.associationRulesetList.ruleSetModuleShow	= true;
						break;
					default: // “确认”将规则添加到当前规则集,将对应id 放入规则集表中的关联字段
						if(this.selected.group_id > -1){ // 确认 
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
							this.sendRequest(this,'/rule/ruleGoodsCatagory?update_rows='+JSON.stringify(upData),'responseStatus');
						}
						this.rulePageAllData.associationRulesetList.ruleSetModuleShow	= false;
						break;
				}
			},
			search(msg){ // 规则搜索
				let currentField= '<i style="background-color:yellow;">'+msg+'</i>';
				let key = 'rule_name';
				for(let i = 0;i < this.ruleAllData.length; i++){
					let row = this.ruleAllData[i];
					// 清空所有标记
					if(row[key].indexOf('<i style="background-color:yellow;">') > -1){// 当前规则名已经存在查询过的标记？清洗掉标记
						row[key] = row[key].replace('<i style="background-color:yellow;">',"").replace('</i>',"")
					}
					// 根据规则内容搜索相同字段
					if( row[key].indexOf(msg)> -1){
						row[key] = row[key].replace(new RegExp(msg),currentField);
					}
				}
			},
			formAction(stateVal,$currentRule){// 格式化表单数据
				if(stateVal == 'submit'){// 提交表单（当前操作要在格式化 "modifyData" 之前执行）
					var submitState = this.save();
					if(submitState){
						return alert(submitState);
					}
				}else{// 格式化 已选中的计算字段数据
					this.$refs.RuleConfig.formatStyle();
					this.rulePageAllData.formData.state = (stateVal == 'back'? false : (!stateVal? 'newRule' : stateVal)); // 改变当前表单状态
				}
				this.rulePageAllData.formData.defaultMatchingMode = '布尔函数'; // 匹配模式初始化
				// 格式化 "modifyData" (复制一份原始数据，用于form表单的初始化数据结构展示)
				this.rulePageAllData.formData.modifyData = JSON.parse(JSON.stringify(this.rulePageAllData.formData.rawData));
				
				if(stateVal == 'modify'){// 如果表单是修改状态，对已复制的初始数据结构的值进行有需要的变更
                	this.modifyRuleFormat($currentRule);
				}
			},
			modifyRuleFormat(msg){// 规则修改格式化
				for(let i in this.rulePageAllData.formData.modifyData){
            		if(i == 'calculate_field' || i == 'calculate_field_key' || i == 'cal_depend_field' || i == 'cal_depend_field_key'){
        				continue;
        			}else if(i == 'rule_id'){
        				this.rulePageAllData.formData.modifyData[i] = msg['id']; 
        			}else if(i == 'match_mode'){
        				this.rulePageAllData.formData.defaultMatchingMode 	= msg.match_mode == '1'? '加权计算' :(msg.match_mode == '2'? '匹配':'布尔函数');
        				this.rulePageAllData.formData.modifyData.match_mode = msg.match_mode == '1'? '加权计算' :(msg.match_mode == '2'? '匹配':'布尔函数');
        			}else{
        				this.rulePageAllData.formData.modifyData[i] = msg[i];
        			}
            	}
            	// 当前规则存在 "计算字段" & "计算字段"存在值
				if(msg.calculate_field && msg.calculate_field.indexOf("=") > -1){
					var $key = '',
						$val = '';
					$key = msg.calculate_field.split('=')[0].replace("json.",""); // 过滤掉开头字段
                	$val = msg.calculate_field.split('=')[1];
                	// 数据链存在数组结构，只取数组结构之前的 数据链
                	$key = ($key.indexOf('[]') > -1 ? $key.split("[]")[0] : $key);
				}
                // 计算字段属性 "data-key" = 输入框值对应的 数据链key;
                if($key && $key.length > 5){
                	let arr = $key.split('.');
                	let obj = JSON.parse(JSON.stringify(this.interface_response_data));
                	for(let k = 0; k < arr.length;k++){
                		obj = obj[arr[k]];
                	}
                	if(!obj)return;
	                for(var i = 0; i < obj.length; i++){
	           			var $rows = obj[i];
	           			for(var j in $rows){
	           				if($rows[j] == $val){
	           					$key = "json."+$key+'['+i+'].'+j;
								break;
	           				}
	           			}
	           		}
	                this.rulePageAllData.formData.modifyData.calculate_field		= $val || ''; // 计算字段输入框的值
	                this.rulePageAllData.formData.modifyData.calculate_field_key 	= $key; // 计算字段输入框值 对应的  数据链key
	                this.rulePageAllData.formData.modifyData.cal_depend_field_key	= msg.cal_depend_field;
	                let depend_field = msg.cal_depend_field;
	                if(msg.cal_depend_field_type == 2){
	                	depend_field = msg.cal_type_2_cal_field;
	                }else if(msg.cal_depend_field_type == 3){
	                	depend_field = msg.cal_type_3_cal_field+'=>'+msg.cal_type_3_cal_dep_field;
	                }
	                this.rulePageAllData.formData.modifyData.cal_depend_field = depend_field;
                }
                this.$refs.RuleConfig.constData.calculate_v = this.rulePageAllData.formData.modifyData.calculate_field;
				this.$refs.RuleConfig.constData.calculate_k = this.rulePageAllData.formData.modifyData.calculate_field_key;
                this.$refs.RuleConfig.formatStyle(true);	
			},
			save() {// 提交表单数据
				let mode = (this.rulePageAllData.formData.defaultMatchingMode == '布尔函数' ? 0:(this.rulePageAllData.formData.defaultMatchingMode == '加权计算'?1:2));
				let currentData = this.rulePageAllData.formData.modifyData;
				let subData = {
					'interface_id': currentData.interface_id, 								// 接口id
					'rule_name': currentData.rule_name, 									// 规则名
					'desc': currentData.desc, 												// 规则描述
					'create_author': '张**', 												// 创建人
					'modify_author': null, 													// 修改人
					'approve_action': currentData.approve_action, 							// 命中动作
					'submit_field': currentData.submit_field, 								// 提交字段
					'match_mode': mode, 		// 计算模式
					'calculate_field': null, 												// 计算字段
					'cal_depend_field': currentData.cal_depend_field_key || null, 			// 依赖字段
					'cal_depend_field_type': (parseInt(currentData.cal_depend_field_type) > -1 ? currentData.cal_depend_field_type: null),		// 依赖字段类型
					'cal_type_2_cal_field': null,											// 依赖类型2
					'cal_type_3_cal_field': null,											// 依赖类型3 第一个参数
					'cal_type_3_cal_dep_field': null,										// 依赖类型3 第二个参数
					'enabled':currentData.enabled || 0,										// 规则执行状态
					'isCloud':currentData.isCloud || 0,										// 规则类型
					// 非“布尔函数”相关字段，默认 null
					'calculate_type':null,													// 加权计算 -> 比较
					'threshold':null,														// 加权计算 -> 阈值
					'base_weight':null,														// 加权计算 -> 权重第一个参数
					'cfit_weight':null														// 加权计算 -> 权重第二个参数
				};
				// 字段效验
				if(currentData.rule_name.length < 1){
					return '请输入"规则名称"!';
				}
				if(currentData.calculate_field.length < 1){
					return '"计算字段"选项必选！';
				}
				if(mode == 1){// 加权计算
					let checkMsg = this.weightedCheck(this.rulePageAllData.formData);
					if(checkMsg){
						return checkMsg;
					};
					subData.calculate_type = currentData.calculate_type;
					subData.threshold = currentData.threshold;
					subData.base_weight = currentData.base_weight;
					subData.cfit_weight = currentData.cfit_weight;
				}else
				if(mode == 2){// 匹配
					let checkMsg = this.matchCheck(this.rulePageAllData.formData);
					if(checkMsg){
						return checkMsg;
					};
				}
				// 计算字段 key+val
				subData.calculate_field = (currentData.calculate_field_key.replace(/\[.*?\]/g,'[]').replace(/\"/g,""))+'='+ currentData.calculate_field; 
				// 计算依赖字段类型 2
				if(currentData.cal_depend_field_type == 2){
					subData.cal_type_2_cal_field = currentData.cal_type_2_cal_field;
				}
				// 计算依赖字段类型 3
				if(currentData.cal_depend_field_type == 3){
					subData.cal_type_3_cal_field = currentData.cal_type_3_cal_field;
					subData.cal_type_3_cal_dep_field = currentData.cal_type_3_cal_dep_field;
				}
				if(this.rulePageAllData.formData.state  == 'newRule'){ // 新增规则
					this.sendRequest(this,'/rule/ruleGoodsCatagory?insert_rows='+JSON.stringify(subData),'responseStatus');
				}else{ // 修改规则
					subData.rule_id = currentData.rule_id;
					subData.modify_author = currentData.modify_author;
					this.sendRequest(this,'/rule/ruleGoodsCatagory?update_rows='+JSON.stringify(subData),'responseStatus');
				}
			},
			weightedCheck(msg){// 加权模式与匹配模式 校验
				let errorField = '';
				if(msg.modifyData.cal_depend_field.length < 1){
					errorField = '计算依赖字段';
				}else
				if(msg.modifyData.calculate_type.length < 1){
					errorField = '比较';
				}else
				if(msg.modifyData.threshold.length < 1){
					errorField = '阈值';
				}else
				if(msg.modifyData.base_weight.length < 1 || msg.modifyData.cfit_weight.length < 1){
					errorField = '权重';
				}else{// 校验通过
					return false;
				}
				return ('"'+msg.defaultMatchingMode+'" 模式下"'+errorField+ '"选项必填！');
			},
			matchCheck(){
				let errorField = '';
				if(msg.modifyData.cal_depend_field.length < 1){
					errorField = '计算依赖字段';
				}else
				if(msg.modifyData.calculate_type.length < 1){
					errorField = '比较';
				}else
				if(msg.modifyData.threshold.length < 1){
					errorField = '阈值';
				}else
				if(msg.modifyData.base_weight.length < 1){
					errorField = '权重';
				}else{// 校验通过
					return false;
				}
				return ('"'+msg.defaultMatchingMode+'" 模式下"'+errorField+ '"选项必填！');
			},
			ruleFilter(arr){
			 	// 过滤掉不是相同类型的规则
				arr.map((item,i) => {
					if(item.isCloud != this.$route.query.type){
						arr.splice(i,1);
					}
				})
				// 要添加关联规则，过滤掉当前规则集已存在关联的规则
				if(this.$route.query.actionName == 'create'){
					this.action = this.$route.query.actionName;
					this.createData.rawData.values.map((item) => {
						this.$global.$splice(arr,item.id)
					});
				}else
				if(this.$route.query.actionName == 'add-ruleset' && this.detailData.rawData.id){
					this.action = this.$route.query.actionName;
					this.detailData.variableData.values.map((item) => {
						this.$global.$splice(arr,item.id)
					});
				}else{
					this.$router.push('/riskStrategy/ruleConfig');
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
			padding-bottom:10px;
			margin-top:15px;
			width:100%;	
			height:94%;
			overflow: hidden;
			.tableBox{
				width:calc(~"100% + 10px");
				height:100%;
				overflow:auto;
				.rule-tab{
					width:100%;	
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
						.footer-tip{
							padding:10px;
							font-size:12px;
							text-align:center;
							color:#ccc;
						}
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
		
		/*  新增规则  */
		.modal{ 
			position:absolute;
			z-index: 2; 
			top:0; 
			left:0; 
			right:0; 
			bottom:0; 
			margin:auto;
			padding-top:60px;
			width:100%;
			height:100%;
			box-sizing:border-box; 
			background:#fff;
			overflow:hidden;
			form{ 
				padding-right:10vw;
				padding-top:10px;
				width:calc(~"100% + 20px");
				height: 100%;
				overflow-y: auto;
				.select-items{ 
					margin: 0 auto;
					max-width: 600px;
					.select-itme{
						display: block;
						padding:10px 0; 
						.select-itme-label,
						.select-itme-inputbox{
							display: inline-block;
							width: 48%;
							height: 30px;
							line-height: 30px;
						}
						.select-itme-label{ 
							width: 43%;
							padding-right:10%;
							min-width: 120px;
							text-align:right;
						}
						.select-itme-inputbox{
							position: relative;
							cursor: pointer;
							overflow: hidden;
							vertical-align: bottom;
							min-width: 240px;
							border:1px solid #c3c3c3; 
							border-radius: 3px;
							input{ 
								position: relative;
								top: -2px;
								height:100%;
								width: 100%;
								text-align:center; 
								border: none;
							}
							&.save{ 
								width:35%; 
								transform: translateX(110%);
								border-radius:3px;
								border:none;
								input{ 
									top: -1.5px;
									color:#fff;
									background:#34b2c9;
									cursor:pointer;
									border:1px solid #eee;
								}
							} 
							&.marginTop{ 
								margin-top:40px; 
							}  
							&.select-itme-countField{/* 计算字段& 计算依赖字段 */
								input[type='text']{
									min-width: auto;
									width:calc(~"100% - 27px");
									height: 100%;
									border:none;
								}
								input[readonly="readonly"]{
									min-width: auto;
									background-color: #F2F2F2;
								}
								.searchBox{
									position:absolute;
									padding:0 8px;
									width:30px;
									height:30px;
									top:0px;
									right:0px;
									&.calculate_field_search:hover{
                            			background-color: #ffe084;
									}
								}
								.query-button{
									position: absolute;
									top: 0;
									right: 0;
									margin: 0;
									padding: 15px;
									&:hover{
										cursor: pointer;
										&:before{
											border: 14px solid #ffe084;
										}
									}
									&:before{
										position: absolute;
										content: "";
										top: 0px;
										right: 0px;
									}
									&.query-rule{
										&:after{
											position: absolute;
											content: "\2705";
											top: 0px;
											right: 7px;
											text-align: center;
											color: green;
										}
									}
									&.selection-rule{
										&:after{
											position: absolute;
											content: "";
											top: 11px;
											right: 10px;
											border: 3px solid transparent;
											border-top-width: 7px;
											border-top-color: #000;
										}
									}
								}
							}
							&.select-itme-weight{/*权重*/
								border:none;
								input{
									top: -1px;
									min-width: 50px;
									height:28px;
									width: 25%;
									border:1px solid #C3C3C3;
									border-radius: 3px;
									text-align: center;
									margin-right: 4%;
								}
								input:nth-of-type(2){
									margin-left: 4%;
								}
								i{
									display: inline-block;
									padding-left: 5%;
									font-size: .9em;
								}
								
							}
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