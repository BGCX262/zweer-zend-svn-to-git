window.addEvent('domready', function(){

	$('link').addEvent('click', function(){
		new Zweer.Dialog.Alert('Cliccato');
	});
});