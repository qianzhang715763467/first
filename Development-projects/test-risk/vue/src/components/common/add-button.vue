<template>
	<div id="add-button" v-if="ready()">
		<router-link tag="a" class="add-button" v-if="params.name"
			:to="{name:params.name,query:{action:params.action,type:params.type}}">
			{{params.val}}
		</router-link>
	</div>
</template>

<script>
	export default{
		name:"add-button",
		props:['params'],
		mounted(){
		},
		methods:{
			ready(){
                return (this.params.val ? true : false);
			}
		}
	}
</script>

<style lang="less" type="text/less" >
	@import url("./../../less/common");
	#add-button{
		.add-button{
			position: absolute;
		    top: 15px;
		    left: 15px;
		    padding: 4px 8px;
		    width:100px;
			font-size: 14px;
			text-align:center; 
			border-radius:3px; 
			background:@lightGray; 
			cursor:pointer;
			&:hover{
				color: #00bcd4;
				box-shadow: 0 0 8px 0px #bdbaba;
			}
		}
	}
</style>