<template>
    <div id="add" v-if="action">
        <!--================================form=====================================-->
        <div class="formInfo">
            <back :params="{'type':$route.query.type,'action':$route.query.action}"></back>
            <div class="reset icon-hover" style="right:10px;" @click="reset()">
                <svg-icon class="icons" id="icon-zhongzhi1"></svg-icon>
            </div>
            <h3>{{$route.query.type == 'product'? '新增产品' : '新增规则集'}}</h3>
            <div class="formBox">
                <div class="name">
                    <label>{{$route.query.type == 'product'? '产品名称：' : '规则集名称：'}}</label>
                    <input type="text" value="" v-model="pageData.varData.name" :placeholder="$route.query.type == 'product' ?'请输入产品名称' : '请输入规则集名称'"/>
                </div>
                <div class="desc">
                    <label>{{$route.query.type == 'product'? '产品描述：' : '规则集描述：'}}</label>
                    <textarea rows="3" cols="100%" v-model="pageData.varData.desc" :placeholder="$route.query.type == 'product' ?'请输入产品描述' : '请输入规则集描述'"></textarea>
                </div>
            </div>
        </div>
        <!--================================规则集／规则=====================================-->
        <div class="detailsBox">
            <div class="details">
                <router-link tag="div" class="add icon-hover" style="right:10px;"  @click.native="addingAssociated"
                	:to="{name:($route.query.type == 'product'? 'ruleSetConfig' : 'ruleConfig'), query:{type:'create',action:'addAssociation',id:-1}}">
                    <svg-icon class="icons" id="icon-add"></svg-icon>
                </router-link>
                <h4>{{$route.query.type == 'product'? '当前产品所包含规则集列表' : '当前规则集所包含规则列表'}}</h4>
                <div class="tableBox" ref="tableBox">
                    <!-----------------------------card------------------------------>
                    <template v-if="$route.query.type == 'product'">
                    	<div class="ruleSet-card" v-for="(row,index) in pageData.varData.values">
	                    	<ruleset-card :params="{name:'group',values:row}"></ruleset-card>
	                        <div class="card-toolber">
	                            <p class="card-tool card-tool-remove" @click="deleteData(row.id)">
	                                <svg-icon id="icon-shanchu"></svg-icon>
	                            </p>
	                        </div>
	                    </div>
                    </template>
                    <!-----------------------------table------------------------------>
                    <template v-else>
                    	<table v-if="pageData.varData.values.length > 0" class="ruleTable" border="1" cellspacing="0" cellpadding="0">
	                        <tr>
	                            <th>设置</th>
	                            <th v-for="item in tableHeader">{{item}}</th>
	                        </tr>
	                        <tr v-for="(row, index) in pageData.varData.values" >
	                            <td class="delete" :style="{color:'red'}" @click="deleteData(row.id)">删除</td>
	                            <td v-for="(item,key) in tableHeader">{{ row[key] }}</td>
	                        </tr>
	                    </table>
                    </template>
                </div>
            </div>
        </div>
        <div class="save">
            <input type="button" value="提交" @click="saveData"/>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import AlertBox from './../common/Alert.vue'
    export default {
        name: 'add',
        data() {
            return {
            	pageData:{
            		action: 'create', // 记录如何进入当前页面的
					varData: {
						name: '',
						desc: '',
						values: []	// 所有相关数据
					}
            	}, // 新增页面的所有数据
            	action 		: false, // view启动指令
            	response_status:false,
                tableHeader	: {
                    "rule_id": "ID",
                    "rule_name": "规则编号",
                    "desc": "规则内容",
                    "approve_action": "执行动作",
                    "threshold": "参数"
                },
                giveData:{}
            }
        },
        components:{
            AlertBox
        },
        watch:{
        	response_status(curVal,oldVal){
        		if (curVal.code == '1') {
                   	this.giveData.showAlert = true;
                   	this.giveData.showCancel = false;
                   	this.giveData.alertTxt = '创建成功';
                   	this.reset();
                   	if(curVal.name){
                   		if(curVal.name == 'createRulest'){
                   			this.$router.push("/riskStrategy/ruleSetConfig");
                   		}else{
                   			this.$router.push("/riskStrategy/products");
                   		}
                   	}
                   	this.alertParams.showAlert = false;
                   	this.alertParams.alertTxt = false;
               } else {
                   this.giveData.alertTxt = '哎呀！ 创建失败了....';
               }
        	}
        },
        mounted() {
        	this.ready();
            this.$route.query.type = this.$route.query.type;
        },
        methods: {
        	ready(){
        		// 添加完规则集或规则之后再次回到当前页面
        		this.pageData.constData = JSON.parse(JSON.stringify(this.pageData.varData));
        		if(this.$global.cacheData.status == 'back'){ 
        			this.pageData = JSON.parse(JSON.stringify(this.$global.cacheData.values.pageData));
        			this.$global.cacheData.status = false;
        		}else if(this.$route.query.action == 'copy'){// 详情页copy过来的？ 目前只复制 "名字"&"描述"
        			this.pageData.varData.name = "复制  "+this.$route.params.name;
        			this.pageData.varData.desc = this.$route.params.desc;
        		}
        		this.action = true;
        	},
        	reset(){
        		this.pageData.action= 'create';
        		this.pageData.varData = JSON.parse(JSON.stringify(this.pageData.constData));
				this.$global.cacheData.values = [];
        	},
        	deleteData(id){
        		this.$global.$splice(this.pageData.varData.values,id);
        	},
        	addingAssociated(){// 添加关联前格式化
        		this.$global.cacheData.status = 'back';
	    		this.$global.cacheData.values.pageData = JSON.parse(JSON.stringify(this.pageData));
	    	},
        	saveData() { // 提交 创建
                var saveName = (this.$route.query.type == 'product' ? '产品' : '规则集');
        
        	}
    	}
    }
</script>

<style lang="less" type="text/less" scoped>
    #add {
    	position: absolute;
    	z-index: 10;
    	top: 0;
    	left: 0;
        width:100%;
        height:100%;
        background-color: #f3f4f6;
        .formInfo {
            position: relative;
            padding:10px 15px;
            width:100%;
            height:180px;
            border-radius: 5px;
            background: #fff;
            h3 {
                width:100%;
            	height:40px;
				line-height:30px;
                text-align: center;
                border-bottom: 1px solid #eee;
            }
            .formBox {
            	margin: 0 auto;
                padding-top: 10px;
            	width:60%;
            	height:calc(~"100% - 40px");
                .name,
                .desc {
                	padding: 5px 10px;
                    width:100%;
                    overflow: hidden;
                    label {
                    	display: flex;
                    	float: left;
                        justify-content: left;
                        align-items: center;
                    	width:30%;
            			height:100%;
                    }
                    input, 
                    textarea {
                    	padding:5px;
                    	width:70%;
            			height:100%;
                        float: left;
                        outline: none;
                        border: 1px solid #eee;
                    }
                }
                .name {
                	height:40%;
                }
                .desc {
                	height: 60%;
                }
            }
        }
        .detailsBox {
        	width: 100%;
            height:calc(~"100% - 240px");
            padding-top: 15px;
            .details {
                position: relative;
                padding:0 15px 15px ;
                width: 100%;
        		height: 100%;
                border-radius: 5px;
                background: #fff;
                .add {
                    .icon{
                        padding:6px;
                    }
                }
                h4 {
                	width: 100%;
        			height: 40px;
        			line-height: 40px;
                }
                .tableBox {
                	width: 100%;
        			height: calc(~"100% - 40px");
                    overflow: auto;
                    .ruleTable {
                    	width: 100%;
                        border-color: #f2f2f2;
                        text-align: center;
                        .ruleTable tr {
                        	width: 100%;
                            height: 40px !important;
                        }
                        th, 
                        td {
                            padding: 5px 10px;
                            font-size: 12px;
                            color: #333;
                        }
                        th {
                            color: #000;
                            font-size: 13px;
                        }
                        .delete {
                            cursor: pointer;
                        }
                    }
                    .ruleSet-card {
                        display: inline-block;
                    }
                    .card-toolber {
                        position: relative;
                        top: -15px;
                        padding: 0 14px;
                        width: 100%;
                        height: 25px;
                        .card-tool {
                            float: left;
                            width: 100%;
                            height: 100%;
                            line-height: 25px;
                            text-align: center;
                            font-size: 13px;
                            background-color: #fff;
                            cursor: pointer;
                            &.card-tool-remove {
                                color: #ccc;
                                border-bottom-left-radius: 5px;
                                border-bottom-right-radius: 5px;
                                box-shadow: 0px 3px 10px 3px #ddd;
                                -moz-box-shadow: 0px 3px 10px 3px #ddd;
                                -webkit-box-shadow: 0px 3px 10px 3px #ddd;
                            }
                            &.card-tool-remove:hover {
                                color: red;
                            }
                            .icon {
                                width: 1.3em;
                                height: 1.3em;
                                vertical-align: sub;
                            }
                        }
                    }
                }
                .ruleSet-card {
                    display: inline-block;
                }
            }
        }
        .save { 
        	padding-top: 15px;
        	width: 100%;
        	height: 60px;
            input {
            	width: 100%;
        		height: 100%;
        		font-size: 16px;
                color: #000;
                outline: none;
                cursor: pointer;
                border-radius: 5px;
                text-align: center;
                border: 1px solid #eee;
                background: #fff;
                &:hover {
                    color: #fff;
                    background: #2ab2ca;
                }
            }
        }
        .alertBox{
            position:absolute;
            top:0;
            left:0;
            z-index:999;
            width: 100%;
        	height: 100%;
            background:rgba(0,0,0,0.5);
        }
    }
</style>