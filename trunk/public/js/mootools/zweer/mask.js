if(!window.Zweer) var Zweer = {};

/**
 * Classe che aggiunge un effetto grafico di dissolvenza alla classe Mask()
 * @author Niccolò Olivieri <flicofloc@gmail.com>
 * @requires Mask
 *
 */
Zweer.Mask = new Class({
	Extends: Mask,

	options: {
		'class': 'zweer_mask',
		start: 0,
		end: 0.75,
		fx: {
			property: 'opacity',
			link: 'cancel'
		}
	},

	destroyOnHide: false,

	initialize: function(target, options)
	{
		this.destroyOnHide = options.destroyOnHide;
		options.destroyOnHide = false; // Se viene messo a TRUE, non esegue l'effetto quando scompare
		this.parent(target, options);
		this.element.set('tween', this.options.fx);
		this.element.get('tween').set(this.options.fx.property, this.options.start);
		this.element.setStyle('display', 'block');
	},

	showMask: function()
	{
		this.hidden = false;
		this.fireEvent('show');
		this.element.get('tween').start(this.options.end).chain(function() {
			this.fireEvent('showEnd');
		}.bind(this));
	},

	hideMask: function()
	{
		this.hidden = true;
		this.fireEvent('hide');
		this.element.get('tween').start(this.options.start).chain(function() {
			if(this.destroyOnHide)
				this.destroy();
			this.fireEvent('hideEnd');
		}.bind(this));
	}
});