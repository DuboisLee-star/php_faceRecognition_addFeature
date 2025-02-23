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

	//**************************************************

	include_once ("../config/conecta_rel.php");
	include_once ("../config/cabecalho.php");

	$html = '<table border=1  cellspacing="0" cellpadding="0" width="100%"';	
	$html .= '<thead>';
	$html .= '<tr>';
    $html .= '<th bgcolor="#CCCCCC" width="25%"><div align="center">&nbsp;ATIRADOR</th>';
	$html .= '<th bgcolor="#CCCCCC" width="35%"><div align="center">&nbsp;ENDEREÇO</th>';
	$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="left">&nbsp;DOCUMENTOS</th>';
	$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="center">&nbsp;D.FILIAÇÃO</th>';
    $html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	$result_transacoes = "SELECT * from tab_membros order by matricula";

	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
	while($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)){

    $html .= '<tr><td width="25%"><div align="left">&nbsp;<font size="1"><b>'.$row_transacoes['matricula'] .'</b><BR>&nbsp;'.utf8_decode($row_transacoes['nome']) ."</td>";
    $html .= '<td width="35%"><div align="left">&nbsp;<font size="1">'.utf8_decode($row_transacoes['rua']) . ' '.$row_transacoes['numero'] . ' <BR>&nbsp;'.utf8_decode($row_transacoes['bairro']) . ' - CEP: '.$row_transacoes['cep'] . ' <BR>&nbsp;'.utf8_decode($row_transacoes['cidade']) . '/'.$row_transacoes['siglauf'] .   "</td>";
    $html .= '<td width="20%"><div align="left">&nbsp;<font size="1">CPF: '.$row_transacoes['cpf'] .'<BR>&nbsp;'.$row_transacoes['email'] . '<BR>&nbsp;'.$row_transacoes['telefone'] ."</td>";
    $html .= '<td width="20%"><div align="center"><font size="1">'.date('d/m/Y', strtotime($row_transacoes['data_filiacao'])) .  "</td>";		
    $html .= "</tr>";

	}

	$html .= '</tbody>';
	$html .= '</table';

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true));
	
	// Carrega seu HTML
	$dompdf->load_html(utf8_encode('

<!--
*****************************************************************************************************
                                  RELAÇÃO DE MEMBROS POR DATA DE FILIAÇÃO
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MEMBROS POR DATA DE FILIAÇÃO</h3>

'. $html .'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relacao_membros_data_filiacao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>