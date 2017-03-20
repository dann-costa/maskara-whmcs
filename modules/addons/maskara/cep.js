//Criado por Victor Hugo Scatolon de Souza | Versão 0.5 | CompuLabs.com.br
$(document).ready(function() {
	// -- Configurações --
	var esconder_campos = false;
	var loading_gif = "//i.imgur.com/q23p6oI.gif";
	// -------------------
	var pagina = window.location;
	pagina.toString().indexOf("register.php") >= 0 ? campos = ['postcode','address1','address2','city','stateselect'] : campos = ['inputPostcode','inputAddress1','inputAddress2','inputCity','stateselect'];
	$("head").append('<style>.cep-erro{border:solid 1px red}</style>')
	function ctrlcampo(op)
	{
		for (i = 1; i < campos.length; i++) {

		}
	}
	ctrlcampo(true);
	$("#postcode").after("<br><br><span class='btn btn-xs col-xs-12 col-sm-5 btn-primary btn-geo'>Preencher endereço automaticamente</span>");
	$("#"+campos[0]).change(function(event) {
		$("#"+campos[0]).after("<p style='margin-top:-33px;margin-left:90%;' id='cep-loading'><img src='"+loading_gif+"' /></p>");
		$.get("//ddd.pricez.com.br/cep/"+$("#"+campos[0]).val()+".json", function(data) {
			data.payload.logradouro != null ? $("#"+campos[1]).val(data.payload.logradouro) : $("#"+campos[1]).addClass('cep-erro');
			data.payload.bairro != null ? $("#"+campos[2]).val(data.	payload.bairro) : $("#"+campos[2]).addClass('cep-erro');
			data.payload.cidade != null ? $("#"+campos[3]).val(data.payload.cidade) : $("#"+campos[3]).addClass('cep-erro');
			data.payload.estado != null ? $("#"+campos[4]).val(data.payload.estado) : $("#"+campos[4]).addClass('cep-erro');
		}).fail(function() {
			alert("Ocorreu um erro ao buscar seu CEP. Tente novamente.");
		});
		ctrlcampo(false);
		$("#cep-loading").remove()
	});
	$("input").change(function(event){
		if($(this).attr('class').indexOf("cep-erro") >= 0){$("#"+$(this).attr('id')).removeClass('cep-erro');}
	});
	$("select").click(function(event) {
		if($(this).attr('readonly') == "true" || $(this).attr('readonly') == "readonly"){$("#"+campos[0]).focus();}
	});
});
