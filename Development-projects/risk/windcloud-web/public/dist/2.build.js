webpackJsonp([2],{693:function(t,e,a){function n(t){a(711)}var i=a(5)(a(699),a(706),n,null,null);t.exports=i.exports},699:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=a(45),i=a.n(n);e.default={name:"rejection-analysis",data:function(){return{pageData:{values:[{title:"拒绝原因分布",id:"a",data:[{value:335,name:"超时未提价资料"},{value:310,name:"超时未面签"},{value:234,name:"系统拒绝"},{value:135,name:"拒绝审批"},{value:1548,name:"面签拒绝"}]},{title:"系统拒绝原因分布",id:"b",data:[{value:335,name:"超时未提价资料"},{value:310,name:"超时未面签"},{value:234,name:"系统拒绝"},{value:135,name:"拒绝审批"},{value:1548,name:"面签拒绝"}]},{title:"审批拒绝原因分布",id:"c",data:[{value:335,name:"超时未提价资料"},{value:310,name:"超时未面签"},{value:234,name:"系统拒绝"},{value:135,name:"拒绝审批"},{value:1548,name:"面签拒绝"}]},{title:"面签拒绝原因分布",id:"d",data:[{value:335,name:"超时未提价资料"},{value:310,name:"超时未面签"},{value:234,name:"系统拒绝"},{value:135,name:"拒绝审批"},{value:1548,name:"面签拒绝"}]}]}}},methods:{charts:function(t){var e=[];t.data.map(function(t){e.push(t.name)});var a={backgroundColor:"#fff",title:{top:"5%",text:t.title,x:"center"},tooltip:{trigger:"item",formatter:"{b} : {c} (件)"},legend:{orient:"vertical",top:"10%",left:"left",data:e},series:[{type:"pie",radius:"55%",center:["50%","60%"],data:t.data,label:{normal:{position:"inner",formatter:"{d}%",offset:[,100],textStyle:{color:"#fff",fontSize:14}}},itemStyle:{emphasis:{shadowBlur:10,shadowOffsetX:0,shadowColor:"rgba(0, 0, 0, 0.5)"}}}]};setTimeout(function(){i.a.init(document.querySelector("#"+t.id)).setOption(a)},100)}}}},701:function(t,e,a){e=t.exports=a(4)(),e.push([t.i,"#rejection-analysis{width:100%;height:100%}#rejection-analysis .chart-content{width:100%;height:calc(100% - 65px);font-size:0;background-color:#f2f2f2}#rejection-analysis .chart-content .chart-box{display:inline-block;width:calc(50% - 2px);height:calc(50% - 2px);background-color:#fff;border-radius:5px}#rejection-analysis .chart-content .chart-box:nth-of-type(odd){margin-right:4px}#rejection-analysis .chart-content .chart-box:nth-of-type(3n+0){margin-top:4px}",""])},706:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"letter-module",attrs:{id:"rejection-analysis"}},[a("h4",{staticClass:"title"},[t._v("业主贷拒绝原因分析")]),t._v(" "),a("section",{staticClass:"chart-content"},t._l(t.pageData.values,function(e){return a("div",{staticClass:"chart-box",attrs:{id:e.id}},[t._v("\n\t\t\t"+t._s(t.charts(e))+";\n\t\t")])}))])},staticRenderFns:[]}},711:function(t,e,a){var n=a(701);"string"==typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);a(6)("c53c95b4",n,!0)}});
//# sourceMappingURL=2.build.js.map