<template>
	<div id="daily-entry" class="letter-module">
		<h4 class="title">业主贷每日进件及放款数</h4>
		<div class="entry-chart">
			
		</div>
	</div>
</template>

<script type="text/ecmascript-6">
	import echarts from 'echarts'
	
	export default{
		name:'deily-entry',
		data(){
			return{
				pageData:{
					legendArr:['进件总数（笔）','进件金额（万元）','放款金额（万元）'],
					dateArr:['2018/03/01','2018/03/02','2018/03/03','2018/03/04','2018/03/05','2018/03/06','2018/03/07','2018/03/08','2018/03/09','2018/03/10',
				            '2018/03/11','2018/03/12','2018/03/13','2018/03/14','2018/03/15','2018/03/16','2018/03/17','2018/03/18','2018/03/19','2018/03/20'
				            ,'2018/03/21','2018/03/22','2018/03/23','2018/03/24','2018/03/25','2018/03/26','2018/03/27','2018/03/28','2018/03/29','2018/03/30','2018/03/31'
		            ],
		            totalArr:[200, 210, 220, 210, 230, 290, 310, 340,320, 300,  250, 210,200, 210, 220, 210, 230, 290, 310, 340,320, 300,  250, 210, 340,320, 300,  250, 210,  250, 210],
		            sumArr1:[100, 140, 100, 130, 150, 70, 76, 90, 78, 80, 90, 100,100, 140, 100, 130, 150, 70, 76, 90, 78, 80, 90, 100, 90, 78, 80, 90, 100, 90, 100],
		            sumArr2:[97, 110, 110, 70, 121, 132, 109, 110, 87, 88, 75, 74,97, 110, 110, 70, 121, 132, 109, 110, 87, 88, 75, 74, 110, 87, 88, 75, 74, 75, 74]
				}
			}
		},
		mounted(){
			this.ready();
		},
		methods:{
			ready(){
				
				let option = {
				    tooltip: {
				        trigger: 'axis',
				        axisPointer: {type: 'cross'}
				    },
				    legend: {
				        data:this.pageData.legendArr
				    },
			     	grid: {
				        y2: 100
				    },
				    xAxis: [
				        {
				            type: 'category',
				            data: this.pageData.dateArr,
				            axisLabel: {
				                textStyle: {
				                    fontStyle: "oblique"
				                },
			                  	rotate: 49,
			                  	interval: 0
				            }
				        }
				    ],
				    yAxis: [
				        {
				            type: 'value',
				            min: 0,
				            max: 450,
				            interval: 50,
				            axisLabel: {
				                formatter: '{value}'
				            }
				        },
				        {
				            type: 'value',
				            min: 0,
				            max: 200,
				            interval: 10,
				            position: 'right',
				            axisLabel: {
				                formatter: '{value}',
				            }
				        }
				    ],
				    series: [
				        {
				            name:this.pageData.legendArr[0],
				            type:'bar',
				            itemStyle:{
				            	normal:{
				            		color: new echarts.graphic.LinearGradient(
				                        0, 0, 0, 1,
				                        [//
				                            {offset: 0, color: '#11a8ab'},
				                            {offset: 0.5, color: '#11a8ab'},
				                            {offset: 1, color: '#fff'}
				                        ]
				                    )
			            		}
			            	},
				            data:this.pageData.totalArr,
				            barWidth: '60%'
				        },
				        {
				            name:this.pageData.legendArr[1],
				            type:'line',
				            yAxisIndex: 1,
				            itemStyle:{normal:{color:'#bf0000'}},
				            data:this.pageData.sumArr1,
				        },
				        {
				            name:this.pageData.legendArr[2],
				            type:'line',
				            yAxisIndex: 1,
				            itemStyle:{normal:{color:'#003f7f'}},
				            data:this.pageData.sumArr2
				        }
				    ]
				}
				echarts.init(document.querySelector('.entry-chart')).setOption(option);

			}
		}
	}
</script>

<style lang="less" type="text/less">
	#daily-entry{
		.title{
			padding-top:30px ;
		}
		.entry-chart{
			width: 100%;
			height: 87vh;
		}
	}
</style>