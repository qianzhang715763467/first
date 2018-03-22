<template>
    <div id="lineChart1" class="chartBox" ref="AAA"></div>
</template>

<script type="text/ecmascript-6">
    import echarts from 'echarts';
    export default{
        name: 'lineChart1',
        data(){
        	return{
        		count:0,
        		chartData:{
        			'color':['#666','#d14a61'],
	                'legend':['申请','放款'],
	                'xAxis':[
	                    {
	                        'values':["", "", "", "", "", "", "", "", "", "", "", ""]
	                    },
	                    {
	                        'values':["", "", "", "", "", "", "", "", "", "", "", ""]
	                    }
	                ],
	                'series':[
	                    {
	                        'name':'申请',
	                        'values':[0, 0, 0, 0, 0, 0, 0, 0, 0,2.6, 5.9, 9.0 ]
	                    },
	                    {
	                        'name':'放款',
	                        'values':[0, 0, 0, 0, 0, 0, 0, 0,0,3.9, 5.9, 11.1]
	                    }
	                ]
        		}
        	}
        },
        watch:{
        	count(newVal,oldVal){
        		if(newVal == 2){
        			this.chart();
        		}
        	}
        },
        methods: {
        	ready(){
        		// 申请数据
            	this.getData(432,'month(begintime)','apply_amt',0);
            	// 放款数据
            	this.getData(433,'month(batchdate)','loan_batch_success_amt',1);
        	},
        	getData(urlID,month,amt,size){
        		var self = this;
        		let d = new Date().getFullYear();// 当前年份
        		self.axios.get("http://ds.idc.xiwanglife.com/dataservice/getconfig.do?id="+urlID).then(function (res) {
					let arr = res.data.details.key1.values;
					let last_month = arr[arr.length-1][month];//获取到的最后一个月份
					// 说明当前最后一个月份的 12 月 指的是上一年的12月
					if(last_month == 12 && arr[arr.length-2][month] != 11){
						let newLast = arr[arr.length-2][month];//获取到的最后一个月份
						for(let i = newLast; i < 12;i++){
							self.chartData.xAxis[size].values[i-newLast] = d-1+'-'+(i+1);
							self.chartData.series[size].values[i-newLast] = 0;
						}
						self.chartData.xAxis[size].values[11-newLast] = d-1+'-12';
						self.chartData.series[size].values[11-newLast] = arr[arr.length-1][amt]/10000;
						for(let i = 0; i < arr.length-1; i++){
							self.chartData.xAxis[size].values[12-newLast+i] = d+'-'+arr[i][month];
							self.chartData.series[size].values[12-newLast+i] = arr[i][amt]/10000;
						}
					}else{
						// 正常情况下
						for(let i = last_month; i < 12;i++){
							self.chartData.xAxis[size].values[i-last_month] = d-1+'-'+(i+1);
							self.chartData.series[size].values[i-last_month] = 0;
						}
						for(let i = 0; i < arr.length; i++){
							self.chartData.xAxis[size].values[12-last_month+i] = d+'-'+arr[i][month];
							self.chartData.series[size].values[12-last_month+i] = arr[i][amt]/10000;
						}
					}
					self.count ++;
			  	}).catch(function (res) {
			  		console.log(res);
			  	});
        	},
        	formatData(){// 格式化图标数据 xAxis & series
    			let xAxisData = []; // series 数据
            	for(let i = 0; i < this.chartData.xAxis.length; i++){
        			xAxisData.push({
                        type: 'category',
                        axisTick: {
                            alignWithLabel: true
                        },
                        axisLine: {
                            onZero: false,
                            lineStyle: {
                                color: (i == 1?this.chartData.color[0]:this.chartData.color[1])
                            }
                        },
                        axisLabel:{
                          color:'#a8a8a8'
                        },
                        axisPointer: {
                            label: {
                                formatter: function (chartData) {
                                   let name = (i == 1?this.chartData.legend[0]:this.chartData.legend[i+1]);
                                    return name + chartData.value + (chartData.seriesData.length ? '：' + chartData.seriesData[0].data : '');
                                }.bind(this)
                            }
                        },
                        data: this.chartData.xAxis[i].values
                    })
            	}
            	
    			let seriesData = []; // series 数据
            	for(var i = 0; i < this.chartData.series.length; i++){
        			seriesData.push({
                        name:this.chartData.series[i].name,
                        type:'line',
                        xAxisIndex: (i == 0? 1:0),
                        smooth: true,
                        data: this.chartData.series[i].values
                    })
            	}
            	return {
            		xAxisData,
            		seriesData
            	};
        	},
            chart() {
                var option = {
                    color: this.chartData.color,
                    tooltip: {
                        trigger: 'none',
                        axisPointer: {
                            type: 'cross'
                        }
                    },
                    legend: {
                        orient: 'vertical',
                        borderWidth:1,
                        right:0,
                        data:this.chartData.legend
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: this.formatData().xAxisData,
                    yAxis: [
                        {
                            type: 'value',
                            axisLabel:{
                                color:'#a8a8a8'
                            },
                        }
                    ],
                    series:this.formatData().seriesData
                };
                // 使用刚指定的配置项和数据显示图表。
                echarts.init(this.$refs.AAA).setOption(option);
            }
        },
        mounted(){
        	this.ready();
        }
    }
</script>

<style lang="less" type="text/less" scoped>
</style>