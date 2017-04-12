<?php
//Laravel DataBase
use WHMCS\Database\Capsule;
//Bloqueia o acesso direto ao arquivo
if (!defined("WHMCS"))
	{
	 die("Acesso restrito!");
	}
	//Cria o Hook
	function maskara($vars) {
    		//Pegando URL do sistema no banco
    		foreach (Capsule::table('tblconfiguration')->WHERE('setting', 'SystemURL')->get() as $system){
	    		$urlsistema = $system->value;
			}
		//Pegando informações da tabela do módulo.
		/** @var stdClass $mask */
		foreach (Capsule::table('mod_maskara')->get() as $mask){
		    $cpfcampo = $mask->cpf;
		    $nascimentocampo = $mask->data_nascimento;
		    $cnpjcampo = $mask->cnpj;
		}
		//Criando o Javascript
		$javascript  = '';
		//Chamando o Jquery da Mascara
		$javascript .= '<script type="text/javascript" src="'.$urlsistema.'/modules/addons/maskara/jquery.maskedinput.min.js"></script><script src="'.$urlsistema.'/modules/addons/maskara/cep.js"></script>';
		//Verifica se o campo é o mesmo do CPF X CNPJ
		if($cpfcampo==$cnpjcampo){
			//Chamando as mascaras
			$javascript .= '<script type="text/javascript">jQuery(function($){ ';
			//Data de Nascimento
			$javascript .= '$("#customfield'.$nascimentocampo.'").mask("99/99/9999", {placeholder: "dd/mm/aaaa"}); ';
			//Telefone
			$javascript .= '$("#phonenumber").mask("(99) 9999-99999"); ';
			$javascript .= '$("#inputPhone").mask("(99) 9999-99999"); ';
			//CEP
			$javascript .= '$("#postcode").mask("99999-999"); ';
			$javascript .= '$("#inputPostcode").mask("99999-999"); ';
			//Fechando Jquery das mascaras
			$javascript .= ' });</script>';
			//CPF CNPj mesmo campo
			$javascript .= '<script>jQuery(function($){$("#customfield'.$cpfcampo.'").focus(function(){$(this).unmask();$(this).val($(this).val().replace(/\D/g,""));}).click(function(){$(this).val($(this).val().replace(/\D/g,"")).unmask();}).blur(function(){if($(this).val().length==11){$(this).mask("999.999.999-99");}else if($(this).val().length==14){$(this).mask("99.999.999/9999-99");}});});</script>';
		}
		else{
			//Chamando as mascaras
			$javascript .= '<script type="text/javascript">jQuery(function($){ ';
			//CPF
			$javascript .= '$("#customfield'.$cpfcampo.'").mask("999.999.999-99"); ';
			//CNPJ
			$javascript .= '$("#customfield'.$cnpjcampo.'").mask("99.999.999/9999-99"); ';
			//Data de Nascimento
			$javascript .= '$("#customfield'.$nascimentocampo.'").mask("99/99/9999", {placeholder: "dd/mm/aaaa"}); ';
			//Telefone
			$javascript .= '$("#phonenumber").mask("(99) 9999-99999"); ';
			$javascript .= '$("#inputPhone").mask("(99) 9999-99999"); ';
			//CEP
			$javascript .= '$("#postcode").mask("99999-999"); ';
			$javascript .= '$("#inputPostcode").mask("99999-999"); ';
			//Fechando Jquery das mascaras
			$javascript .= ' });</script>';
		}
		
		//Retorna o Javascript
		return $javascript;
	}
	//Adicionando o hook as páginas
	add_hook("ClientAreaFooterOutput",1,"maskara");
?>