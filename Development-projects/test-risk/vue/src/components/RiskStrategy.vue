<template>
    <div id="riskStrategy" v-if="showCurrent()">
       <router-view></router-view>
    </div>
</template>

<script type="text/ecmascript-6">
    export default{
        name: 'riskStrategy',
        data(){
          return{
              childRouter:''
          }
        },
        methods:{
            //解决从子导航点击父导航时页面空白问题：
            showCurrent(){
                if(this.$route.path.lastIndexOf("/")>1){
                    this.childRouter=this.$route.path;
                    return true;
                }else{
                	if(this.childRouter.indexOf('/details') > -1){
                    	this.childRouter = this.childRouter.split('/details')[0];
                    }
                	if(this.childRouter.indexOf('/add') > -1){
                    	this.childRouter = this.childRouter.split('/add')[0];
                    }
                    this.$router.push(this.childRouter);
                    return true;
                }

            }
        }
    }
</script>

<style lang="less" type="text/less">
    #riskStrategy{
    	width:100%;
    	height:100%;
    }
</style>