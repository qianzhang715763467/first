<template>
	<!-- 产品卡片 -->
	<div class="card" id="card" v-if="ready()">
		<div class="card-tooltip">{{parsingName}}</div>
		<section class="card-box">
			<router-link  tag="a" class="card-main" :to="parsingLinkToData">
				<h3 class="card-title" @mouseover="tooltip($event,'over')" @mouseout="tooltip($event)">{{parsingName}}</h3>
				<div v-if="$route.name!='add'" class="card-choose" :class="[parsingClass]">
					<svg-icon id="icon-2xuanzhong"></svg-icon>
				</div>
				<ul class="card-items">
					<div v-for="(name,key) in parsingMessage">
						<li class="card-item"><i>{{name}}&nbsp; : &nbsp;</i>{{params.values[key]}}</li>
					</div>
					<li style="font-weight: 100;font-size:12px;">* 统计窗口30天 {{ params.values.rule_count}}</li>
				</ul>
			</router-link>
			<footer class="card-footer" v-show="params.state">
				<!-- “执行中状态”不可操作  -->
				<ul class="items" :class="{'noDrop':params.values.status == 1}">
					<li class="item delete" @click="deleteGroup"><svg-icon id="icon-shanchu"></svg-icon></li>
					<li class="item" v-if="params.values.rule_count > 0" :class="[!parsingButton1State?'notExcute':'test']" @click="params.values.status = parsingButton1State"><svg-icon id="icon-2xuanzhong"></svg-icon></li>
					<li class="item" v-if="params.values.rule_count > 0" :class="[parsingButton2State == 2?'test':'excute']"@click="toggleExecuteGroup"><svg-icon id="icon-2xuanzhong"></svg-icon></li>
				</ul>
            </footer>
		</section>
		<template v-if="params.addAssociated == 'addAssociation'">
			<div class="add-ruleset">
				<span class="add-text" v-show="isAddRuleset" @click="addingAssociated({'action':'add','val':params.values})">
					添加
				</span>
				<span class="add-text cancellationAddRuleset" v-show="!isAddRuleset" @click="addingAssociated({'action':'remove','val':params.values})">
					取消添加
				</span>
			</div>
		</template>
	</div>
</template>

<script>
	export default{
		name:"card",
		props:['params'],
	    data(){
	    	return{
	    		isAddRuleset	: true, 	// "增加" && "取消增加" 状态切换
                currentIndex	: -1, 		// 当前点击卡片的id
                correlation_id	: [],		// 关联id去重
	    		basicInformation: {
	    			"product":{
	    				"values":{
	    					"passed_qty"				: "进件数量",
		                	"According_unique_number"	: "拒绝数量",
		                	"number_manual_audits"		: "人工拒绝",
		                	"details_hit_rule"			: "通过率    "
	    				}
	    			},
	    			"group":{
	    				"values":{
	    					"passed_qty"				: "进件数量",
		                	"According_unique_number"	: "拒绝数量",
		                	"details_hit_rule"			: "通过率"
	    				}
	    			}
	    		}
	    	}
	    },
	    computed:{
	    	parsingName(){
	    		return this.params.values[this.params.name+'_name'];
	    	},
	    	parsingClass(){
	    		if(this.params.values.status && this.params.values.status == 1){
	    			return 'excute';
	    		}else if(this.params.values.status && this.params.values.status == 2){
	    			return 'test';
	    		}else{
	    			return 'not-excute';
	    		}
	    	},
	    	parsingMessage(){
	    		return this.basicInformation[this.params.name].values;
	    	},
	    	parsingButton1State(){// 卡片底部，左边状态按钮状态
    			return (!this.params.values.status?2:0);
	    	},
	    	parsingButton2State(){// 卡片底部，右边状态按钮状态
	    		return (this.params.values.status == 1?2:1);
	    	},
	    	parsingLinkToData(){// router-link to:参数
	    		return {
	    			// 仅查看 “产品详情页面包含规则集详情”时，不需要修改url路径！只改变传入参数就行。修改路径导致的闪屏
	    			name:this.params.viewOnly?'product-details':this.params.name+'-details',
	    			query:{
	    				'type':this.params.name,
			    		'action':'details',
			    		'id':this.params.values.id,
			    		'associated_count':(this.params.name == 'product'?this.params.values.ruleset_count : this.params.values.rule_count)||0,
	    				'viewOnly':this.params.viewOnly // 当前字段只有在"产品详情页面"->点击包含规则集时才会存在，目的是区分详情页面点击的"包含规则集"状态为"仅可见"
	    			}
	    		}
	    	}
	    },
	    methods:{
	    	ready() {
	    		if(this.params)return true;
	        },
	        tooltip(ev,action){
	        	if(ev.target.className == 'card-title'){
	        		ev.target.parentNode.parentNode.parentNode.childNodes[0].style.opacity = (action == 'over'? 1 : 0);
	        	}
	        },
	        toggleExecuteGroup(){
	        	if(this.params.values.status != 1){// 要切换执行规则集？返回当前规则集id
	        		this.$emit('action',{'name':'toggle','id':this.params.values.id});
	        	}else{
	        		this.params.values.status = 2;
	        	}
	        },
	        deleteGroup(){
        		this.$emit('action',{'name':'delete','id':this.params.values.id});
	        },
         	addingAssociated(msg){// 点击当前卡片"增加"|"取消增加"
	 			this.isAddRuleset = !this.isAddRuleset;		// 切换显示状态 
	 			this.$emit('action',msg);// 返回对应状态&id
	 		}
        }
	}
</script>

<style lang="less" type="text/less">
	.card{
		.card-tooltip{
        	position: absolute;
        	z-index:1;
        	padding: 5px ;
        	font-size: 13px;
        	margin-top: -11px;
        	margin-left:15px;
        	border-radius: 3px;
        	background-color: rgba(0,0,0,.6);
        	color: #fff;
        	opacity:0;
        	transition:opacity 1s;  
			&:after{
	        	position: absolute;
	        	top: 27px;
	        	left: 5px;
	        	content: "";
	        	border: 8px solid transparent;
	        	border-top-color: rgba(0,0,0,.6);
	        }
        }
    	.card-box{
	    	width: 170px;
	    	margin:13px;
	    	border-radius: 5px;
	    	background-color:transparent;
	    	box-shadow:2px 2px 10px 3px #ddd;
			-moz-box-shadow:2px 2px 10px 3px #ddd;
			-webkit-box-shadow:2px 2px 10px 3px #ddd;
	    	.card-main{
				position:relative;
				display: flex;
	    		flex-flow: column;
		    	padding:10px 10px 0;
		    	width:100%;
		    	height: 210px;
		    	border-radius: 5px;
		    	background-color: #fff;
				.card-choose,.delete{
					position:absolute;
					top:8px;
					right:8px;
					width: 20px;
					min-height:20px;
					border-radius:20px;
					&.notSelect{
						background:#fff;
						box-shadow:0 5px 5px #ddd;
						-moz-box-shadow:0 5px 5px #ddd;
						-webkit-box-shadow:0 5px 5px #ddd;
					}
					&.selected{
						box-shadow:0 5px 5px #ddd;
						-moz-box-shadow:0 5px 5px #ddd;
						-webkit-box-shadow:0 5px 5px #ddd;
					}
				}
				.delete{
					right:30px;
					.icons{
						color:red;
					}
				}
		    	.card-title{
		    		width: 100%;
		            min-height: 28px;
		            max-height: 28px;
					font-size:14px;
					font-weight:bold;
		            text-align:center;
		            color: #000;
		            overflow: hidden;
		            text-overflow: ellipsis;
		            white-space: nowrap;
		            border-bottom:1px solid #ddd;
					&.titleActive{
						color:#34b2c9;
					}
		        }
		        .card-items{
		        	display: block;
		        	overflow: hidden;
		        	padding: 5px 10px;
		        	width: 100%;
		        	height: 100%;
		        	border-bottom: 1px solid #F2F2F2;
		        	background-color: white;
		        	.card-item{
		        		margin:10px 0;
		        		font-size: 12px;
		        		color: #666;
						word-break: break-all;
						word-wrap:break-word;
						&:last-child{
							line-height: 17px;
						}
		        		i{
		        			font-weight: 600;
		        			color: #333;
		        		}
		        	}
		        }
			}
			.card-footer{
                position: relative;
                width: 100%;
                background-color: #fff;
                border-radius: 5px;
                .items{
                	width: 100%;
                	font-size:0;
                	&.noDrop {
                		visibility: hidden;
                    }
                	.item{
                		position: relative;
                		display: inline-block;
                		width: 33.333%;
                		padding-top: 10px;
                		padding-bottom: 10px;
                		color: #ccc;
                		text-align: center;
                		font-size: 12px;
                		transition:all .3s; 
                		&:hover{
                			background-color: #999;
                			cursor: pointer;
                		}
                		&:nth-of-type(2):after{
                			position: absolute;
                			content: "";
                			top: 10px;
                			left: 0;
                			width: 1px;
                			height: calc(~"100% - 20px");
                			background-color: #ccc;
                		}
                		&:nth-of-type(2):before{
                			position: absolute;
                			content: "";
                			top: 10px;
                			right: 0;
                			width: 1px;
                			height: calc(~"100% - 20px");
                			background-color: #ccc;
                		}
	                    &.delete{
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
            .excute {
                color: #4DB129 !important;
            }
            .test {
                color: #ffc107 !important;
            }
            .not-excute {
                color: #f2f2f2 !important;
            }
    	}
    	.add-ruleset{
			position: absolute;
			top:13px;
			left:13px;
			width: calc(~"100% - 26px");
			height: calc(~"100% - 26px");
			z-index: 10;
			background-color: rgba(0,0,0,.5);
			border-radius: 6px;
			.add-text{
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				margin: auto;
				width: 50px;
				height: 50px;
				line-height: 50px;
				text-align: center;
				font-size: 13px;
				color: #fff;
				border-radius: 50%;
				background-color: #3BA9A9;
				transform: scale(1);
				opacity: .7;
				transition:all .3s;
				cursor:pointer;
				&:hover{
					transform: scale(1.2);
					opacity: 1;
				}
				&.cancellationAddRuleset{
					font-size: 12px;
					line-height: 120%;
				    padding: 11px 8px 0;
					background-color: red;
				}
			}
		}
    }
</style>