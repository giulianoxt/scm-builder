Event.observe('readFormula', 'click', setFormula);
Event.observe('formReadFormula', 'submit', setFormula);

function setFormula() {   
    if( validFrm('formReadFormula') ){
        var cont = $('cont').innerHTML;
        var str = $('formula').value;
        str = str.replace(/&/,"^");
        var pars = "formula="+str+"&action=readFormula&cont="+cont;
        //updateWindow('transfer' , '../../modules/controller.php', pars);
        new Ajax.Request('http://www.dimap.ufrn.br/root_logicamente/modules/controller.php',{
                    method: 'post', parameters: pars, onFailure: reportError             
                     });
        var formula = $('formula').value;
        new Insertion.Bottom('transfer', '<div id = "f'+cont+'">' + formula + '</div>');
        new Insertion.Bottom('transfer', '<button id="b'+cont +'">Delete</button>');
        Event.observe('b'+cont, 'click', del.bind(this, cont));
        Event.observe ('f'+cont, 'click', sel.bind(this, cont));
        cont++;
        $('cont').innerHTML = cont;
    }
}

function del(id){
	$('f'+ id).remove();
	$('b'+ id).remove();
	var pars = "&action=delFormula&form=f"+id;
	new Ajax.Request('http://www.dimap.ufrn.br/root_logicamente/modules/controller.php',{
					method: 'post', parameters: pars, onFailure: reportError			 
					 });
}

function sel(id){	
	var listMenu = $('transfer').getElementsByTagName('div');
	var node = $A(listMenu);
	
	//adiciona class
	$('f'+ id).addClassName('selected');
	//remove class das oturas
	var elmBotao = $('f'+ id);
	node.without(elmBotao).each(function(node){
		node.removeClassName('selected');
	});

	var pars = "&action=selFormula&id="+id;
	new Ajax.Request('http://www.dimap.ufrn.br/root_logicamente/modules/controller.php',{
					method: 'post', parameters: pars, onFailure: reportError

		});
}
