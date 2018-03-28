import RiskIndex 	from './components/RiskIndex.vue'
//import LetterData 	from './components/LetterData.vue'
const LetterData 		= r => require.ensure([], () => r(require('./components/LetterData.vue')), 'LetterData');
const IncomingDataAnalysis = r => require.ensure([], () => r(require('./components/LetterData/IncomingDataAnalysis.vue')), 'IncomingDataAnalysis');
const MicroDataAnalysis = r => require.ensure([], () => r(require('./components/LetterData/MicroDataAnalysis.vue')), 'MicroDataAnalysis');
const RejectionAnalysis = r => require.ensure([], () => r(require('./components/LetterData/RejectionAnalysis.vue')), 'RejectionAnalysis');
const DailyEntry 		= r => require.ensure([], () => r(require('./components/LetterData/DailyEntry.vue')), 'DailyEntry');
const SinceData 		= r => require.ensure([], () => r(require('./components/LetterData/SinceData.vue')), 'SinceData');
import Check 		from './components/Check.vue'
import RiskStrategy from './components/RiskStrategy.vue'
import Monitor 		from './components/Monitor.vue'
import Products 	from './components/RiskStragety/products.vue'
import RuleConfig 	from './components/RiskStragety/RuleConfig.vue'
import RuleSetConfig from './components/RiskStragety/RulesetConfig.vue'
import NameList 	from './components/RiskStragety/NameList.vue'
import Details 		from './components/RiskStragety/details.vue'
import Add 			from './components/RiskStragety/Add.vue'
const routes=[
    {path:'/riskIndex', name:'riskIndex', component:RiskIndex},
    {path:'/check', name:'check', component:Check},
    {
    	path:'/letterData',
    	name:'letterData',
    	component:LetterData,
    	children:[
    		{path:'incomingDataAnalysis',name:'incomingDataAnalysis',	component:IncomingDataAnalysis},
    		{path:'microDataAnalysis', 	name:'microDataAnalysis',	component:MicroDataAnalysis},
    		{path:'rejectionAnalysis', 	name:'rejectionAnalysis',	component:RejectionAnalysis},
    		{path:'dailyEntry', 		name:'dailyEntry',			component:DailyEntry},
    		{path:'sinceData', 			name:'sinceData',			component:SinceData}
    	]
	},
    {
        path:'/riskStrategy',
        name:'riskStrategy',
        component:RiskStrategy,
        children:[
         	{path:'products', 		name:'products',		component:Products},
            {path:'ruleConfig', 	name:'ruleConfig',		component:RuleConfig},
            {path:'ruleSetConfig',	name:'ruleSetConfig',	component:RuleSetConfig},
            {path:'nameList',		name:'nameList',		component:NameList},
            {path:'add',		    name:'add',			    component:Add},
            {path:'details',		name:'details',			component:Details},
            {path:'/riskStrategy',	redirect:'/riskStrategy/products'}
        ]
    },
    {path:'/monitor', name:'monitor', component:Monitor},
    {path:'*',redirect:'/riskIndex'}
];

export default{
    mode: 'history',
    base: __dirname,
    routes
}
