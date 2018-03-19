import Vue 			from 'vue'
import App 			from './App.vue'
import VueRouter 	from 'vue-router'
import VueResource 	from 'vue-resource'
import Axios 		from 'axios'
import routerConfig from './router-config.js'
import globalData   from './js/global.js'
// less 初始化
require('!style-loader!css-loader!less-loader!./less/common.less');
require('!style-loader!css-loader!less-loader!./less/interface.less');// 规则页面接口组件通用样式
require('!style-loader!css-loader!less-loader!./less/table.less');
// iconfont svg style
import './static/risk-iconfont/iconfont.css';
import './static/risk-iconfont/iconfont.js';

// 功能组件
import RulesetCard 	from './components/common/card.vue';			// card 
import Back			from './components/common/back.vue';			// back
import AddButton	from './components/common/add-button.vue';		// add-button 
import SvgIcon 		from './components/common/svg-icon.vue';		// svg-icon-font

// 挂载功能组件
Vue.component('SvgIcon',SvgIcon)
Vue.component('RulesetCard',RulesetCard)
Vue.component('back',Back)
Vue.component('AddButton',AddButton)
Vue.use(VueRouter)
Vue.use(VueResource)
Vue.prototype.axios = Axios;
Vue.prototype.$global = globalData;
//控制弹框的显示隐藏：
Vue.prototype.alertParams={    //弹框参数
    showAlert:false,   //是否显示弹框
    alertTxt:false,    //弹框内容
    isConfirm:false,   //是否点击弹框的确认按钮
    showCancel:false   //是否显示弹框的取消按钮
};
// (当前实例|组件，路径，请求参数，给不同请求命名[可不写])
Vue.prototype.sendRequest= function($this,$url,$response,name){
	$this.axios.get('http://localhost:8090'+$url)
  	.then(function (response) {
  		$this[$response] = response.data;
  		if(name){
  			$this[$response]['name'] = name;
  		}
  	})
  	.catch(function (response) {
  		$this[$response] = response.data;
  		if(name){
  			$this[$response]['name'] = name;
  		}
  	});
}
const router= new VueRouter(routerConfig);

let vue = new Vue({
    router,
  	el: '#app',
  	render: h => h(App)
})
