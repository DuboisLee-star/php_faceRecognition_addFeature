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

	$html = '<table border=1  width="100%"';	
	$html .= '<thead>';
	$html .= '<tr>';
    $html .= '<th bgcolor="#CCCCCC" width="5%"><div align="center">&nbsp;FOTO</th>';
	$html .= '<th bgcolor="#CCCCCC" width="5%"><div align="center">&nbsp;MAT</th>';
	$html .= '<th bgcolor="#CCCCCC" width="55%"><div align="left">&nbsp;NOME</th>';
	$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center">&nbsp;CR</th>';
	$html .= '<th bgcolor="#CCCCCC" width="20%"><div align="center">&nbsp;VAL.CR</th>';
    $html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	$result_transacoes = "SELECT * from tab_membros WHERE bloqueio LIKE '%Nao%' OR plano_pgto LIKE '%M%' order by matricula";

	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
	while($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)){

    $html .= '<tr><td width="5%"><div align="center"><img src="https://sistema.hostmarq.com.br/fotos/'.$row_transacoes['foto'].'" height="30" width="30"></td>';
    $html .= '<td width="5%"><div align="center"><font size="2">&nbsp;'.$row_transacoes['matricula'] ."</td>";
    $html .= '<td width="55%"><div align="left"><font size="2">&nbsp;'.utf8_decode($row_transacoes['nome']) ."</td>";
    $html .= '<td width="15%"><div align="center"><font size="2">&nbsp;'.$row_transacoes['cr'] . "</td>";
    $html .= '<td width="20%"><div align="center"><font size="2">&nbsp;'.date('d/m/Y', strtotime($row_transacoes['validade_cr'])) .  "</td>";		
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
                                            RELAÇÃO DE MEMBROS
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">RELA&Ccedil;&Atilde;O DE MEMBROS ATIVOS</h3>

'. $html .'

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"relacao_membros", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>