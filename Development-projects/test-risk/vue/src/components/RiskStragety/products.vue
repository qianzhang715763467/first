<template>
    <div id="products" v-if="action">
        <div class="addRuleSetBtnBox">
        	<add-button :params="{val:'新增产品+',name:'product-add',type:'product',action:'create'}"></add-button>
        </div>
        <div class="ruleSetsBox">
        	<section class="ruleSet-cardSet">
        		<div class="ruleSet-card" v-for="(row,index) in pageData.product_data">
        			<!-- 产品卡片 -->
	        		<ruleset-card :params="{name:'product',values:row}"></ruleset-card>
	        	</div>
        	</section>
        	<!-- 详情 -->
        	<router-view></router-view>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default{
        name: 'products',
        data(){
            return{
            	action:false,
            	responseMessage:false,
            	pageData:{
            		product_data:[] // 产品数据
            	}
            }
        },
        watch:{
        	responseMessage(newVal,oldval){ 
				if(newVal.code == '1'){// 请求成功,计数器+1
					this.pageData.product_data = newVal.message;
                    this.action = true;
				}
			}
        },
        mounted(){
			this.ready();
        },
        methods:{
        	ready(){
        		this.sendRequest(this,'/product/productGoodsCatagory?select_rows={}','responseMessage'); 
        	}
        }
    }
</script>

<style lang="less" type="text/less">
	@lightGray:#eeeff0;
    #products{
        position:relative;
        width: 100%;
    	height: 100%;
    	border-radius:5px;
        background:#fff;
        overflow:hidden;
        .addRuleSetBtnBox{
        	position: absolute;
        	z-index: 2;
        	top: 0;
        	left: 0;
        	padding:25px ;
			padding-bottom: 15px;
        	width: 100%;
            background-color: white;
            .addRulesBtn{
            	width: 100px;
    			height: 30px;
				line-height: 30px;
                text-align:center;
                border-radius:3px;
                background:@lightGray;
                cursor:pointer;
				&:hover{
					color: #00bcd4;
					box-shadow: 0 0 8px 0px #bdbaba;
				}
            }
        }
        .ruleSetsBox{
            width: 100%;
    		height: 100%;
            overflow: hidden;
            .ruleSet-cardSet{
        		padding: 70px 20px 20px;
            	width: calc(~"100% + 17px");
            	height:100%;
            	overflow-y: auto;
            	.ruleSet-card{
            		display: inline-block;
            	}
            }
        }
    }
</style>