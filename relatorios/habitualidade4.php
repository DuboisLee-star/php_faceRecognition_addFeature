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
	include_once ("../config/texto.php");

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
		
		$sql2 = "SELECT *
                 FROM tab_habitualidade
                 WHERE matricula = :matricula
                 AND IFNULL(aprovado, 0) = 1
                 AND DATE(data_aprovacao) = CURDATE()
                 ORDER BY data_aprovacao DESC";
                 
		$stm = $conexao->prepare($sql2);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->execute();
		$habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

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
	
// html habitualidade
$html_habilitualidade = "";
if ($habitualidade) {
    foreach ($habitualidade as $idh => $value) {
        // Verifica se a propriedade 'data' existe no objeto antes de utilizá-la
        $data_habitualidade = (isset($habitualidade[$idh]->data_aprovacao)) ? date('d/m/Y H:i', strtotime($habitualidade[$idh]->data_aprovacao)) : 'Data não disponível';

        $html_habilitualidade .= '
            <tr>
                <td width="30%">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->evento) . '</td>
                <td width="15%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->tipo) . '</td>
                <td width="12%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->modelo) . '</td>
                <td width="15%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->calibre) . '</td>                    
                <td width="15%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->sigma) . '</td>
                <td width="8%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->qtdemunicoes) . '</td>
            </tr>
        ';
    }
}

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true, 'enable_html5_parser'=>true));
	
	$dompdf->set_option('isHtml5ParserEnabled', true);

    // Obtenha a data mais recente dos dados de habitualidade
    $dataMaisRecente = isset($habitualidade[0]->data_aprovacao) ? date('d/m/Y H:i', strtotime($habitualidade[0]->data_aprovacao)) : 'Data não disponível';

	// Carrega seu HTML
	$dompdf->load_html(utf8_encode('
	
	
<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'
</div>

<h4 style="text-align: center;">HABITUALIDADE NA DATA DE: <font color="red">' . $dataMaisRecente . '</h4></font>

<div align=justify style="line-height: 135%; margin-left: 15; margin-right: 15"><FONT SIZE="2"><b>Associado:</b> '.utf8_decode($cliente->nome).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>CR:</b> '.utf8_decode($cliente->cr).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Validade CR:</b> '.date('d/m/Y', strtotime($cliente->validade_cr)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>RM:</b> 4ª RM<br><b>Endereço:</b> '.utf8_decode($cliente->rua).' nº '.utf8_decode($cliente->numero).', bairro '.utf8_decode($cliente->bairro).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).'<br><br></div>

<div align="center">
	<table border=1 cellspacing=0 cellpadding=5 width=100%>
		<tr>
	     	<td align="center" width="30%" bgcolor="#C0C0C0"><font size="2"><b>EVENTO</b></td>
			<td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>TIPO</b></td>
			<td align="center" width="17%" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
			<td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>			
			<td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
			<td align="center" width="8%" bgcolor="#C0C0C0"><font size="2"><b>QTDE</b></td>			
		</tr>
		'.$html_habilitualidade.'
	</table>
</div>
<br>
<div align=justify style="line-height: 135%; margin-left: 15; margin-right: 15"><FONT SIZE="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DECLARO, para fim de comprovação de habitualidade de prática de tiro desportivo junto ao Exército Brasileiro, que fiz, nesse dia, a(s)  habitualidade(s) acima mencionada(s).</p><br><br><br><br></div>


<div align="center" style="line-height: 120%; margin-left: 15; margin-right: 15">
<FONT SIZE="2">'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br><br>


________________________________________<br>
'.utf8_decode($cliente->nome).'<br><b>CR nº: </b> '.utf8_decode($cliente->cr).'<br>
</div>
</div>

			
		'));

	//Renderizar o html
	$dompdf->render();
	$pagesCount = $dompdf->get_canvas()->get_page_number();
	
	// -------------------------------------------------------
	// grava log
	// -------------------------------------------------------
	$sql = 'INSERT INTO tab_declaracoes 
		(matricula, relatorio, data_emissao, ip_emissao) VALUES
		(:matricula, :relatorio, :data_emissao, :ip_emissao)
	';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->bindValue(':relatorio', utf8_encode('Declaração Habitualidade'));
	$stm->bindValue(':data_emissao', date('Y-m-d H:i:s'));
	$stm->bindValue(':ip_emissao', $_SERVER['REMOTE_ADDR']);
	$stm->execute();
	// -------------------------------------------------------

	$documento = base64_encode($dompdf->output());
 
		//Exibibir a pÃ¡gina
	$dompdf->stream(
		"declaracao_habitualidade_assinar", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
	
	
?>