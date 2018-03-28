<template>
	<div id="rejection-analysis" class="letter-module">
		<h4 class="title">业主贷拒绝原因分析</h4>
		<section class="chart-content">
			<div class="chart-box" v-for="row in pageData.values" :id="row.id">
				{{charts(row)}};
			</div>
		</section>
	</div>
</template>

<script type="text/ecmascript-6">
	import echarts from 'echarts'
	
	export default{
		name:'rejection-analysis',
		data(){
			return{
				pageData:{
					values:[
						{
							'title':'拒绝原因分布',
							'id':'a',
							'data':[
								{value:335, name:'超时未提价资料'},
				                {value:310, name:'超时未面签'},
				                {value:234, name:'系统拒绝'},
				                {value:135, name:'拒绝审批'},
				                {value:1548, name:'面签拒绝'}
							]
						},
						{
							'title':'系统拒绝原因分布',
							'id':'b',
							'data':[
								{value:335, name:'超时未提价资料'},
				                {value:310, name:'超时未面签'},
				                {value:234, name:'系统拒绝'},
				                {value:135, name:'拒绝审批'},
				                {value:1548, name:'面签拒绝'}
							]
						},
						{
							'title':'审批拒绝原因分布',
							'id':'c',
							'data':[
								{value:335, name:'超时未提价资料'},
				                {value:310, name:'超时未面签'},
				                {value:234, name:'系统拒绝'},
				                {value:135, name:'拒绝审批'},
				                {value:1548, name:'面签拒绝'}
							]
						},
						{
							'title':'面签拒绝原因分布',
							'id':'d',
							'data':[
								{value:335, name:'超时未提价资料'},
				                {value:310, name:'超时未面签'},
				                {value:234, name:'系统拒绝'},
				                {value:135, name:'拒绝审批'},
				                {value:1548, name:'面签拒绝'}
							]
						}
					]
				}
			}
		},
		methods:{
			charts(msg){
				let arr = [];
				msg.data.map((item) => {
					arr.push(item.name);	
				})
				let option = {
					backgroundColor:'#fff',
				    title : {
				    	top:'5%',
				        text: msg.title,
				        x:'center'
				    },
				    tooltip : {
				        trigger: 'item',
				        formatter: "{b} : {c} (件)"
				    },
				    legend: {
				        orient: 'vertical',
				        top:'10%',
				        left: 'left',
				        data: arr
				    },
				    series : [
				        {
				            type: 'pie',
				            radius : '55%',
				            center: ['50%', '60%'],
				            data:msg.data,
				            label: {
				                normal: {
				                    position: 'inner',
				                    formatter: '{d}%',
				                     offset: [,100],
				                     textStyle: {
				                        color: '#fff',
				                        fontSize: 14
				                    }
				                }
				            },
				            itemStyle: {
				                emphasis: {
				                    shadowBlur: 10,
				                    shadowOffsetX: 0,
				                    shadowColor: 'rgba(0, 0, 0, 0.5)'
				                }
				            }
				        }
				    ]
				}
				setTimeout(function(){
					echarts.init(document.querySelector('#'+msg.id)).setOption(option);
				},100);
			}
		}
	}
</script>

<style lang="less" type="text/less">
	#rejection-analysis{
		width: 100%;
		height: 100%;
		.chart-content{
			width: 100%;
			height: calc(~'100% - 65px');
			font-size: 0;
			background-color: #f2f2f2;
			.chart-box{
				display: inline-block;
				width: calc(~"50% - 2px");
				height: calc(~"50% - 2px");
				background-color:#fff;
				border-radius:5px;
				&:nth-of-type(odd){
					margin-right: 4px;
				}
				&:nth-of-type(3n+0){
					margin-top: 4px;
				}
			}
		}
	}
</style>