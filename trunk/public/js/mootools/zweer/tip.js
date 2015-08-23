if(!window.Zweer) var Zweer = {};

/**
 * Classe che crea dei tooltip flottanti quando si passa sopra a un link o a un elemento
 * @author Niccolò Olivieri <flicofloc@gmail.com>
 */
Zweer.Tip = new Class({
    Implements: [Options, Events],

    options: {
        position: 'top',
        center: true,
        content: 'title',
        html: false,
        baloon: true,
        arrowSize: 6,
        arrowOffset: 6,
        distance: 3,
        motion: 6,
        motionOnShow: true,
        motionOnHide: true,
        showDelay: 0,
        hideDelay: 0,
        theme: 'zweer_tip',
        offset: {
            x: 0,
            y: 0
        },
        fx: {
            duration: 'short'
        }
    },

    initialize: function(elements, options)
    {
        this.setOptions(options);
        if(!['top', 'right', 'bottom', 'left'].contains(this.options.position))
            this.options.position = 'top';

        if(elements)
            this.attach(elements);

        return this;
    },

    attach: function(elements)
    {
        var that = this;
        $$(elements).each(function(element){
            element.addEvents({
                'mouseenter': function(){
                    that.show(this);
                },
                'mouseleave': function(){
                    that.hide(this);
                }
            });
        });
    },

    show: function(element)
    {
        var Old = element.retrieve('zweer_tip');
        if(Old)
            if(Old.getStyle('opacity') == 1)
            {
                clearTimeout(Old.retrieve('zweer_tip_timeout'));
                return;
            }

        var Tip = this.create(element);
        element.store('zweer_tip', Tip);
        this.animate(Tip, 'in');
        this.fireEvent('show', [Tip, element]);

        return this;
    },

    hide: function(element)
    {
        var Tip = element.retrieve('zweer_tip');
        if(!Tip)
            return;

        this.animate(Tip, 'out');
        this.fireEvent('hide', [Tip, element]);

        return this;
    },

    create: function(element)
    {
        if(this.options.content == 'title')
        {
            this.options.content = 'floating_title';
            if(!element.get(this.options.content))
                element.setProperty(this.options.content, element.get('title'));
            element.set('title', '');
        }

        var Content = typeof(this.options.content) == 'string' ? element.get(this.options.content) : this.options.content(element);
        var Cwr = new Element('div', { 'class': this.options.theme, styles: { margin: 0 } });
        var Tip = new Element('div', { 'class': this.options.theme + '_wrapper', styles: { margin: 0, padding: 0, 'z-index': Cwr.getStyle('z-index') } }).adopt(Cwr);

        if(Content)
        {
            if(this.options.html)
                Cwr.set('html', typeof(Content) == 'string' ? Content : Content.get('html'));
            else
                Cwr.set('text', Content);
        }

        var Body = document.id(document.body);
        Tip.setStyles({
            position: 'absolute',
            opacity: 0
        }).inject(Body);

        if(this.options.baloon && !Browser.ie6)
        {
            var Triangle = new Element('div', { 'class': this.options.theme + '_triangle', styles: { margin: 0, padding: 0 } });
            var TriangleStyle = {
                'border-color': Cwr.getStyle('background-color'),
                'border-width': this.options.arrowSize,
                'border-style': 'solid',
                width: 0,
                height: 0
            };

            switch(this.options.position)
            {
                case 'inside':
                case 'top':
                    TriangleStyle['border-bottom-width'] = 0;
                break;

                case 'right':
                    TriangleStyle['border-left-width'] = 0;
                    TriangleStyle['float'] = 'left';
                    Cwr.setStyle('margin-left', this.options.arrowSize);
                break;

                case 'bottom':
                    TriangleStyle['border-top-width'] = 0;
                break;

                case 'left':
                    TriangleStyle['border-right-width'] = 0;
                    if(Browser.ie7)
                    {
                        TriangleStyle['position'] = 'absolute';
                        TriangleStyle['right'] = 0;
                    }
                    else
                        TriangleStyle['float'] = 'right';
                    Cwr.setStyle('margin-right', this.options.arrowSize);
                break;
            }

            switch(this.options.position)
            {
                case 'inside':
                case 'top':
                case 'bottom':
                    TriangleStyle['border-left-color'] = TriangleStyle['border-right-color'] = 'transparent';
                    TriangleStyle['margin-left'] = this.options.center ? Tip.getSize().x / 2 - this.options.arrowSize : this.options.arrowOffset;
                break;

                case 'left':
                case 'right':
                    TriangleStyle['border-top-color'] = TriangleStyle['border-bottom-color'] = 'transparent';
                    TriangleStyle['margin-top'] = this.options.center ? Tip.getSize().y / 2 - this.options.arrowSize : this.options.arrowOffset;
                break;
            }

            Triangle.setStyles(TriangleStyle).inject(Tip, (this.options.position == 'top' || this.options.position == 'inside') ? 'bottom' : 'top');
        }

        var TipSize = Tip.getSize(),
            TriangleCoordinate = element.getCoordinates(Body),
            Position = {
                x: TriangleCoordinate.left + this.options.offset.x,
                y: TriangleCoordinate.top + this.options.offset.y
            };

        switch(this.options.position)
        {
            case 'inside':
                Tip.setStyles({
                    width: Tip.getStyle('width'),
                    heigth: Tip.getStyle('heigth')
                });

                element.setStyle('position', 'relative').adopt(Tip);

                Position = {
                    x: this.options.offset.x,
                    y: this.options.offset.y
                };
            break;

            case 'top':
                Position.y -= TipSize.y + this.options.distance;
            break;

            case 'right':
                Position.x += TriangleCoordinate.width + this.options.distance;
            break;

            case 'bottom':
                Position.y += TriangleCoordinate.height + this.options.distance;
            break;

            case 'left':
                Position.x -= TipSize.x + this.options.distance;
            break;
        }

        if(this.options.center)
            switch(this.options.position)
            {
                case 'top':
                case 'bottom':
                    Position.x += TriangleCoordinate.width / 2 - TipSize.x / 2;
                break;

                case 'left':
                case 'right':
                    Position.y += TriangleCoordinate.height / 2 - TipSize.y / 2;
                break;

                case 'inside':
                    Position.x += TriangleCoordinate.width / 2 - TipSize.x / 2;
                    Position.y += TriangleCoordinate.height / 2 - TipSize.y / 2;
                break;
            }

        Tip.set('morph', this.options.fx).store('zweer_tip_position', Position);
        Tip.setStyles({
            top: Position.y,
            left: Position.x
        });

        return Tip;
    },

    animate: function(Tip, Direction)
    {
        clearTimeout(Tip.retrieve('zweer_tip_timeout'));
        Tip.store('zweer_tip_timeout', function(TheTip){
            var DirectionIn = Direction == 'in',
                StyleEnd = {
                    opacity: DirectionIn ? 1 : 0
                };

            if((this.options.motionOnShow && DirectionIn) || (this.options.motionOnHide && !DirectionIn))
            {
                var Position = Tip.retrieve('zweer_tip_position');
                if(!Position)
                    return;

                switch(this.options.position)
                {
                    case 'inside':
                    case 'top':
                        StyleEnd['top'] = DirectionIn ? [Position.y - this.options.motion, Position.y] : Position.y - this.options.motion;
                    break;

                    case 'right':
                        StyleEnd['left'] = DirectionIn ? [Position.x + this.options.motion, Position.x] : Position.x + this.options.motion;
                    break;

                    case 'bottom':
                        StyleEnd['bottom'] = DirectionIn ? [Position.y + this.options.motion, Position.y] : Position.y + this.options.motion;
                    break;

                    case 'left':
                        StyleEnd['left'] = DirectionIn ? [Position.x - this.options.motion, Position.x] : Position.x - this.options.motion;
                }
            }

            TheTip.morph(StyleEnd);
            if(!DirectionIn)
                TheTip.get('morph').chain(function(){
                    this.dispose();
                }.bind(this));
        }).delay(Direction == 'in' ? this.options.showDelay : this.options.hideDelay, this, Tip);

        return this;
    }
});