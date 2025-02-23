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
	if (!empty($cliente->segundo_rua) || !empty($cliente->segundo_num) || !empty($cliente->segundo_bairro) || !empty($cliente->segundo_cep) || !empty($cliente->segundo_cidade) || !empty($cliente->segundo_estado)) {
		$endereco_sec = $cliente->segundo_rua.' nº '.utf8_decode($cliente->segundo_num).', '.$cliente->segundo_bairro.' Cep: '.utf8_decode($cliente->segundo_cep).' - '.$cliente->segundo_cidade.'/'.utf8_decode($cliente->segundo_estado);
	} else {
		$endereco_sec = 'NÃO CONSTA';
	}

	$dompdf->load_html('
<!--
*****************************************************************************************************
                                DECLARAÇÃO DE SEGURANÇA DE GUARDA DO ACERVO
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<br><br><br><Br>
<h3 style="text-align: center;">ANEXO A</h3><center>DECLARAÇÃO DE SEGURANÇA DO ACERVO (DSA)<br><br><br>
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
EU, <B>'.mb_strtoupper($cliente->nome).'</b>, nacionalidade <B>'.mb_strtoupper($cliente->nacionalidade).'</b>, natural de <B>'.mb_strtoupper($cliente->naturalidade).'</b>, nascido em <b>'. ($cliente->data_nascimento ? date('d/m/Y', strtotime($cliente->data_nascimento)) : '') . '</b>, profissão <B>'.mb_strtoupper($cliente->profissao).'</b>, estado civil <B>'.mb_strtoupper($cliente->estadocivil).'</b>, residindo em <B>'.mb_strtoupper($cliente->rua).'</b> nº <B>'.$cliente->numero.'</b>, bairro <B>'.mb_strtoupper($cliente->bairro).'</b>, cep: <B>'.$cliente->cep.'</b>, <B>'.mb_strtoupper($cliente->cidade).'</b>/<B>'.mb_strtoupper($cliente->siglauf).'</b> e CPF nº <B>'.formataCPFCNPJ($cliente->cpf).'</b>. DECLARO, para fim de <b>CONCESSÃO, REVALIDAÇÃO DE REGISTRO NO COMANDO DO EXÉRCITO OU APOSTILAMENTO</b>, que o  local de guarda do meu acervo de <B>'.mb_strtoupper($cliente->categoria).'</b> possui cofre ou lugar seguro, com tranca, para armazenamento das armas de fogo desmuniciadas de que sou proprietário, e de que adotarei as medidas necessárias para impedir que menor de dezoito anos de idade ou pessoa civilmente incapaz se apodere de arma de fogo sob minha posse ou de minha propriedade, observado o disposto no art. 13 da Lei nº 10.826, de 2003.
<br><br><br>
<font size="2"><b>SEGUNDO ENDEREÇO:</b> '.$endereco_sec.'</font><br><br>
<table width="100%" style="border-collapse: collapse; margin-top: 5px;">
          <p align="center">'. $cliente->cidade .'/'. $cliente->siglauf.', '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br><br><br>
    
<!-- Requerente -->
____________________________________________<br>
REQUERENTE<br><b>
'.$cliente->nome.'</b><br>
CPF: '.formataCPFCNPJ($cliente->cpf).'
</div>
</table>
			
	');
	
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	
	//Exibibir a página
	$dompdf->stream(
		"dsa", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>