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
	include_once ("../config/assinatura.php");
	
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
	$dompdf->load_html('
<!--
*****************************************************************************************************
                        DECLARAÇÃO DE NÃO ESTAR RESPONDENDO INQUÉRITO CRIMINAL
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
<br><br><br>
<h3 style="text-align: center;">DECLARAÇÃO DE INEXISTÊNCIA DE INQUÉRITOS<br> POLICIAIS OU PROCESSOS CRIMINAIS</h3><br><br>
<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
Eu, <b>'.mb_strtoupper($cliente->nome).'</b>, '.mb_strtoupper($cliente->nacionalidade).', '.mb_strtoupper($cliente->profissao).', natural de '.mb_strtoupper($cliente->naturalidade).', nascido em '. ($cliente->data_nascimento ? date('d/m/Y', strtotime($cliente->data_nascimento)) : '') . ', filho de '.mb_strtoupper($cliente->pai).' e '.mb_strtoupper($cliente->mae).', residente e domiciliado a Rua '.mb_strtoupper($cliente->rua).', '.mb_strtoupper($cliente->numero).' – Bairro '.mb_strtoupper($cliente->bairro).' – '.mb_strtoupper($cliente->cidade).'/'.mb_strtoupper($cliente->siglauf).', CEP '.strtoupper($cliente->cep).', portador da cédula de identidade nº '.mb_strtoupper($cliente->identidade).', expedida em '.strtoupper($cliente->dataexped).' pela '.mb_strtoupper($cliente->orgaouf).' e inscrito no Cadastro de Pessoa Física sob nº '.formataCPFCNPJ($cliente->cpf).', DECLARO, sob as penas da lei, que não respondo a inquéritos policiais nem a processos criminais tanto no estado de domicilio quanto nos demais entes federativos, e estou ciente de que, em caso de falsidade ideológica, ficarei sujeito às sanções prescritas no Código Penal e às demais cominações legais aplicáveis tanto no estado de domicilio quanto nos outros entes federativos.<br><br>

<i>“Art.  299 - Omitir, em documento público ou particular, declaração que nele deveria constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre o fato juridicamente relevante. <br><br>

Pena - reclusão de 1 (um) a 5 (cinco) anos e multa, se o documento é público e reclusão de 1 (um) a 3 (três) anos, se o documento é particular.”</i>

<br><br>
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

	');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"DIC", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>