<template>
    <div id="lineChart2" class="chartBox" ref="AAA"></div>
</template>

<script type="text/ecmascript-6">
    import echarts from 'echarts';
    export default{
        name: 'lineChart2',
        data(){
        	return{
        		chartData:{
        			'color':['#666','#d14a61'],
	                'legend':['通过','拒绝'],
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
	                        'name':'通过',
	                        'values':[0, 0, 0, 0, 0, 0, 0, 0, 0,2.6, 5.9, 9.0 ]
	                    },
	                    {
	                        'name':'拒绝',
	                        'values':[0, 0, 0, 0, 0, 0, 0, 0,0,3.9, 5.9, 11.1]
	                    }
	                ]
        		}
        	}
        },
        methods: {
        	ready(){
        		let self = this;
        		self.axios.get("http://ds.idc.xiwanglife.com/dataservice/getconfig.do?id=434")
        		.then(function (res) {
        			let N = [],	Y = [];
        			let total = 0;
        			let Nsize = 0;
        			res.data.details.key1.values.map((item) => {
        				total += item['count(distinct a.serialno)'];
        				if(item.tag == 'N'){
        					N.push(item);
        					Nsize += item['count(distinct a.serialno)'];
        				}else{
        					Y.push(item);
        				}
        			})
        			self.$emit('upup',(Nsize / total*100).toFixed(0))
        			self.getData(N,'month(t.begintime)','count(distinct a.serialno)',0);
        			self.getData(Y,'month(t.begintime)','count(distinct a.serialno)',1);
        			self.chart();
        		}).catch(function (res) {
			  		console.log(res);
			  	});
        	},
        	getData(arr,month,amt,size){
        		let d = new Date().getFullYear();// 当前年份
				let last_month = arr[arr.length-1][month];//获取到的最后一个月份
				// 说明当前最后一个月份的 12 月 指的是上一年的12月
				if(last_month == 12 && arr[arr.length-2][month] != 11){
					let newLast = arr[arr.length-2][month];//获取到的最后一个月份
					for(let i = newLast; i < 12;i++){
						this.chartData.xAxis[size].values[i-newLast] = d-1+'-'+(i+1);
						this.chartData.series[size].values[i-newLast] = 0;
					}
					this.chartData.xAxis[size].values[11-newLast] = d-1+'-12';
					this.chartData.series[size].values[11-newLast] = arr[arr.length-1][amt];
					for(let i = 0; i < arr.length-1; i++){
						this.chartData.xAxis[size].values[12-newLast+i] = d+'-'+arr[i][month];
						this.chartData.series[size].values[12-newLast+i] = arr[i][amt];
					}
				}else{
					// 正常情况下
					for(let i = last_month; i < 12;i++){
						this.chartData.xAxis[size].values[i-last_month] = d-1+'-'+(i+1);
						this.chartData.series[size].values[i-last_month] = 0;
					}
					for(let i = 0; i < arr.length; i++){
						this.chartData.xAxis[size].values[12-last_month+i] = d+'-'+arr[i][month];
						this.chartData.series[size].values[12-last_month+i] = arr[i][amt];
					}
				}
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