import RiskIndex from './components/RiskIndex.vue'
import Check from './components/Check.vue'
import RiskStrategy from './components/RiskStrategy.vue'
import Monitor from './components/Monitor.vue'
import Products from './components/RiskStragety/products.vue'
import RuleConfig from './components/RiskStragety/RuleConfig.vue'
import RuleSetConfig from './components/RiskStragety/RulesetConfig.vue'
import NameList from './components/RiskStragety/NameList.vue'
import Add from './components/common/Add.vue'
import DetailsPage from './components/common/details.vue'
import CreateRule from './components/common/create-rule.vue'

const routes = [{
		path: '/riskIndex',
		name: 'riskIndex',
		component: RiskIndex
	},
	{
		path: '/check',
		name: 'check',
		component: Check
	},
	{
		path: '/riskStrategy',
		name: 'riskStrategy',
		component: RiskStrategy,
		children: [{
				path: 'products',
				name: 'products',
				component: Products,
				children: [{
						path: 'details',
						name: 'product-details',
						component: DetailsPage
					},
					{
						path: 'add',
						name: 'product-add',
						component: Add
					},
				]
			},
			{
				path: 'ruleSetConfig',
				name: 'ruleSetConfig',
				component: RuleSetConfig,
				children: [{
						path: 'details',
						name: 'group-details',
						component: DetailsPage
					},
					{
						path: 'add',
						name: 'group-add',
						component: Add
					}
				]
			},
			{
				path: 'ruleConfig',
				name: 'ruleConfig',
				component: RuleConfig,
				children: [{
					path: 'create-rule',
					name: 'create-rule',
					component: CreateRule
				}]
			},
			{
				path: 'nameList',
				name: 'nameList',
				component: NameList
			},
			{
				path: '/riskStrategy',
				redirect: '/riskStrategy/products'
			}
		]
	},
	{
		path: '/monitor',
		name: 'monitor',
		component: Monitor
	},
	{
		path: '*',
		redirect: '/riskIndex'
	}
];

export default {
	mode: 'history',
	base: __dirname,
	routes
}