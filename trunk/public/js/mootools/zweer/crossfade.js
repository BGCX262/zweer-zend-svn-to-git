if(!window.Zweer) var Zweer = {};

Zweer.Crossfade = new Class({
	Implements: [Options, Events],

	options: {
		toImage: null,
		toSuffix: '-over',
		preload: true,
		backgroundColor: '#fff',
		fx: {
			duration: 500,
			link: 'cancel'
		}
	},

	element: null,
	cover: null,
	fx: null,
	imageOver: null,

	initialize: function(element, options)
	{
		this.setOptions(options);
		this.element = $(element);

		if(this.element.get('tag') == 'div')
		{
			if(this.imageOver = this.element.getStyle('background-image').match(/url\("?([a-zA-Z0-9-\.\:\/]+)"?\)/))
				this.imageOver = this.imageOver[1];
		}
		else if(this.element.get('tag') == 'img')
			this.imageOver = this.element.get('src');

		if(this.options.toImage)
			this.imageOver = this.options.toImage;
		else
		{
			this.imageOver = this.imageOver.split('.');
			var Ext = this.imageOver.pop();
			this.imageOver = this.imageOver.join('.') + this.options.toSuffix + '.' + Ext;
		}

		if(this.options.preload)
			new Asset.image(this.imageOver);

		this.cover = new Element('div', {
			styles: {
				position: 'absolute',
				top: 0,
				left: 0,
				width: this.element.getSize().x,
				height: this.element.getSize().y,
				'background-image': 'url("' + this.imageOver + '")',
				'background-color': this.options.backgroundColor,
				opacity: 0,
				'z-index': this.element.getStyle('z-index').toInt() + 1
			}
		});

		if(this.element.get('tag') == 'div')
		{
			this.cover.inject(this.element);
			if(this.element.getStyle('position'))
				this.element.setStyle('position', 'relative');
		}
		else if(this.element.get('tag') == 'img')
		{
			$(document.body).adopt(this.cover);
		}

		this.fx = new Fx.Tween(this.cover, this.options.fx);

		this.element.addEvent('mouseenter', function(){
				this.fireEvent('startShow');
				this.show();
				this.fireEvent('show');
			}.bind(this));

		if(this.element.get('tag') == 'div')
		{
			this.element.addEvent('mouseleave', function(){
				this.fireEvent('startHide');
				this.hide();
				this.fireEvent('hide');
			}.bind(this));
		}
		else if(this.element.get('tag') == 'img')
		{
			this.element.addEvents({
				load: function(){
					this.cover.setPosition({
						x: this.element.getPosition($(document.body)).x,
						y: this.element.getPosition($(document.body)).y
					});

					this.cover.setStyles({
						width: this.element.getSize().x,
						height: this.element.getSize().y
					});

				}.bind(this),
				mouseleave: function(){
					if(this.cover.getStyle('opacity') == 0)
						this.cover.fireEvent('mouseleave');
				}.bind(this)
			});

			this.cover.addEvent('mouseleave', function(){
				this.fireEvent('startHide');
				this.hide();
				this.fireEvent('hide');
			}.bind(this));
		}
	},

	show: function()
	{
		this.fx.start('opacity', 1);
	},

	hide: function()
	{
		this.fx.start('opacity', 0);
	}
});

Element.implement({
	crossfade: function(options)
	{
		return new Zweer.Crossfade(this, options);
	}
});