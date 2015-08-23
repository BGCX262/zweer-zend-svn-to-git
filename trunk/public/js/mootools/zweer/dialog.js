if(!window.Zweer) var Zweer = {};

/**
 * Classe che ridisegna alert(), prompt() e confirm() con uno stile milkbox-like
 * @author Niccolò Olivieri <flicofloc@gmail.com>
 * @requires Zweer.Mask
 */
Zweer.Dialog = new Class({
	Implements: [Options, Events],

	options: {
		inject: null,
		theme: 'zweer_dialog',
		title: null,
		scroll: true, // IE
		forceScroll: false,
		useEscKey: true,
		destroyOnHide: false,
		autoOpen: true,
		closeButton: true,

		duration: 500,
		hideOnClick: true,

		onInitialize: function(wrapper){
			this.fx = new Fx.Tween(wrapper, {
				property: 'opacity',
				duration: this.options.duration
			}).set(0);

			this.mask = new Zweer.Mask(this.options.inject, {
				hideOnClick: this.options.hideOnClick,
				destroyOnHide: this.options.destroyOnHide,
				fx: {
					duration: this.options.duration
				}
			});

			if(this.options.hideOnClick) this.mask.addEvent('click', this.close.bind(this));
		},
		onBeforeOpen: function(){
			this.mask.show();
			this.fx.start(1).chain(function(){
				this.fireEvent('show');
			}.bind(this));
		},
		onBeforeClose: function(){
			this.mask.hide();
			this.fx.start(0).chain(function(){
				this.fireEvent('hide');
			}.bind(this));
		}/*,
		onOpen: function(){},
		onClose: function(){},
		onShow: function(){},
		onHide: function(){}*/
	},

	wrapper: null,
	content: null,
	title: null,
	closeButton: null,

	opened: false,

	fx: null,
	mask: null,

	initialize: function(options)
	{
		this.setOptions(options);
		if(!this.options.inject)
			this.options.inject = document.body;

		this.wrapper = new Element('div', { 'class': this.options.theme }).inject(this.options.inject);
		this.content = new Element('div', { 'class': this.options.theme + '_content' }).inject(this.wrapper);

		if(this.options.title)
		{
			this.title = new Element('div', { 'class': this.options.theme + '_title', text: this.options.title }).inject(this.wrapper);
			this.wrapper.addClass(this.options.theme + '_withTitle');
		}

		if(this.options.closeButton)
			this.closeButton = new Element('a', { 'class': this.options.theme + '_close', events: { click: this.close.bind(this) } }).inject(this.wrapper);

		if(this.options.scroll && Browser.ie6 || this.options.forceScroll)
		{
			this.wrapper.setStyle('position', 'absolute');
			var Position = this.wrapper.getPosition(this.options.inject);
			window.addEvent('scroll', function(){
				var Scroll = document.getScroll();
				this.wrapper.setPosition({
					x: Position.x + Scroll.x,
					y: Position.y + Scroll.y
				});
			});
		}

		if(this.options.useEscKey)
			document.addEvent('keydown', function(e){
				if(e.key == 'esc')
					this.close();
			}.bind(this));

		this.addEvent('hide', function(){
			if(this.options.destroyOnHide)
				this.destroy();
		}.bind(this));

		this.fireEvent('initialize', this.wrapper);
	},

	setContent: function()
	{
		var Content = Array.from(arguments);

		if(Content.length == 1)
			Content = Content[0];

		this.content.empty();

		var Type = typeOf(Content);
		if(['string', 'number'].contains(Type))
			this.content.set('text', Content);
		else
			this.content.adopt(Content);

		return this;
	},

	open: function()
	{
		this.fireEvent('beforeOpen', this.wrapper).fireEvent('open');
		this.opened = true;
		return this;
	},

	close: function()
	{
		this.fireEvent('beforeClose', this.wrapper).fireEvent('close');
		this.opened = false;
		return this;
	},

	destroy: function()
	{
		this.wrapper.destroy();
	},

	toElement: function()
	{
		return this.wrapper;
	}
});

Zweer.Dialog.Alert = new Class({
	Extends: Zweer.Dialog,

	options: {
		destroyOnHide: true,

		okText: 'Ok',
		focus: true
	},

	initialize: function(message, options)
	{
		this.parent(options);

		var OkButton = new Element('input', {
			type: 'button',
			events: {
				click: this.close.bind(this)
			},
			value: this.options.okText
		});

		this.setContent(
				new Element('p', { 'class': this.options.theme + '_' + (this instanceof Zweer.Dialog.Error ? 'error' : 'alert'), text: message }),
				new Element('div', { 'class': this.options.theme + '_buttons' }).adopt(OkButton)
		);

		if(this.options.autoOpen)
			this.open();

		if(this.options.focus)
			this.addEvent('show', function(){
				OkButton.focus();
			});
	}
});

Zweer.Dialog.Error = new Class({
    Extends: Zweer.Dialog.Alert
});

Zweer.Dialog.Confirm = new Class({
    Extends: Zweer.Dialog,

    options: {
        destroyOnHide: true,

        okText: 'Ok',
        cancelTest: 'Cancel',
        focus: true
    },

    initialize: function(message, okFunction, cancelFunction, options)
    {
        this.parent(options);
        var emptyFn = function(){},
            that = this,
            Buttons = [
                { fn: okFunction || emptyFn, txt: this.options.okText },
                { fn: cancelFunction || emptyFn, txt: this.options.cancelTest }
            ].map(function(Button){
                return new Element('input', {
                    type: 'button',
                    events: {
                        click: function(){
                            Button.fn();
                            that.close();
                        }
                    },
                    value: Button.txt
                })
            });

        this.setContent(
                new Element('p', { 'class': this.options.theme + '_confirm', text: message }),
                new Element('div', { 'class': this.options.theme + '_buttons' }).adopt(Buttons)
        );

        if(this.options.autoOpen)
            this.open();

        if(this.options.focus)
            this.addEvent('show', function(){
                Buttons[1].focus();
            });
    }
});

Zweer.Dialog.Prompt = new Class({
    Extends: Zweer.Dialog,

    options: {
        okText: 'Ok',
        focus: true
    },

    initialize: function(message, returnFunction, options)
    {
        this.parent(options);

        if(!returnFunction)
            returnFunction = function(){};

        var textInput = new Element('input', { 'class': 'zweer_dialog_textInput', type: 'text' }),
            submitButton = new Element('input', { type: 'submit', value: this.options.okText }),
            formEvents = {
                submit: function(e){
                    e.stop();
                    returnFunction(textInput.get('value'));
                    this.close();
                }.bind(this)
            };

        this.setContent(
                new Element('p', { 'class': this.options.theme + '_prompt', text: message }),
                new Element('form', { 'class' : this.options.theme + '_buttons', events: formEvents }).adopt(textInput, submitButton)
        );

        if(this.options.autoOpen)
            this.open();

        if(this.options.focus)
            this.addEvent('show', function(){
                textInput.focus();
            });
    }
});

Zweer.Dialog.Iframe = new Class({
    Extends: Zweer.Dialog,

    options: {
        useScrollBar: true
    },

    initialize: function(url, options)
    {
        this.parent(options);

        this.setContent(
                new Element('iframe', {
                    src: url,
                    frameborder: 0,
                    scrolling: this.options.useScrollBar ? 'auto' : 'no'
                })
        );

        if(this.options.autoOpen)
            this.open();
    }
});

Zweer.Dialog.Request = new Class({
    Extends: Zweer.Dialog,

    requestOptions: null,

    initialize: function(url, requestOptions, options)
    {
        this.parent(options);
        this.requestOptions = requestOptions || {};

        this.addEvent('open', function(){
            var R = new Request.HTML(this.requestOptions).addEvent('success', function(text){
                this.setContent(text);
            }.bind(this)).send({
                        url: url
                    });
        }.bind(this));

        if(this.options.autoOpen)
            this.open();
    },

    setRequestOptions: function(options)
    {
        this.requestOptions = Object.merge(this.requestOptions, options);
        return this;
    }
});

Element.implement({
	ZweerDialog: function(options){
		this.store('ZweerDialog', new Zweer.Dialog(options).setContent(this).open());
		return this;
	},

    confirmLinkClick: function(message, options){
        this.addEvent('click', function(e){
            e.stop();

            new Zweer.Dialog.Confirm(message, function(){
                location.href = this.get('href');
            }.bind(this), null, options)
        });

        return this;
    },

    confirmFormSubmit: function(message, options){
        this.addEvent('submit', function(){
            e.stop();
            new Zweer.Dialog.Confirm(message, function(){
                this.submit();
            }.bind(this), null, options)
        });

        return this;
    }
});