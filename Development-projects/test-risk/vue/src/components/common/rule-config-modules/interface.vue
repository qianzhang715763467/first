<template>
	<div id="interface">
		<div class="interfaceList-wrap" @click="close($event)">
			<section class="interfaceList-main">
				<span class="interfaceList-close">X</span>
				<h6 class="interfaceList-title">{{pageData.title}}</h6>
				<div class="interfaceList-content">
					<ol class="items">
						<li class="item" v-for="(row,index) in pageData.values" :data-index="index">{{row.name}}</li>
					</ol>
					<p class="interfaceList-closing">没有数据了...</p>
				</div>
			</section>
		</div>
	</div>
</template>

<script>
	export default{
		name:'interface',
		data(){
			return{
				pageData:{
					title:"接口列表",
					values:[]
				}
			}
		},
		methods:{
			close(e){
				let $className = e.target.className; 
				if($className.indexOf('interfaceList-wrap') > -1 || $className.indexOf('interfaceList-close') > -1){
					this.$emit('upup',{'id':'-1' ,'state':false});
				}else if($className.indexOf('item') > -1){
					let row = this.pageData.values[e.target.getAttribute('data-index')];
					this.$emit('upup',{'id':row.id,'val':row.interface_json,'state':false});
				}
			}
		}
	}
</script>

<style lang="less" type="text/less">
	
</style>