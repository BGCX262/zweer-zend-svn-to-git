if(!window.Zweer) var Zweer = {};

Zweer.AutoRoll = new Class({
	Implements: Options,

	options: {
		overState: '-over',
		preload: true
	},

	element: null,

	initialize: function(element, options)
	{
		this.setOptions(options);
		this.element = $(element);

		if(this.element.get('tag') == 'img' || this.element.get('tag') == 'div')
		{
			var ImageFile;
			if(this.element.get('tag') == 'img')
				ImageFile = this.element.get('src');
			else
				if(ImageFile = this.element.getStyle('background-image').match(/url\("?([a-zA-Z0-9-\.\:\/]+)"?\)/))
					ImageFile = ImageFile[1];

			if(ImageFile)
			{
				ImageFile = ImageFile.split('.');
				this.element.store('zweer_autoRoll_ext', ImageFile.pop());
				this.element.store('zweer_autoRoll_file', ImageFile.join('.'));

				this.element.addEvents({
					mouseenter: function(){
						this.execute('over');
					}.bind(this),
					mouseleave: function(){
						this.execute();
					}.bind(this)
				});

				if(this.options.preload)
					new Asset.image(this.element.retrieve('zweer_autoRoll_file') + this.options.overState + '.' + this.element.retrieve('zweer_autoRoll_ext'));
			}
		}
	},

	execute: function(state)
	{
		state = (state == undefined) ? '' : this.options.overState;

		if(this.element.get('tag') == 'img')
			this.element.set('src', this.element.retrieve('zweer_autoRoll_file') + state + '.' + this.element.retrieve('zweer_autoRoll_ext'));
		else
			this.element.setStyle('background-image', 'url("' + this.element.retrieve('zweer_autoRoll_file') + state + '.' + this.element.retrieve('zweer_autoRoll_ext') + '")');
	}
});

Element.implement({
	autoRoll: function(options)
	{
		return new Zweer.AutoRoll(this, options);
	}
});