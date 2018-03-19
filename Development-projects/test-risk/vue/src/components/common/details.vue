<template>
    <div class="details-wrap" id="detailInfo" v-if="action">
        <header class="details-head details-wrap-moudel">
        	<!-- 查看当前产品包含规则集 时，才会传入参数。用于返回时复原数据-->
            <back :params="{action:($route.query.viewOnly?'viewOnly':false)}"></back>
            <!-- 名字 & 状态-->
            <p class="details-wrap-bar details-head-title">
                <span v-show="pageData.constData.status == '1'|| !pageData.modify">
                	{{parsingMessage('name')}}
                </span>
                <input class="titleEdit" type="text" v-show="pageData.modify && pageData.constData.status != '1'" v-model="pageData.varData.name"/>
                <i class="detail-title-status" :class="[parsingSatusClass]" v-show="!pageData.modify">
                	（{{parsingMessage('status')||"未使用"}}）
                </i>
                <select class="executing-state-select" v-if="$route.query.type == 'product'" v-show="pageData.modify" v-model="pageData.varData.status" name="" >
                    <option value="0" selected>未上架</option>
                    <option value="1">上架中</option>
                </select>
            </p>
            <!-- 工具栏 -->
            <ul class="details-head-toolbar" v-if="!$route.query.viewOnly">
            	<!-- 只有 产品 || 规则集('未执行'|'未使用')  才可以修改 -->
            	<template v-if="toolbarControlStatus">
            		<li v-show="!pageData.modify" class="details-head-toolbar-item details-head-toolbar-Modify" @click="isedited">
	                    <svg-icon id="icon-xiugaimima"></svg-icon>
	                </li>
	                <li v-show="pageData.modify" class="details-head-toolbar-item details-head-toolbar-Modify modifyStyle"@click="isedited">
	                   	取消修改
	                </li>
	                <!-- 只有'未上架'的产品 || ('未使用') 的规则集  才可以删除  -->
	                <li v-show="!pageData.modify" class="details-head-toolbar-item details-head-toolbar-delete" @click="isdelete">
	                	<svg-icon id="icon-shanchu"></svg-icon>
	                </li>
            	</template>
            	<!-- 非当前产品关联的规则集 都可以拥有 复制功能 -->
            	<router-link v-if="$route.query.type !='product_ruleset'" v-show="!pageData.modify" tag="li" :to="parsingLinkToCopyData" class="details-head-toolbar-item details-head-toolbar-copy">
	                <svg-icon id="icon-fuzhi"></svg-icon>
	            </router-link>
            </ul>
            <!-- 详细信息  -->
            <div class="details-head-content">
                <ul class="details-head-content-items">
                	<li class="details-head-content-item" :class="{'ruleset':($route.query.type == 'product' ? false:true)}"
                		v-for="row in pageData.constData.message[$route.query.type]">
                		<p class="details-head-content-item-top">{{row.name}}</p>
                        <p class="details-head-content-item-bottom">+{{row.val}}</p>
                	</li>
                </ul>
            </div>
        </header>
        <div class="details-explain details-wrap-moudel"><!-- 描述 -->
            <p class="details-wrap-bar details-explain-title">{{reverseString("desc")}}</p>
            <div class="details-explain-text" v-show="!pageData.modify">
                {{pageData.constData.desc}}
            </div>
            <textarea rows="3" cols="100%" class="change-description" v-show="pageData.modify" v-model="pageData.varData.desc"></textarea>
        </div>
        <!-- 关联数据模块  -->
        <section class="details-main details-wrap-moudel">
            <p class="details-wrap-bar details-main-title">
                {{reverseString("include")}}
                <router-link v-show="pageData.modify && pageData.constData.status == false " @click.native="addingAssociated" tag="a" class="details-main-title-added"
                	:to="{name:''+reverseString('add-link'), query:{type:'details',action:'addAssociation'}}">
                    {{reverseString("add")}}
                </router-link>
            </p>
            <div class="details-main-tabBox">
                <!-- 显示规则集模块 -->
                <template v-if="$route.query.type == 'product'">
                	<div class="ruleSet-card" ref="cardRemove" v-for="row in parsingMessage('values')">
                		<ruleset-card :params="{name:'group',values:row,viewOnly:true,state:pageData.modify}" @action="groupAction"></ruleset-card>
                    </div>
                </template>
                <!-- 显示规则模块 -->
                <template v-else>
                	<table border="1" cellspacing="0" cellpadding="0" v-if="parsingMessage('values').length > 0">
                        <tr>
                            <th v-show="pageData.modify">删除</th>
                            <th v-show="pageData.modify">启用</th>
                            <th v-for="item in tabFiled">{{item}}</th>
                        </tr>
                        <tr ref="deleteTr" v-for="row in parsingMessage('values')" :class="{noDrop:(row.status == 0? true:false)}">
                            <td v-if="pageData.modify" class="delete" @click="deleteRule(row)">
                            	<svg-icon id="icon-shanchu"></svg-icon>
                            </td>
                            <td v-if="pageData.modify" style="position: relative;">
                                <span class="switchBox">
                                	<switch-btn class="swith-btn"  :params="{switch:row}"></switch-btn>
                                </span>
                            </td>
                            <td v-for="(item,key) in tabFiled">{{ row[key] }}</td>
                        </tr>
                    </table>
                </template>
            </div>
        </section>
        <footer class="details-foot details-wrap-moudel" v-show="pageData.modify" @click="commitChanges()">
            <div class="details-save-changes">提交修改</div>
        </footer>
    </div>
</template>

<script>
    import AlertBox from './../common/Alert.vue'
    import SwitchBtn from './../common/Switch.vue'
	
    export default {
        name: "detailInfo",
        msgTxt: "产品",
        data() {
            return {
            	action: false,// view层启动指令
                responseMessage:null,// 请求响应信息
                tabFiled: {		// 规则table展示字段
                    "rule_id": "ID",
                    "rule_name": "规则编号",
                    "desc": "规则内容",
                    "approve_action": "执行动作",
                    "threshold": "参数"
                },
                pageData:{
                	modify:false,// 切换为修改状态，默认false
                	constData:{
                		'id'	: false,
						'name'	: false,
						'status': false,
						'desc'	: false,
						'message':{
							'product':[
								{'name':'通过数量','key':'passed_qty','val':false},
								{'name':'拒绝数量','key':'According_unique_number','val':false},
								{'name':'人工审核','key':'number_manual_audits','val':false},
								{'name':'命中规则集详情','key':'details_hit_rule','val':false}
							],
							'group':[
								{'name':'通过数量','key':'passed_qty','val':false},
								{'name':'拒绝数量','key':'According_unique_number','val':false},
								{'name':'命中规则详情','key':'details_hit_rule','val':false}
							]
						},
						'values': []	// 所有相关数据
                	},
                	varData:{}
                }
            }
        },
        filters:{},
        computed:{
        	toolbarControlStatus(){// 当前详情页为"产品" || "规则集"（并且当前规则集必须是 "未执行"||"未使用"状态）
        		return this.$route.query.type == 'product'||(this.$route.query.type == 'group' && !this.pageData.constData.status);
        	},
        	parsingSatusClass(){
	    		if(this.pageData.constData.status && this.pageData.constData.status == 1){
	    			return 'excute';
	    		}else if(this.pageData.constData.status && this.pageData.constData.status == 2){
	    			return 'test';
	    		}else{
	    			return 'not-excute';
	    		}
	    	},
        	parsingLinkToCopyData(){// copy 参数
	    		return {
	    			'name':this.$route.query.type+'-add',
	    			'query':{
	    				'action':'copy',
	    				'type':this.$route.query.type
    				},
	    			'params':{
	    				'name':this.pageData.constData.name,
	    				'desc':this.pageData.constData.desc
	    			}
    			}
	    	}
        },
        components: {
            AlertBox,
            SwitchBtn
        },
        mounted() {
            this.ready();
        },
        watch: {// 监听 axios 请求是否返回数据
            responseMessage:"responseMessageFormat",
            $route(newVal,oldVal){// 页面路径未发生变化，仅改变了传入参数。刷新页面
            	if(newVal.fullPath != oldVal.fullPath){
            		this.ready();
            	}
            }
        },
        methods: {
            ready() {
            	// 其他页面返回到当前详情页，并且暂存数据中值正确（如果刷新页面，缓存区值是不存在的）
            	if(this.$global.cacheData.status == 'back' && this.$global.cacheData.values.pageData && this.$global.cacheData.values.pageData.constData.id){
            		this.pageData = JSON.parse(JSON.stringify(this.$global.cacheData.values.pageData));
            		this.action = true;
            	}else{
        		// 直接进入当前详情页，判断类型(产品|规则集)
            		if(this.$route.query.type == 'product'){
	            		this.sendRequest(this,'/product/productGoodsCatagory?select_rows={"product_id":'+this.$route.query.id+'}','responseMessage','product');// 当前产品数据
	            		if(this.$route.query.associated_count > 0){// 存在关联数据，获取
	            			this.sendRequest(this,'/product/productGoodsCatagory?select_rows={"associated":'+this.$route.query.id+'}','responseMessage','associated');// 关联数据
	            		}
	            	}else{
	            		// viewOnly 存在，说明是为了查看 产品详情中包含的规则集。缓存当前产品所有参数，以便回到产品详情时使用
						if(this.$route.query.viewOnly){
							this.$global.cacheData.status = 'back';
							this.addingAssociated();
							this.pageData.modify = false;
							// 查询关联之前，清空当前关联。防止数据混淆
							this.pageData.constData.values.length = 0;
						}
						this.sendRequest(this,'/ruleset/rulesetGoodsCatagory?select_rows={"group_id":'+this.$route.query.id+'}','responseMessage','group');// 当前规则集数据
						if(this.$route.query.associated_count > 0){// 存在关联数据，获取
	            			this.sendRequest(this,'/ruleset/rulesetGoodsCatagory?select_rows={"associated":'+this.$route.query.id+'}','responseMessage','associated');// 关联数据
	            		}
					}
            	}
            	this.$global.cacheData.values = {};
            	this.$global.cacheData.status = false;
            },
            parsingMessage(val){// 通过不同状态，返回对应值
            	if(this.pageData.modify){// 修改状态
            		return this.pageData.varData[val];
            	}else{// 未修改状态
            		if(val == 'status'){
            			if(this.$route.query.type == 'product' && this.pageData.constData[val] == '0')return '未上架';
            			if(this.$route.query.type == 'product' && this.pageData.constData[val] == '1')return '上架中';
            			if(this.$route.query.type == 'group' && this.pageData.constData[val] == '0')return '未执行';
            			if(this.$route.query.type == 'group' && this.pageData.constData[val] == '1')return '执行中';
            			if(this.$route.query.type == 'group' && this.pageData.constData[val] == '2')return '并行测试';
            			if(this.pageData.constData[val] == undefined)return '未使用';
            		}else{
            			return this.pageData.constData[val];
            		}
            	}
            },
            reverseString(val){// 根据不同状态，返回相应字符串
            	if(this.$route.query.type == 'product'){
            		if(val == 'desc')return "产品说明";
            		if(val == 'include')return "当前产品包含规则集";
            		if(val == 'add')return "+添加规则集";
            		if(val == 'add-link')return "ruleSetConfig";
            	}else{
            		if(val == 'desc')return "规则集说明";
            		if(val == 'include')return "当前规则集包含规则";
            		if(val == 'add')return "+添加规则";
            		if(val == 'add-link')return "ruleConfig";
            	}
            },
            responseMessageFormat(newVal,oldVal){// JAXA 请求响应格式化
            	if (newVal.code == '1' ) {
            		// name 存在，获取的数据。不存在获取的是成功与否的状态
               		if(newVal.name && newVal.name != 'associated'){
           				// 当前(产品|规则集)的基础信息
               			this.pageData.constData.message[this.$route.query.type].map((item) => {
           					item.val = newVal.message[0][item.key];
           				})
               			this.pageData.constData['id'] = newVal.message[0].id; 
       					this.pageData.constData['name'] = newVal.message[0][this.$route.query.type+'_name']; 
       					this.pageData.constData['desc'] = newVal.message[0].desc;
       					this.pageData.constData['status'] = newVal.message[0].status; 
       					// 同步 常量数据 与 修改数据
						this.syncData();     
       					this.action = true;
              		}else if(newVal.name && newVal.name == 'associated'){
              			// 当前(产品|规则集)的关联数据
              			this.pageData.constData.values = newVal.message;
              		}else{
              			if(String(newVal.message).indexOf('产品删除成功') > -1){
			       			this.$router.push('/riskStrategy/products');
			       		}else if(String(newVal.message).indexOf('规则集删除成功') > -1){
			       			this.$router.push('/riskStrategy/ruleSetConfig');
			       		}else{// 已修改的数据覆盖原始数据
			       			alert(newVal.message);
			       			this.pageData.modify = false;
	                   		this.pageData.constData = JSON.parse(JSON.stringify(this.pageData.varData));
	                   	}
              		}
               	}else {
               		alert('404...');
               	}
            },
            isedited(){// 修改当前产品|规则集
	    		this.pageData.modify = !this.pageData.modify;
                this.syncData();
	    	},
	    	isdelete(){// 删除当前产品|规则集
	    		let str = this.$route.query.type == 'product'? '产品':'规则集';
	    		if(confirm('确定要删除当前'+str+'?')){
	    			if(this.$route.query.type =='product'){
                        this.sendRequest(this,'/product/productGoodsCatagory?delete_rows={"product_id":'+ this.pageData.constData.id+'}','responseMessage');
                    }else{
                        this.sendRequest(this,'/ruleset/rulesetGoodsCatagory?delete_rows={"group_id":'+ this.pageData.constData.id+'}','responseMessage');
            		}
                }
	    	},
	    	groupAction(msg){// 关联规则集操作
	    		if(msg.name == 'toggle'){// 切换正在执行的规则集
	    			if(confirm("确定要切换“执行中”的规则集？")){
		    			this.pageData.varData.values.map((item) => {
		    				if(item.id == msg.id){
		    					item.status = 1;
		    				}else{
		    					if(item.status == 1){
		    						item.status = 0;
		    					}
		    				}
		    			})
		    		}
	    			return;
	    		}
	    		if(msg.name == 'delete'){// 删除关联规则集
	    			if(confirm("确定要删除当前规则集？")){
	    				this.$global.$splice(this.pageData.varData.values,msg.id);
		    		}
	    			return;
	    		}
	    	},
	    	deleteRule(msg){// 删除关联规则
	    		this.$global.$splice(this.pageData.varData.values,msg.id);
	    	},
	    	addingAssociated(){// 添加关联前格式化
	    		this.$global.cacheData.status = 'back';
	    		this.$global.cacheData.values.pageData = JSON.parse(JSON.stringify(this.pageData));
	    	},
	    	commitChanges(){// 提交修改
	    		let status = this.pageData.varData.status;
	    		if(this.pageData.varData.desc.length < 1)return alert("描述不能为空！");
	    		if(!this.submitDataCheck(status))return;
	    		// 校验通过
	    		let upData = {};
	    		upData['modify_author'] = "张**";
	    		upData['desc'] 			= this.pageData.varData.desc;
	    		upData['associatedData']= [];
	    		// 关联数据状态
	    		this.pageData.varData.values.map((item) => {
	    			upData.associatedData.push({
	    				'id':item.id,
	    				'name':(this.$route.query.type == 'product' ? item.group_name : item.rule_name),
	    				'status':item.status||0
	    			});
	    		});
	    		if(this.$route.query.type == 'product'){
	    			upData['product_id'] 	= this.pageData.varData.id;
		    		upData['product_name']  = this.pageData.varData.name;
	    			upData['ruleset_count'] = this.pageData.varData.values.length;
	    			upData['status'] 		= this.pageData.varData.status;
		    		this.sendRequest(this,'/product/productGoodsCatagory?update_rows='+JSON.stringify(upData),'responseMessage');
	    		}else{
	    			upData['group_id']	 	= this.pageData.varData.id;
		    		upData['group_name'] 	= this.pageData.varData.name;
	    			upData['rule_count']	= this.pageData.varData.values.length;
		    		this.sendRequest(this,'/ruleset/rulesetGoodsCatagory?update_rows='+JSON.stringify(upData),'responseMessage');
	    		}
	    	},
	    	submitDataCheck(status){
	    		let rule_count = 0, riskControlType = false, nuclearFrontalType = false;
	    		// 获取符合条件的数据量
	    		this.pageData.varData.values.map((item) => {
					if(item.status == 1){
						rule_count ++;
						if(item.type == '风控'){
							riskControlType = true;
						}
						if(item.type == '核额'){
							nuclearFrontalType = true;
						}
					}
				});
    			if(this.$route.query.type == 'product' && status == 1){
					if(this.pageData.varData.values.length < 2 || !riskControlType || !nuclearFrontalType){
						alert("上架中的产品必须有两条“执行中”的规则集，（风控|核额）类型规则集 各一条")
						return false;
					}
    			}
    			if(this.$route.query.type == 'group' && (status == 1 || status == 2)){
					if(this.pageData.varData.values.length < 1 || rule_count < 1){
						alert("（执行中|并行测试）的规则集，至少要包含一条“启用”的规则")
						return false;
					}
    			}
    			return true;
	    	},
            syncData(){// 同步 常量数据 与 修改数据
				this.pageData.varData = JSON.parse(JSON.stringify(this.pageData.constData));  
            }
        }
    }
</script>

<style lang="less" type="text/less">
	@atrovirens:#009688;
	@aurantius:#FFC107;
	
    .details-wrap {
    	position: absolute;
    	display: flex;
        flex-direction: column;
	    z-index: 10;
	    top: 0;
	    left: 0;
        width: 100%;
        height: 100%;
        background-color: #f3f4f6;
        .details-wrap-moudel {
            padding: 15px;
            border-radius: 5px;
            background-color: #fff;
            &.details-head {
                position: relative;
                height: 160px;
                min-height: 160px;
                overflow: hidden;
                .details-head-toolbar {
                    position: absolute;
                    display: block;
                    z-index: 10;
                    top: 0px;
                    right: 0px;
                    width: 200px;
                    height: 49px;
                    font-size: 0;
                    .details-head-toolbar-item {
                        display: inline-block;
                        float: right;
                        width: 33.3%;
                        height: 100%;
                        line-height: 49px;
                        font-size: 12px;
                        text-align: center;
                        cursor: pointer;
                        transition: background-color .4s;
                        overflow: hidden;
                        &:hover {
                            color: #fff;
                            font-size: 15px;
                            background-color: @aurantius;
                        }
                        &.modifyStyle {
                            color: #fff;
                            background-color: @aurantius;
                        }
                        &.details-head-toolbar-copy{
                        	.icon{
                        		width: 1.5em;
	                            height: 1.5em;
	                            vertical-align: middle;
                        	}
                        }
                        &.details-head-toolbar-delete{
                        	.icon{
                        		width: 1.6em;
	                            height: 1.6em;
	                            vertical-align: middle;
                        	}
                        }
                        &.details-head-toolbar-Modify{
                        	.icon{
                        		width: 1.7em;
	                            height: 1.7em;
	                            vertical-align: middle;
                        	}
                        }
                    }
                }
                .details-head-content {
                    position: relative;
                    .details-head-content-items {
                        width: 100%;
                        height: 100%;
                        padding: 25px 10px 10px;
                        font-size: 0px; /*解决 inline-block*/
                        overflow: hidden;
                        .details-head-content-item {
                            display: inline-block;
                            width: 25%;
                            height: 100%;
                            font-size: 13px;
                            border-right: 1px solid #f2f2f2;
                            overflow: hidden;
                            &.ruleset{
                            	width: 33%;
                            }
                            .details-head-content-item-top,
                            .details-head-content-item-bottom {
                                width: 100%;
                                padding: 7px;
                                text-align: center;
                            }
                            .details-head-content-item-bottom {
                                color: green;
                                font-size: 20px;
                            }
                            &:last-child {
                                border: none;
                            }
                        }
                    }
                }
            }
            &.details-explain {
                display: flex;
                flex-direction: column;
                height: 120px;
                min-height: 120px;
                margin: 12px 0;
                .details-explain-text {
                    padding-left: 20px;
                    font-size: 12px;
                    color: #666;
                    overflow: auto;
                }
                .change-description {
                    padding: 5px 10px;
                    resize: none; /* 禁止拖动 */
                    border: none;
                    border-radius: 5px;
                    box-shadow: 0 0 5px 0px #2196F3;
                }
            }
            &.details-main {
                flex-grow: 1;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                .details-main-tabBox {
                    padding-left: 20px;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    .ruleSet-card {
                        float: left;
                    }
                    table {
                        width: 100%;
                        border-color: #f2f2f2;
                        text-align: center;
                        tr{
                        	&.noDrop{
                        		background-color: #fafafa;
                        		th, td {
	                            	color: #9e9e9e;
	                        	}
                        	}
                        	th, td {
	                            padding: 5px 10px;
	                            font-size: 12px;
	                            color: #333;
	                        }
	                        th {
	                        	color: #fff;
	                            font-size: 14px;
	                            background-color: #607d8b;
	                        }
	                        .delete {
                        		padding: 0px 10px;
	                        	font-size: 14px;
	                            color: red;
	                            cursor: pointer;
	                            &:hover{
	                            	background-color: #F2F2F2;
	                            	font-size: 18px;
	                            }
	                        }
	                        .switchBox{
	                        	position: absolute;
	                        	display: inline-block;
	                        	top: 2px;
	                        	left: 0;
	                        	bottom: 0;
	                        	right: 0;
	                        	margin: auto;
	                        	width: 35px;
	                        	height: 18px;
	                        	.swith-btn {
		                            margin: 0 auto;
		                        }
	                        }
                        }
                    }
                }
            }
            &.details-foot {
                margin-top: 10px;
                height: 50px;
                min-height: 50px;
                color: #fff;
                background-color:@atrovirens;
                opacity:1; 
                &:hover {
                   opacity:.9;
                }
                .details-save-changes {
                    width: 100%;
                    height: 100%;
                    text-align: center;
                    letter-spacing: 2px;
                    cursor: pointer;
                }
            }
            .details-wrap-bar {
                position: relative;
                height: 35px;
                min-height: 35px;
                line-height: 19px;
                font-weight: 600;
                color: #333;
                font-size: 14px;
                text-align: left;
                &.details-head-title {
                    font-size: 15px;
                    text-align: center;
                    border-bottom: 1px solid #f2f2f2;
                    .titleEdit{
                        font-size:16px;
                        padding: 3px 5px 4px;
                        color: #666;
                        text-align: center;
                        font-size: 14px;
                        border-radius: 3px;
                        border: 1px solid #03a9f4;
                        box-shadow: 0px 0px 5px 0px #a9e4ff;
                    }
                    .detail-title-status {
                        font-size: 12px;
                        vertical-align: sub;
                        &.excute {
                            color: #4caf50;
                            cursor: no-drop;
                        }
                        &.test {
                            color: #ff9800;
                            cursor: no-drop;
                        }
                        &.not-excute {
                            color: #c1c0c0;
                            cursor: pointer;
                        }
                    }
                    .executing-state-select {
                        vertical-align: text-top;
                        border-radius: 5px;
                        border-color: #e4dada;
                        color: #9E9E9E;
                        padding-bottom: 3px;
                        cursor: pointer;
                        outline: none;
                        option {
                            border: none;
                            outline: none;
                        }
                    }
                }
                &.details-main-title {
                    .details-main-title-added {
                        position: absolute;
                        display: block;
                        top: -5px;
                        right: 0;
                        width: 120px;
                        height: 30px;
                        line-height: 30px;
                        text-align: center;
                        color: #fff;
                        background-color: @atrovirens;
                        border-radius: 5px;
                        transition: background-color .2s;
                        opacity:1; 
                        &:hover {
                        	opacity: .8;
                        }
                    }
                }
            }
        }
        .alertBox {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 999;
            width:100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
    }
</style>