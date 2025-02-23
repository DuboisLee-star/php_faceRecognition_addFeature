<?php	
ini_set('error_reporting', E_ERROR); 
register_shutdown_function("fatal_handler"); 
function fatal_handler() { 
$error = error_get_last(); 
echo("<pre>"); 
print_r($error); 
}

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
	include_once ("../config/assinatura1.php");
	include_once ("../config/texto.php");

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

	// Valida se existe um id e se ele é numérico
	if (!empty($id_cliente) && is_numeric($id_cliente)):

		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql = 'SELECT *  FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);
		
		// Faz a conexão con o banco
        $conexao = conexao::getInstance();
        $sql = 'SELECT * FROM info_clube WHERE id = :id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', 1);
        $stm->execute();
        $clube = $stm->fetch(PDO::FETCH_OBJ);	

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
                                       DECLARAÇÃO DE FILIAÇÃO
****************************************************************************************************
-->

<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h4 style="text-align: center;">DECLARAÇÃO DE FILIAÇÃO A ENTIDADE DE TIRO</h4><font size="2"><br><b>ANEXO B</b><br>(Inciso XVII do art. 2º do Decreto nº 11.615/2023)<br><br>DADOS DA ENTIDADE DE TIRO DECLARANTE<BR>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b><font size="2">Nome:</b></td>
        <td width="50%" valign="top"><font size="2">'.mb_strtoupper($clube->clube_nome).'</font></td>
        <td width="25%" valign="top"><b><font size="2">CNPJ:</font></b><font size="2"> '.$clube->clube_cnpj.'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Certificado de Registro</b></td>
        <td width="50%" valign="top"><font size="2">'.$clube->clube_cr.'</font></td>
        <td width="25%" valign="top"><b><font size="2">Data:</font></b><font size="2"> '. ($clube->clube_validade_cr ? date('d/m/Y', strtotime($clube->clube_validade_cr)) : ''). '</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Endereço</b></td>
        <td width="75%" colspan="2" valign="top"><font size="1">'.mb_strtoupper($clube->clube_endereco).'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Dados de Filiação</b></td>
        <td width="50%" valign="top"><b>Numero:</b> '.$cliente->matricula.'</font></td>   
        <td width="25%" valign="top"><b><font size="2">Data:</font></b><font size="2"> '. ($cliente->data_filiacao ? date('d/m/Y', strtotime($cliente->data_filiacao)) : '') . '</font></td>
    </tr>     
</table>

<br>
<div align="center">DADOS DO REQUERENTE</div>


<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b><font size="2">Nome:</b></td>
        <td width="50%" valign="top"><font size="2">'.mb_strtoupper($cliente->nome).'</font></td>
        <td width="25%" valign="top"><b><font size="2">CPF:</font></b><font size="2"> '.$cliente->cpf.'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Certificado de Registro</b></td>
        <td width="50%" valign="top"><font size="2">'.$cliente->cr.'</td>
        <td width="25%" valign="top"><b><font size="2">Data:</font></b><font size="2"> '. ($cliente->validade_cr ? date('d/m/Y', strtotime($cliente->validade_cr)) : ''). ' </font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Endereço:</b></td>
        <td width="75%" colspan="2" valign="top"><font size="1">'.mb_strtoupper($cliente->rua).' nº '.$cliente->numero.', '.mb_strtoupper($cliente->complemento).' '.mb_strtoupper($cliente->bairro).', CEP '.$cliente->cep.' - '.mb_strtoupper($cliente->cidade).'/'.mb_strtoupper($cliente->siglauf).'</font></td>
    </tr>
</table>
	
<div align="justify" style="line-height: 170%; margin-left: 10; margin-right: 10"><br>
O <b>'.mb_strtoupper($clube->clube_nome).'</b>, DECLARA,  para fim de comprovação de filiação nos termos do contido no inciso XVII do Art. 2º do Decreto nº 11.615, de 21 de julho de 2023, e sob as penas da lei, que o(a) cidadão(ã), <B>'.mb_strtoupper($cliente->nome).'</b>, acima identificado, pertence aos quadros desta Entidade, com matricula <B>'.$cliente->matricula.'</b> em <b>'. ($cliente->data_filiacao ? date('d/m/Y', strtotime($cliente->data_filiacao)) : '') . '</b> e conforme os dados de filiação acima descritos.</div><br>

<div align=justify style="line-height: 140%; margin-left: 5; margin-right: 5">Esta declaração tem validade de 90 dias.<br>

'.PDF_ASSINA.'

	');
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	
	//Exibibir a página
	$dompdf->stream(
		"declaraccao_filiacao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>