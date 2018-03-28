<template>
	<div id="micro-analysis" class="letter-module">
		<h4 class="title">小微企业进件数据分析</h4>
	<table border="1" cellspacing="0" cellpadding="0" v-for="tab in pageData">
			<tr v-for="(row,i) in tab.key" v-if="i == 0"> <!-- 首行th -->
				<th v-for="(item,index) in row" :colspan="item.colspan||0" :rowspan="item.rowspan||0" >
					{{item.name}}
				</th>
			</tr>
			<tr v-else><!-- 其他行th -->
				<th v-for="(item,index) in row">
					{{item.name}}
				</th>
			</tr>
			<tr v-for="row in tab.values"><!-- td -->
				<td v-for="item in tab.collation">
					{{row[item]}}
				</td>
			</tr>
		</table>
	</div>
</template>

<script type="text/ecmascript-6">
	export default{
		name:'micro-analysis',
		data(){
			return{
				pageData:{
					loan:{// 放款
						// td列显示顺序依照当前数组排序规则
						'collation':[
							'date',
							'entry_number',
							'entry_sum',
							'average_entry_sum',
							'loan_sum_ratio'
						],
						// 合并行(rowspan)&合并列(colspan)的字段只能出现一次。重复出现样式会发生错误，配置时请注意
						'key':[
							[
								{'name':'日期','rowspan':'2'},
								{'name':'预审情况','colspan':'4'}
							],[
								{'name':'借款企业数'},
								{'name':'意向金额（万元）'},
								{'name':'建议准入（笔）'},
								{'name':'不建议准入（笔）'}
							]
						],
						'values':[
							{
								'date':'2018/3/2',
								'entry_number':'0',
								'entry_sum':'0',
								'average_entry_sum':'0',
								'loan_sum_ratio':'0'
							},
							{
								'date':'2018/3/2',
								'entry_number':'0',
								'entry_sum':'0',
								'average_entry_sum':'0',
								'loan_sum_ratio':'0'
							},
							{
								'date':'总计',
								'entry_number':'105',
								'entry_sum':'66503.18466',
								'average_entry_sum':'70',
								'loan_sum_ratio':'35'
							}
						]
					},
					systemApproval:{// 系统审批
						'collation':[
							'pass_count',
							'reject_count',
							'waits_submit_count',
							'waits_submit_sum'
						],
						'key':[
							[
								{'name':'审批情况','colspan':'4'}
							],[
								{'name':'审批通过（笔）'},
								{'name':'审批拒绝（笔）'},
								{'name':'放款笔数（笔）'},
								{'name':'放款金额（万元）'}
							]
						],
						'values':[
							{
								'pass_count':'0',
								'reject_count':'0',
								'waits_submit_count':'0',
								'waits_submit_sum':'0'
							},
							{
								'pass_count':'0',
								'reject_count':'0',
								'waits_submit_count':'0',
								'waits_submit_sum':'0'
							},
							{
								'pass_count':'12',
								'reject_count':'0',
								'waits_submit_count':'12',
								'waits_submit_sum':'2009.432072'
							}
						]
					}
				}
			}
		}
	}
</script>

<style lang="less" type="text/less">
	#micro-analysis{
		table{
			tr:last-child{
				color: red;
				font-weight: 600;
			}
		}
	}
</style>