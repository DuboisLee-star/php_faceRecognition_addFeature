<?php	

	//**************************************************
	// função para formatar CPF e CNPJ | Tiago Moselli
	//**************************************************
	
	function formataCPFCNPJ($value){
		$cnpj_cpf = preg_replace("/\D/", '', $value);
		if(strlen($cnpj_cpf) === 11) {
			return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
		} 
		return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
	}
	$meses = array(
		'01' => 'Janeiro',
		'02' => 'Fevereiro',
		'03' => 'Março',
		'04' => 'Abril',
		'05' => 'Maio',
		'06' => 'Junho',
		'07' => 'Julho',
		'08' => 'Agosto',
		'09' => 'Setembro',
		'10' => 'Outubro',
		'11' => 'Novembro',
		'12' => 'Dezembro'
	);
	
	//**************************************************
	include_once("../config/config.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura2.php");	
	
	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
	
	// Valida se existe um id e se ele é numérico
	if (!empty($id_cliente) && is_numeric($id_cliente)):
	    
		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);
		if(!empty($cliente)):
		    
			// Formata a data no formato nacional
			$array_data     = explode('-', $cliente->data_nascimento);
			$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];
		endif;
	endif;
	
       $html = '<table border=1>';	
	   $html .= '<tr><td>'.$cliente->matricula.'</td></tr>';
	$html .= '</tbody>';
	$html .= '</table>';
	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;
	
	// include autoloader
	require_once("dompdf/autoload.inc.php");
	
	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true));
	
	// Carrega seu HTML
	$endereco_sec = '';
	if (!empty($cliente->rua) || !empty($cliente->num) || !empty($cliente->bairro) || !empty($cliente->cep) || !empty($cliente->cidade) || !empty($cliente->siglauf)) {
		$endereco_sec = utf8_decode($cliente->rua).' n&#176; '.utf8_decode($cliente->num).', '. utf8_decode($cliente->bairro) .' Cep: '.utf8_decode($cliente->cep).' - '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf);
	} else {
		$endereco_sec = 'NÃO CONSTA';
	}

	$dompdf->load_html(utf8_encode('
<!--
*****************************************************************************************************
                                DECLARAÇÃO DE SEGURANÇA DE GUARDA DO ACERVO
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<br><br><br><Br>
<h3 style="text-align: center;">ANEXO A</h3><center>DECLARA&Ccedil;&Atilde;O DE SEGURAN&Ccedil;A DO ACERVO (DSA)<br><br><br>
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
EU, <B>'.mb_strtoupper(utf8_encode($cliente->nome)).'</b>, nacionalidade <B>'.mb_strtoupper(utf8_encode($cliente->nacionalidade)).'</b>, natural de <B>'.mb_strtoupper(utf8_encode($cliente->naturalidade)).'</b>, nascido em <b>'. ($cliente->data_nascimento ? date('d/m/Y', strtotime($cliente->data_nascimento)) : '') . '</b>, profiss&atilde;o <B>'.mb_strtoupper(utf8_encode($cliente->profissao)).'</b>, estado civil <B>'.mb_strtoupper(utf8_encode($cliente->estadocivil)).'</b>, residindo em <B>'.mb_strtoupper(utf8_encode($cliente->segundo_rua)).'</b> n&#176; <B>'.$cliente->segundo_num.'</b>, bairro <B>'.mb_strtoupper(utf8_encode($cliente->segundo_bairro)).'</b>, cep: <B>'.$cliente->segundo_cep.'</b>, <B>'.strtoupper(utf8_encode($cliente->segundo_cidade)).'</b>/<B>'.mb_strtoupper(utf8_encode($cliente->segundo__estado)).'</b> e CPF n&#176; <B>'.formataCPFCNPJ($cliente->cpf).'</b>. DECLARO, para fim de <b>CONCESS&Atilde;O, REVALIDA&Ccedil;&Atilde;O DE REGISTRO NO COMANDO DO EX&Eacute;RCITO OU APOSTILAMENTO</b>, que o <u>segundo local de guarda</u> do meu acervo de <B>'.mb_strtoupper(utf8_encode($cliente->categoria)).'</b> possui cofre ou lugar seguro, com tranca, para armazenamento das armas de fogo desmuniciadas de que sou propriet&aacute;rio, e de que adotarei as medidas necess&aacute;rias para impedir que menor de dezoito anos de idade ou pessoa civilmente incapaz se apodere de arma de fogo sob minha posse ou de minha propriedade, observado o disposto no art. 13 da Lei n&#176;	10.826, de 2003.
<br><br><br>
<font size="2"><b>PRIMEIRO ENDERE&Ccedil;O:</b> '.strtoupper(utf8_decode($endereco_sec)).'</font><br><br>

<table width="100%" style="border-collapse: collapse; margin-top: 5px;">
        <tr>
              <p align="center">'. $cliente->cidade .'/'. $cliente->siglauf.', '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br><br><br>
    
<!-- Requerente -->
____________________________________________<br>
REQUERENTE<br><b>
'.$cliente->nome.'</b><br>
CPF: '.formataCPFCNPJ($cliente->cpf).'
</div>
        </tr>
</table>
			
	'));
	
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	
	//Exibibir a página
	$dompdf->stream(
		"dsa2", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>