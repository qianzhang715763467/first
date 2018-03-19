<template>
	<div id="dependency-filter">
		<div class="interfaceList-wrap" @click="close($event)">
			<section class="interfaceList-main">
				<span class="interfaceList-close">X</span>
				<h6 class="interfaceList-title">{{dependencyData.title}}</h6>
				<p class="mode-selection">
					<span class="mode-item mode-sum" :class="{'mode-enable':enable}"  @click="enable = !enable">合计字段</span>
					<span class="mode-item mode-single" :class="{'mode-enable':!enable}"@click="enable = !enable">特定值</span>
				</p>
				<div class="interfaceList-content mode-sum" v-show="enable">
					<ol class="items">
						<li class="item" v-for="(i,index) in dependencyData.sum.values">
							<input type="radio"  :name="i" :id="i" :value="i" v-model="dependencyData.sum.selectFields"/>
							<label :for="i">{{i}}</label>
						</li>
					</ol>
				</div>
				<div class="interfaceList-content mode-single" v-show="!enable">
					<ol class="items">
						<li class="item">
							<select class="select-itme-inputbox" v-model="dependencyData.single.selectFields.name">
								<option v-for="(i,index) in dependencyData.single.values" :value="i" v-if='index == 0' selected="selected">{{i}}</option>
								<option :value="i" v-else>{{i}}</option>
							</select>
						</li>
						<li class="item">
							<select class="select-itme-inputbox" v-model="dependencyData.single.selectFields.val">
								<option v-for="(i,index) in dependencyData.sum.values" :value="i" v-if='index == 0' selected="selected">{{i}}</option>
								<option :value="i" v-else>{{i}}</option>
							</select>
						</li>
					</ol>
				</div>
				<div class="sub">确定</div>
			</section>
		</div>
	</div>
</template>

<script>
	export default{
		name:'dependency-filter',
		data(){
			return{
				enable:true,
				str:"",
				dependencyData:{
					title:"计算依赖字段 ——> 字段筛选",
					sum:{
						selectFields:'',
						values:[]
					},
					single:{
						selectFields:{
							'name':"",
							'val':""
						},
						values:[]
					}
				}
			}
		},
		methods:{
			close(e){
				let $className = e.target.className; 
				if($className.indexOf('interfaceList-wrap') > -1 || $className.indexOf('interfaceList-close') > -1){
					this.$emit('upup',{'state':false});
				}else if($className.indexOf('sub') > -1){
					if(this.enable){
						if(!this.dependencyData.sum.selectFields)return alert('请先选择字段！');
						this.$emit('upup',{'val':this.dependencyData.sum.selectFields+'='+this.str,'state':false});
					}else{
						// 两个字段必须全选
						if(!this.dependencyData.single.selectFields.name || !this.dependencyData.single.selectFields.val)return alert('请选择完整字段！');
						this.dependencyData.single.selectFields.name += '='+this.str;
						this.dependencyData.single.selectFields.val += '='+this.str;
						this.$emit('upup',{'val':JSON.parse(JSON.stringify(this.dependencyData.single.selectFields)) ,'state':false});
					}
				}
			},
			formatData(msg){
				this.enable = true;
				this.dependencyData.sum.values.length = 0;
				this.dependencyData.single.values.length = 0;
				for(let  i = 0; i < msg.length; i++){
					let obj = msg[i];
					for(let j in obj){
						if(Number(obj[j])){
							if(i == 0){
								this.dependencyData.sum.values.push(j);
							}
						}else{
							if(i > 0){
								if(obj[j] != msg[i-1][j]){
								this.dependencyData.single.values.push(obj[j]);
								}
							}else{
								this.dependencyData.single.values.push(obj[j]);
							}
						}
					}
				}
			}
		}
	}
</script>

<style lang="less" type="text/less">
	#dependency-filter{
		.mode-selection{
			padding-top: 3px;
			width: 100%;
			font-size: 0px;
			.mode-item{
				display: inline-block;
				letter-spacing: normal;
    			word-spacing: normal;
    			padding: 8px;
				width: 50%;
				font-size: 14px;
				text-align: center;
				color: #ccc;
				background-color: #eee;
				cursor: pointer;
				&:hover{
					opacity: .8;
					color: #000;
					background-color: #FFCC80;
				}
				&.mode-enable{
					color: #fff;
					background-color: #26A69A;
				}
			}
		}
		.interfaceList-content{
			padding-top: 5px;
			height: 100%;
			&.mode-sum{
				input{
				    vertical-align: middle;
				}
				label{
					vertical-align: sub;
				}
			}
			&.mode-single{
				.select-itme-inputbox{
					padding: 4px;
					border: none;
					border-bottom: 1px solid #666;
					cursor: pointer;
					outline:none; 
					&:hover{
						color: #00BCD4;
					}
					option{
						padding: 3px;
						color: #999;
					}
				}
			}
			li{
				padding: 0;
				height: 30px;
				line-height: 30px;
				text-indent: 0;
				list-style-type: none;
	            font-weight: 400 !important;
	            font-size: 13px !important;
			}
		}
		.sub{
			width: 100%;
			height: 30px;
			line-height: 30px;
			text-align: center;
			color: #666;
			font-size: 14px;
			border-radius: 3px;
			background-color: #e0e0e0;
			cursor: pointer;
			transition:all .3s; 
			&:hover{
				color: #fff;
				background-color:#26A69A;
			}
		}
	}
</style>