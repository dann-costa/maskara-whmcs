<?php
//Laravel DataBase
use WHMCS\Database\Capsule;

function maskara_config() {
	$configarray = array(
		'name' => 'Maskara',
		'description' => 'Módulo Maskara para WHMCS.',
		'version' => '0.1',
		'language' => 'portuguese-br',
		'author' => 'Daniel Costa - DC WEBSOLUTIONS',
		);
	return $configarray;
}

function maskara_activate($vars) {
    //Linguagem
	$LANG = $vars['_lang'];

    //Criando nova tabela
	Capsule::schema()->create('mod_maskara',
		function ($table) {
			/** @var \Illuminate\Database\Schema\Blueprint $table */
			$table->increments('id');
			$table->string('cpf');
			$table->string('data_nascimento');
			$table->string('cnpj');
		}
		);

	//Inserindo dados no banco de dados
	Capsule::connection()->transaction(
		function ($connectionManager)
		{
			/** @var \Illuminate\Database\Connection $connectionManager */
			$connectionManager->table('mod_maskara')->insert(['cpf' => 'nulo','data_nascimento' => 'nulo','cnpj' => 'nulo']);
		}
		);

    //Retorno
	return array('status'=>'success','description'=>'Módulo Maskara ativado com sucesso!');
	return array('status'=>'error','description'=>'Não foi possível ativar o módulo de Maskara por causa de um erro desconhecido');
}

function maskara_deactivate($vars) {
    //Linguagem
	$LANG = $vars['_lang'];

    //Remover Banco de Dados
	Capsule::schema()->drop('mod_maskara');

    //Retorno
	return array('status'=>'success','description'=>'Módulo de Maskara foi desativado com sucesso!');
	return array('status'=>'error','description'=>'Não foi possível desativar o módulo Maskara por causa de um erro desconhecido');
}

function maskara_output($vars) {

    //Linguagem
	$LANG = $vars['_lang'];

	//Salvando informações de configuração
	if($_GET['config']=='salvar'){
		try{
			$updatedUserCount = Capsule::table('mod_maskara')->update(['cpf' => $_POST['cpf'],'data_nascimento' => $_POST['data-nascimento'],'cnpj' => $_POST['cnpj'],]);
		    //Sucesso em salvar
			echo '<div class="alert alert-success"><strong>Alerta:</strong> Suas informações foram salvas com sucesso.</div>';
		}
		//Caso não conseguir, exibirá o erro
		catch (\Exception $e){
			echo '<div class="alert alert-danger"><strong>Alerta:</strong> Não foi possível salvar suas informações, erro: {$e->getMessage()}</div>';
		}
	}

    //Pegando informações da tabela do módulo.
	/** @var stdClass $mask */
	foreach (Capsule::table('mod_maskara')->get() as $mask){
		$cpfcampo = $mask->cpf;
		$nascimentocampo = $mask->data_nascimento;
		$cnpjcampo = $mask->cnpj;

	}

	?>

	<div class="row">
	<div role="main" class="col-md-4 col-sm-12 col-xs-12 pull-right">
		<!--Sobre-->

		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa fa-id-card-o" aria-hidden="true"></i> <?=$LANG['sobre'];?></div>
			<div class="panel-body">
				<p><strong><center>Módulo Maskara para WHMCS.</center></strong><br/>
					<strong>Criador:</strong> Daniel Costa - DC WEBSOLUTIONS<br/>
					<strong>Versão:</strong> <?php
					$versao = $vars['version'];
					$versaodisponivel = file_get_contents("http://lab.dcwebsolutions.com.br/versao-modulos/maskara.txt");
					if($versao==$versaodisponivel){
						echo ''.$versao = $vars['version'].'';
					}
					?> <br/>
					<strong>Data:</strong> 08/02/2017 <br/>
				</p>
			</div>
		</div>
		<!--Descrição-->
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-file-text" aria-hidden="true"></i> <?=$LANG['descricao'];?></div>
			<div class="panel-body">
				<p class="text-justify"><?=$LANG['descricao-texto'];?></p>
			</div>
		</div>
		<!--Verificar atualização-->
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-wrench" aria-hidden="true"></i> <?=$LANG['atualizacao'];?></div>
			<div class="panel-body">
				<?php
				$versao = $vars['version'];
				$versaodisponivel = file_get_contents("http://lab.dcwebsolutions.com.br/versao-modulos/maskara.txt");
				if($versao==$versaodisponivel){
					echo '<center><i class="fa fa-check-circle-o" aria-hidden="true"></i> '.$LANG["sucatualizacao"].'</center>';
				}
				else{
					echo '<center><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '.$LANG["erroatualizacao"].'<br/><a href="#" class="btn btn-danger"><i class="fa fa-download" aria-hidden="true"></i> '.$LANG["baixar"].'</a></center>';
				}

				?>
			</div>
		</div>
	</div>
	<aside role="complementary" class="col-md-8 col-sm-12 col-xs-12">
		<!-- Configurações-->
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-cogs" aria-hidden="true"></i> <?=$LANG["configuracao"];?></div>
			<div class="panel-body">
				<form action="addonmodules.php?module=maskara&config=salvar" method="POST">
					<div class="modal-body">
						<!--CPF Campo Customizado-->
						<div class="panel panel-primary">
							<div class="panel-heading"><?=$LANG['selecione-campo'];?> <b><?=$LANG['cpf'];?></b></div>
							<div class="panel-body">
								<select name="cpf" id="cpf" class="form-control">
									<?php
									$cpf_campo = '';
			    					//Pegando informações da tabela do módulo.
									/** @var stdClass $customfields */
									foreach (Capsule::table('tblcustomfields')->get() as $customfields) {
										$idfields = $customfields->id;
										$namefields = $customfields->fieldname;
										if($idfields==$cpfcampo){
											$cpf_campo .= '<option value="'.$idfields.'" selected="selected">'.$namefields.'</option>';
										}
										else{
											$cpf_campo .= '<option value="'.$idfields.'">'.$namefields.'</option>';
										}
									}

									//imprime os resultados
									echo $cpf_campo;
									?>
								</select>
							</div>
						</div>
						<!--CNPJ Campo Customizado-->
						<div class="panel panel-primary">
							<div class="panel-heading"><?=$LANG['selecione-campo'];?> <b><?=$LANG["cnpj"];?></b></div>
							<div class="panel-body">
								<select name="cnpj" id="cnpj" class="form-control">
									<?php
									$cnpj_campo = '';
			    					//Pegando informações da tabela do módulo.
									/** @var stdClass $customfields */
									foreach (Capsule::table('tblcustomfields')->get() as $customfields) {
										$idfields = $customfields->id;
										$namefields = $customfields->fieldname;
										if($idfields==$cnpjcampo){
											$cnpj_campo .= '<option value="'.$idfields.'" selected="selected">'.$namefields.'</option>';
										}
										else{
											$cnpj_campo .= '<option value="'.$idfields.'">'.$namefields.'</option>';
										}
									}

									//imprime os resultados
									echo $cnpj_campo;
									?>
								</select>
							</div>
						</div>
						<!--Data Nascimento Campo Customizado-->
						<div class="panel panel-primary">
							<div class="panel-heading"><?=$LANG['selecione-campo'];?> <b><?=$LANG["nascimento"];?></b></div>
							<div class="panel-body">
								<select name="data-nascimento" id="data-nascimento" class="form-control">
									<?php
									$datanascimento_campo = '';
			    					//Pegando informações da tabela do módulo.
									/** @var stdClass $customfields */
									foreach (Capsule::table('tblcustomfields')->get() as $customfields) {
										$idfields = $customfields->id;
										$namefields = $customfields->fieldname;
										if($idfields==$nascimentocampo){
											$datanascimento_campo .= '<option value="'.$idfields.'" selected="selected">'.$namefields.'</option>';
										}
										else{
											$datanascimento_campo .= '<option value="'.$idfields.'">'.$namefields.'</option>';
										}
									}

									//imprime os resultados
									echo $datanascimento_campo;
									?>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-success" value="Salvar">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?=$LANG['cancelar'];?></button>
						</div>
					</form>
				</div>
			</div>
</aside>
</div>
<?php } ?>

