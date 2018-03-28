<template>
	<div id="back">
		<section class="back icon-hover" @click="back">
			<svg-icon class="icons" id="icon-fanhui"></svg-icon>
		</section>
	</div>
</template>

<script>
	export default{
		name:"back",
		props:['params'],// 两个参数 name(规则集|产品),action(动作说明，从哪退出)
		data(){
            return{
			}
		},
		methods:{
			back(){
				if(this.params.action == 'create'){
					if(this.params.type == 'product'){
						this.$router.push("/riskStrategy/products");
						return;
					}else{
						this.$router.push('/riskStrategy/ruleSetConfig');
						return;
					}
				}
				if(this.params.action == 'viewOnly'){
					this.$global.cacheData.status = 'back';
				}
            	window.history.go(-1);
            }
		},
		mounted(){
		}
	}
</script>

<style lang="less" type="text/less">
	#back{
		.back{
		    left:10px;
		    .icon{
		    	padding: 5px;
		    }
		}
	}
</style>