<template>
    <div id="RiskHeader">
    	<ul class="items">
			<li class="item" v-for="row in values">
				<div class="item-icon iconBox">
	                <svg-icon class="icons" :id="row.iconId"></svg-icon>
	            </div>
				<section class="item-content detailInfo">
					<h6 class="item-title title">{{row.title}}</h6>
					<p class="item-num num">{{row.num}}</p>
					<p class="item-count">{{row.increaseRatio}}</p>
				</section>
			</li>
		</ul>
	</div>
</template>

<script type="text/ecmascript-6">
    export default{
        name: 'RiskHeader',
        data(){
            return {
            	values:[
            		{title:'大额业主贷',iconId:'icon-tubiaolunkuo_huaban',num:'',increaseRatio:'','field':'YZDDE0001'},
                    {title:'家私贷',iconId:'icon-yue',num:'',increaseRatio:'','field':'YZDJS0001'},
                    {title:'追加贷',iconId:'icon-yuqi',num:'',increaseRatio:'','field':'YZDZJ0001'},
                    {title:'总逾期率',iconId:'icon-yuqidelicai',num:'0.00%',increaseRatio:'','field':''}
            	]
            }
        },
        mounted(){
        	var self = this;
			self.axios.get("http://ds.idc.xiwanglife.com/dataservice/getconfig.do?id=418").then(function (res) {
				let arr = res.data.details.list.values;
				for(let j = 0; j < self.values.length; j++){
					for(let i = 0; i < arr.length; i++){
						if(arr[i].specificid == self.values[j].field){
							self.values[j].num = arr[i].apply_amt/10000+'w';
							self.values[j].increaseRatio = arr[i].apply_cnt;
						}
					}
				}
		  	}).catch(function (res) {
		  		console.log(res);
		  	});
        },
        method:{
        }
    }
</script>

<style lang="less" type="text/less">
    #RiskHeader {
        position: relative;
        padding: 10px 0;
        width:100%;
    	.items{
    		position: relative;
    		display: flex;
			width: 100%;
            padding-top: calc(~"12vh/2 - 55px");
			.item{
				display: flex;
				flex-direction: unset;
				width: 25%;
				overflow: hidden;
				text-align:center;
				.item-icon{
					position:relative;
					padding-right: 10px;
					min-width: 50px;
					width: 40%;
					text-align: right;
					color: #5ea6ec;
					font-size: 1.7rem;
					.icon{
						position: absolute;
						top: 0;
						bottom: 0;
						right: 20px;
						margin: auto;
					}
				}
				.item-content{
					position: relative;
					width: 75%;
					text-align: left;
					.item-title{
						padding: 0px 0 8px;
						font-size: 1rem;
						color: #a8a8a8;
					}
					.item-num{
						font-size: 1.5rem;
					}
					.item-count{
						padding-bottom: 2px;
						color: #666;
					}
				}
				&:nth-of-type(3){
					.icon{
						font-size: 1.9rem;
					}
				}
			}
    	}
    }
</style>