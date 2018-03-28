webpackJsonp([1],{694:function(e,t,a){function n(e){a(712)}var _=a(5)(a(700),a(707),n,null,null);e.exports=_.exports},700:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={name:"since",data:function(){return{pageData:{loan:{collation:["date","entry_number","entry_sum","average_entry_sum","loan_strokeCount","loan_sum","loan_strokeCount_ratio","loan_sum_ratio"],key:[[{name:"日期",rowspan:"2"},{name:"进件总数（笔）",rowspan:"2"},{name:"进件金额（万元）",rowspan:"2"},{name:"平均进件金额（万元）",rowspan:"2"},{name:"放款",colspan:"4"}],[{name:"放款笔数（笔）"},{name:"放款金额（万元）"},{name:"已放款笔数比率（%）"},{name:"已放款金额比率（%）"}]],values:[{date:"2018/3/2",entry_number:"29",entry_sum:"540",average_entry_sum:"18.62",loan_strokeCount:"3",loan_sum:"21",loan_strokeCount_ratio:"/",loan_sum_ratio:"/"},{date:"总计",entry_number:"740",entry_sum:"13543.3",average_entry_sum:"18.30",loan_strokeCount:"212",loan_sum:"2097.7",loan_strokeCount_ratio:"28.65",loan_sum_ratio:"15.49"}]},systemApproval:{collation:["pass_count","pass_sum","reject_count","reject_sum","pass_rate","pass_sum_rate","waits_submit_count","waits_submit_sum","uncommitted"],key:[[{name:"系统审批",colspan:"6"},{name:"提价资料",colspan:"3"}],[{name:"系统通过笔数（笔）"},{name:"系统通过金额（万元）"},{name:"系统拒绝笔数（笔）"},{name:"系统拒绝金额（万元）"},{name:"系统通过率（%）"},{name:"系统通过金额比率（%）"},{name:"待提交资料笔数（笔）"},{name:"待提交资料金额（万元）"},{name:"超时未提交（笔）"}]],values:[{pass_count:"20",pass_sum:"360",reject_count:"9",reject_sum:"180",pass_rate:"68.97",pass_sum_rate:"66.67",waits_submit_count:"/",waits_submit_sum:"/",uncommitted:"0"},{pass_count:"538",pass_sum:"9971.3",reject_count:"202",reject_sum:"3572",pass_rate:"72.70",pass_sum_rate:"73.63",waits_submit_count:"26",waits_submit_sum:"465",uncommitted:"133"}]},manualApproval:{collation:["approval_total","approval_sum","approval_pending_count","approval_pending_sum","approve_count","applied_sum","credit_sum","average_credit_sum","approval_rejections_count","approve_rate","approve_sum_rate"],key:[[{name:"人工审批",colspan:"11"}],[{name:"人工审批总数（笔）"},{name:"人工审批金额（万元）"},{name:"待审批笔数（笔）"},{name:"待审批金额（万元）"},{name:"审批通过笔数（笔）"},{name:"申请金额（万元）"},{name:"授信金额（万元）"},{name:"平均授信金额（万元）"},{name:"审批拒绝笔数（笔）"},{name:"审批通过率（%）"},{name:"审批通过金额比率（%）"}]],values:[{approval_total:"9",approval_sum:"145",approval_pending_count:"/",approval_pending_sum:"/",approve_count:"5",applied_sum:"75",credit_sum:"37",average_credit_sum:"7.40",approval_rejections_count:"4",approve_rate:"55.56",approve_sum_rate:"25.52"},{approval_total:"445",approval_sum:"8071.3",approval_pending_count:"11",approval_pending_sum:"220",approve_count:"253",applied_sum:"4666.8",credit_sum:"2451",average_credit_sum:"9.69",approval_rejections_count:"192",approve_rate:"56.85",approve_sum_rate:"30.37"}]},visaInterview:{collation:["approval_pass_rate","approval_credit_sum_rate","interview_total","wait_interview_count","not_interview_count","exempt_interview_count","exempy_interview_sum","interview_pass_count","interview_pass_sum","interview_reject_count","interview_pass_rate"],key:[[{name:"综合审批通过率（%）",rowspan:"2"},{name:"综合审批授信金额比率（%）",rowspan:"2"},{name:"面签",colspan:"9"}],[{name:"面签总数（笔）"},{name:"待面签笔数（笔）"},{name:"超时未面签（笔）"},{name:"免面签笔数（笔）"},{name:"免面签金额（万元）"},{name:"面签通过笔数（笔）"},{name:"面签通过金额（万元）"},{name:"面签拒绝笔数（笔）"},{name:"面签通过率（%）"}]],values:[{approval_pass_rate:"/",approval_credit_sum_rate:"/",interview_total:"5",wait_interview_count:"/",not_interview_count:"0",exempt_interview_count:"2",exempy_interview_sum:"19",interview_pass_count:"3",interview_pass_sum:"38",interview_reject_count:"0",interview_pass_rate:"100.00"},{approval_pass_rate:"41.33",approval_credit_sum_rate:"22.36",interview_total:"220",wait_interview_count:"10",not_interview_count:"47",exempt_interview_count:"33",exempy_interview_sum:"390",interview_pass_count:"183",interview_pass_sum:"1754.7",interview_reject_count:"4",interview_pass_rate:"83.18"}]}}}}}},702:function(e,t,a){t=e.exports=a(4)(),t.push([e.i,"",""])},707:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"letter-module",attrs:{id:"since"}},[a("h4",{staticClass:"title"},[e._v("1月9日至今数据")]),e._v(" "),e._l(e.pageData,function(t){return a("table",{attrs:{border:"1",cellspacing:"0",cellpadding:"0"}},[e._l(t.key,function(t,n){return 0==n?a("tr",e._l(t,function(t,n){return a("th",{attrs:{colspan:t.colspan||0,rowspan:t.rowspan||0}},[e._v("\n\t\t\t\t"+e._s(t.name)+"\n\t\t\t")])})):a("tr",e._l(t,function(t,n){return a("th",[e._v("\n\t\t\t\t"+e._s(t.name)+"\n\t\t\t")])}))}),e._v(" "),e._l(t.values,function(n){return a("tr",e._l(t.collation,function(t){return a("td",[e._v("\n\t\t\t\t"+e._s(n[t])+"\n\t\t\t")])}))})],2)})],2)},staticRenderFns:[]}},712:function(e,t,a){var n=a(702);"string"==typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);a(6)("a8f4b276",n,!0)}});
//# sourceMappingURL=1.build.js.map