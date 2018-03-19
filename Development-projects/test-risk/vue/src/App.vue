<template>
	<div id="app">
		<header-com class="header" :pathText="path_text"></header-com>
		<div class="content">
			<ul class="leftNav" @click="getChildren($event)">
				<li v-for="(k,index) in fatherNav" :class="k.name=='riskStrategy'?'riskStrategyBtn':''">
					<router-link tag="div" @click.native="curIndex = index" :to="{name:k.name}" :class="{'multi-level-nav':k.childrenNav}">
						<img :src="index==curIndex?k.activeSrc:k.src" />{{k.val}}
					</router-link>
					<ul class="RiskStrategyLeft" v-if="k.childrenNav">
						<router-link v-for="i in k.childrenNav" tag="li" class="multi-level-nav" :class="['pointer','svg-'+i.id]" :to="{name:''+i.name}">
							<svg-icon class="icons" :id="i.id"></svg-icon>{{i.val}}
						</router-link>
					</ul>
				</li>
			</ul>
			<div class="rightView">
				<router-view></router-view>
			</div>
		</div>
	</div>
</template>

<script>
	import $ from 'jquery'
	import Header from './components/Header/header.vue';
	export default {
		name: 'app',
		data() {
			return {
				curIndex: -1,
				path_text:'',//  路由切换时相应的路径文本
				fatherNav: [{
						name: 'riskIndex',
						val: '风险大盘',
						src: require("./static/navIcons/riskIndex-not@2x.png"),
						activeSrc: require("./static/navIcons/riskIndex@2x.png")
					},
					{
						name: 'check',
						val: '贷前审核管理',
						src: require("./static/navIcons/check-not@2x.png"),
						activeSrc: require("./static/navIcons/check@2x.png")
					},
					{
						name: 'riskStrategy',
						val: '风控策略引擎',
						src: require("./static/navIcons/riskStrategy-not@2x.png"),
						activeSrc: require("./static/navIcons/riskStrategy@2x.png"),
						'childrenNav': [
							{
								name: 'products',
								val: "产品",
								id: 'icon-chanpin'
							},
							{
								name: 'ruleSetConfig',
								val: "规则集",
								id: 'icon-guize3'
							},
							{
								name: 'ruleConfig',
								val: "规则",
								id: 'icon-guize2'
							},
							{
								name: 'nameList',
								val: "黑白名单管理",
								id: 'icon-heibaimingdan'
							}
						]
					},
					{
						name: 'monitor',
						val: '数据监控管理',
						src: require("./static/navIcons/dataMonitor-not@2x.png"),
						activeSrc: require("./static/navIcons/dataMonitor@2x.png")
					}
				],
				headerTitle: {
					'riskIndex': '风险大盘',
					'check': '贷前审核管理',
					'riskStrategy': '风控策略引擎',
					'monitor': '数据监控管理',
					'products': '风控策略引擎 > 产品',
					'product-add': '风控策略引擎 > 产品 > 新增',
					'product-details': '风控策略引擎 > 产品 > 详情',
					'ruleSetConfig': '风控策略引擎 > 规则集',
					'group-add': '风控策略引擎 > 规则集 > 新增',
					'group-details': '风控策略引擎 > 规则集 > 详情',
					'ruleConfig': '风控策略引擎 > 规则',
					'nameList': '风控策略引擎 > 黑白名单管理'
				}
			}
		},
		watch: {
			$route(newVal, oldval) { // 监听路由的目的： 当前规则页面由其他页面跳转进来，这时候再次点击导航"规则"数据没有初始化
				this.pathText(newVal);
			},
		},
		components: {
			'header-com': Header
		},
		methods: {
			pathText(route){// 路由切换时显示相应的路径文本
				if(route.name == 'create-rule'){
					this.path_text = (route.query.action == 'create' ? '风控策略引擎 > 规则 > 新建': '风控策略引擎 > 规则 > 修改');
				}else{
					this.path_text = this.headerTitle[route.name];
				}
			},
			getChildren(ev) {// 当前点击对象存在子导航||当前点击对象就是子导航
				// 每次点击导航时，缓存状态初始化
				this.$global.cacheData.status = false;
				if(ev.target.className.indexOf('multi-level-nav') > -1){
					$('.RiskStrategyLeft').show("slow");
				}else{
					$('.RiskStrategyLeft').hide("slow");
				}
			}
		},
		mounted() {
			this.pathText(this.$route);
		}
	}
</script>

<style lang="less" type="text/less">
	@import url("./less/common"); 
	html,body,#app{ 
		.w(100%); 
		.h(100%); 
		.header{ 
			.w(100%) ; 
			.h(46px); 
		} 
		.content{ 
			display:flex; .w(100%); 
			.h(calc(~"100% - 46px")); 
			.leftNav{ 
				.h(100%); 
				width:175px; 
				min-width:175px; 
				float:left; 
				padding-top:20px; 
				background:#363e40; 
				li{ 
					.w(100%); 
					min-height:40px; 
					.l-h(40px); 
					margin:20px 0; 
					text-align:left; 
					img{ 
						.w(20px); 
						margin-right:6px; 
					} 
					div{ 
						display:flex; 
						.w(100%); 
						.h(100%); 
						color:@white; 
						.f-s(16px); 
						padding:5px 15px; 
						align-items:center; 
						.icons{ 
							margin-right:10px; 
						} 
						&.router-link-active{ 
							background:rgba(0,0,0,0.4); 
						} 
					} 
					&.riskStrategyBtn{
						margin-bottom:0; 
						height:auto; 
						.RiskStrategyLeft{ 
							display:none; 
							.w(80%); 
							margin:0 auto; 
							z-index:1; 
							.router-link-active{ 
								color:#2ab2ca; 
							} 
							li{ 
								position: relative; 
								display:flex; 
								.w(100%); 
								.h(30px); 
								.f-s(14px); 
								color:#fff; 
								margin:10px 0; 
								padding:5px 15px; 
								padding-left: 35px; 
								border-radius:3px; 
								background:#363e40; 
								box-shadow:1px 1px 1px #000; 
								align-items:center; 
								&.router-link-exact-active.router-link-active{ 
									.icon{ 
										transform: scale(1.1); 
									} 
								} 
								&.svg-icon-chanpin, 
								.svg-icon-guize3{ 
									.icon{ 
										font-size:1rem; 
									} 
								} 
								&.svg-icon-guize2{ 
									.icon{ 
										left: 7px; 
										font-size:1.3rem; 
									} 
								} 
								&.svg-icon-heibaimingdan{ 
									.icon{ 
										font-size: .9rem; 
									} 
								} 
								.icon{ 
									position: absolute; 
									left: 10px; 
								} 
							} 
							li:last-child{ 
								margin-bottom: 0; 
							} 
						} 
					} 
				} 
			} 
			.rightView{ 
				.w(calc(~"100% - 175px")); 
				min-width:715px; 
				.h(100%); 
				padding:10px; 
				float:left; 
				background:#f3f4f6; 
				.bar{ 
					position: absolute; 
					z-index: 2; 
					top: 0; 
					left: 0; 
					width:100%; 
					padding:15px 30px; 
					line-height: 0px; 
					.toolbar-but{ 
						position: absolute; 
						display: block; 
						width: 30px; 
						height: 30px; 
						&:first-child{ 
							left:0; top: 0; 
						} 
						&:last-child{ 
							right: 10px; 
						} 
					} 
				} 
			} 
		} 
	}
</style>