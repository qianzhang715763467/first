<template>
	<div id="letter">
		<!-- 每日进件及放款数 -->
		<daily-entry></daily-entry>
		<!-- 业主贷进件数据分析 -->
		<incoming-analysis></incoming-analysis>
		<!-- 至今数据 -->
		<since></since>
		<!-- 拒绝原因分析-->
		<rejection></rejection>
		<!-- 小微企业进件数据分析 -->
		<micro></micro>
	</div>
</template>

<script type="text/ecmascript-6">
	import DailyEntry from './LetterData/DailyEntry.vue';
	import IncomingDataAnalysis from './LetterData/IncomingDataAnalysis.vue';
	import Since from './LetterData/SinceData.vue';
	import Rejection from './LetterData/RejectionAnalysis.vue';
	import Micro from './LetterData/MicroDataAnalysis.vue';
	
	
	export default{
		name:'letter',
		data(){
			return{
				
			}
		},
		components:{
			"daily-entry":DailyEntry,
			'incoming-analysis':IncomingDataAnalysis,
			"since":Since,
			"rejection":Rejection,
			"micro":Micro
		}
	}
	
</script>

<style lang="less" type="text/less">
	#letter{
		width: 100%;
		height: 100%;
		overflow-y: auto;
		overflow-x: hidden;
		.letter-module{
			margin-bottom: 10px;
			padding:0 10px .1px;
			border-radius: 3px;
			background-color: #fff;
		}
		.title{
			padding:20px 5px;
			color: #333;
			font-weight: 600;
			text-align: center;
		}	
		table{
			margin-bottom: 20px;
			width: 100%;
			height: 100%;
			text-align: center;
			font-size: .8em;
			border-spacing: 0;
            border-collapse: collapse;
            border: 1px solid #ccc;
            th{
            	color: #333;
				background-color: #a0b9c5;
			}
			th,td{
				padding: 5px;
			}
		}	
	}
</style>