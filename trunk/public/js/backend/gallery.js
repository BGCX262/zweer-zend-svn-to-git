window.addEvent('domready', function(){

    if($('url'))
        new Form.Upload('url', {
            onComplete: function(Text){
                if(Text.substr(0, 1) != '<')
                    alert(Text);

                window.location.href = window.location.href;
            }
        });

});