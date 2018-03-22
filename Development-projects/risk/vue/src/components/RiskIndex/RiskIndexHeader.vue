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
            		{title:'大额业主贷',iconId:'icon-tubiaolunkuo_huaban',num:996,increaseRatio:'213'},
                    {title:'家私贷',iconId:'icon-yue',num:396,increaseRatio:'45'},
                    {title:'追加贷',iconId:'icon-yuqi',num:79,increaseRatio:'456'},
                    {title:'总逾期率',iconId:'icon-yuqidelicai',num:'13.16%',increaseRatio:''}
            	]
            }
        },
        method:{
        },
        mounted(){
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