export default {
	name: 'tooltip',
	template: `<span class="wplegal-form-tooltip">
					<img class="wplegal-tooltip-image" :src="tooltip.default">
					<span class="wplegal-form-tooltiptext">{{text}}</span>
				</span>`,
	props: {
		text: {
			type: String,
			default: '',
		}
	},
	data() {
		return {
			tooltip: require('../../admin/images/tooltip-icon.png')
		}
	}
}