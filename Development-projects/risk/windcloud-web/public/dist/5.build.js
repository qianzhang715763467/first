webpackJsonp([5],{690:function(t,e,a){function r(t){a(715)}var i=a(5)(a(696),a(710),r,null,null);t.exports=i.exports},696:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=a(45),i=a.n(r);e.default={name:"deily-entry",data:function(){return{pageData:{legendArr:["进件总数（笔）","进件金额（万元）","放款金额（万元）"],dateArr:["2018/03/01","2018/03/02","2018/03/03","2018/03/04","2018/03/05","2018/03/06","2018/03/07","2018/03/08","2018/03/09","2018/03/10","2018/03/11","2018/03/12","2018/03/13","2018/03/14","2018/03/15","2018/03/16","2018/03/17","2018/03/18","2018/03/19","2018/03/20","2018/03/21","2018/03/22","2018/03/23","2018/03/24","2018/03/25","2018/03/26","2018/03/27","2018/03/28","2018/03/29","2018/03/30","2018/03/31"],totalArr:[200,210,220,210,230,290,310,340,320,300,250,210,200,210,220,210,230,290,310,340,320,300,250,210,340,320,300,250,210,250,210],sumArr1:[100,140,100,130,150,70,76,90,78,80,90,100,100,140,100,130,150,70,76,90,78,80,90,100,90,78,80,90,100,90,100],sumArr2:[97,110,110,70,121,132,109,110,87,88,75,74,97,110,110,70,121,132,109,110,87,88,75,74,110,87,88,75,74,75,74]}}},mounted:function(){this.ready()},methods:{ready:function(){var t={tooltip:{trigger:"axis",axisPointer:{type:"cross"}},legend:{data:this.pageData.legendArr},grid:{y2:100},xAxis:[{type:"category",data:this.pageData.dateArr,axisLabel:{textStyle:{fontStyle:"oblique"},rotate:49,interval:0}}],yAxis:[{type:"value",min:0,max:450,interval:50,axisLabel:{formatter:"{value}"}},{type:"value",min:0,max:200,interval:10,position:"right",axisLabel:{formatter:"{value}"}}],series:[{name:this.pageData.legendArr[0],type:"bar",itemStyle:{normal:{color:new i.a.graphic.LinearGradient(0,0,0,1,[{offset:0,color:"#11a8ab"},{offset:.5,color:"#11a8ab"},{offset:1,color:"#fff"}])}},data:this.pageData.totalArr,barWidth:"60%"},{name:this.pageData.legendArr[1],type:"line",yAxisIndex:1,itemStyle:{normal:{color:"#bf0000"}},data:this.pageData.sumArr1},{name:this.pageData.legendArr[2],type:"line",yAxisIndex:1,itemStyle:{normal:{color:"#003f7f"}},data:this.pageData.sumArr2}]};i.a.init(document.querySelector(".entry-chart")).setOption(t)}}}},705:function(t,e,a){e=t.exports=a(4)(),e.push([t.i,"#daily-entry .title{padding-top:30px}#daily-entry .entry-chart{width:100%;height:87vh}",""])},710:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)},staticRenderFns:[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"letter-module",attrs:{id:"daily-entry"}},[a("h4",{staticClass:"title"},[t._v("业主贷每日进件及放款数")]),t._v(" "),a("div",{staticClass:"entry-chart"})])}]}},715:function(t,e,a){var r=a(705);"string"==typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);a(6)("b281a312",r,!0)}});
//# sourceMappingURL=5.build.js.map