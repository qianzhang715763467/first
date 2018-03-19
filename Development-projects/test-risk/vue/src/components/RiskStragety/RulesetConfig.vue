<template>
    <div id="ruleset" v-if="action">
        <div class="addRuleSetBtnBox">
        	<back v-if="$route.query.type" :params="{action:$route.query.type}"></back>
        	<add-button  v-if="!$route.query.type" :params="{val:'新增规则集+',name:'group-add',type:'group',action:'create'}"></add-button>
        </div>
        <div class="ruleSetsBox">
        	<section class="ruleSet-cardSet">
    			<div class="ruleSet-card" v-for="(row,index) in pageData.values" v-show="(!$route.query.type?true:row.status == undefined)">
    				<!-- 规则集卡片 -->
        			<ruleset-card v-show="!row.added" :params="{name:'group',values:row,addAssociated:$route.query.action}" @action="addingAssociated"></ruleset-card>
    			</div>	
        	</section>
        </div>
        <!-- 详情 -->
    	<router-view></router-view>
    </div>
</template>

<script type="text/ecmascript-6">
	export default{
        name: 'ruleset',
        data(){
            return{
            	action: false,
            	responseMessage:false, // 响应状态
            	pageData:{ // 当前页面所有数据
//          		state: false,// 当前页面行为状态（默认|详情|新增）
            		values: []	// 所有规则集数据
            	},
            }
        },
        mounted(){
        	this.ready();
        },
        watch:{
        	responseMessage(newVal,oldVal){
        		if(newVal.code == '1'){
        			this.pageData.values = newVal.message;
        			this.addabilityGroupCount = this.pageData.values.length;
        			// 其他页面进来添加 关联规则集？ 过滤掉已经被添加的规则集
        			if(newVal.name && newVal.name == 'unusedGroup'){// 页面刷新时，缓存不存在
    					this.$global.cacheData.values.pageData.varData.values.map((item,i) => {
        					this.$global.$splice(this.pageData.values,item.id);
        				})
        			}
                    this.action = true;
				}
          	},
          	$route(newVal,oldVal){
				if(newVal.fullPath != oldVal.fullPath){
					this.ready();
				}
			}
        },
        methods:{
        	ready(){
                // 'addAssociation'说明是由其他页面跳转进来添加关联规则集的
                if(this.$route.query.action == 'addAssociation'){
                	if(this.$global.cacheData.values.pageData){
                		this.sendRequest(this,'/ruleset/rulesetGoodsCatagory?select_rows={"unused":true}','responseMessage','unusedGroup'); // 未使用过的规则集数据
                	}else{
                		this.$router.push('/riskStrategy/ruleSetConfig');
                		this.ready();
                	}
                }else{
                	this.sendRequest(this,'/ruleset/rulesetGoodsCatagory?select_rows={}','responseMessage'); // 全部规则集数据
                }
            },
        	addingAssociated(msg){ // "增加"|"取消增加"规则集，点击之后的返回整条规则集参数。
        		if(msg.action == "add"){// 将"当前规则集参数"暂存
        			this.$global.cacheData.values.pageData.varData.values.push(JSON.parse(JSON.stringify(msg.val)));
                }else{// 从暂存数据中删除"当前规则集参数"
                	this.$global.$splice(
                		this.$global.cacheData.values.pageData.varData.values,
                		msg.val.id
                	);
        		}
	        }
        }
    }
</script>

<style lang="less" type="text/less" scoped>
    #ruleset{
        position:relative;
        width:100%;
        height:100%;
        border-radius:5px;
        background:#fff;
		.noRulesets{
			padding-top:30%;
			width:100%;
        	height:30px;
        	line-height:30px;
			font-size:18px;
			text-align:center;
			a{
				color:#00bcd4;
				font-weight:bold;
			}
		}
        .addRuleSetBtnBox{
        	position: absolute;
        	z-index: 2;
        	top: 0;
        	left: 0;
        	width:100%;
            background-color: white;
			padding:25px ;
			padding-bottom: 15px;
            .addRulesBtn{
            	width:100px;
            	height: 30px;
            	line-height: 30px;
                text-align:center;
                border-radius:3px;
                background:#eeeff0;
                cursor:pointer;
				&:hover{
					color: #00bcd4;
					box-shadow: 0 0 8px 0px #bdbaba;
				}
            }
            .addEntry-ruleset{
            	position: absolute;
            	top: 9px;
            	right: 10px;
            	width: 80px;
            	height: 30px;
            	line-height: 30px;
            	text-align: center;
            	color: #fff;
            	border-radius: 5px;
				background-color: #009688;
            }
        }
        .ruleSetsBox{
            width:100%;
        	height:100%;
            overflow: hidden;
            .ruleSet-cardSet{
        		padding: 70px 20px 20px;
            	width: calc(~"100% + 17px");
            	height:100%;
            	overflow-y: auto;
            	.ruleSet-card{
            		position: relative;
            		display: inline-block;
            	}
            }
        }
    }
</style>