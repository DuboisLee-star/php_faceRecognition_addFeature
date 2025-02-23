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
	include_once ("../config/assinatura2.php");
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
                                       DECLARAÇÃO DE COMPROMISSO
*****************************************************************************************************
-->

<br><br>
<h4 style="text-align: center;">DECLARAÇÃO DE COMPROMISSO DE PARTICIPAÇÃO<br>EM TREINAMENTOS E COMPETIÇÕES</h4><br><center><b>ANEXO C</b><br>(art. 35 do Decreto nº 11.615/2023)
<br><br><br>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b><font size="2">Nome:</b></td>
        <td width="50%" valign="top"><font size="2">'.mb_strtoupper($clube->clube_nome).'</font></td>
        <td width="25%" valign="top"><b><font size="2">CNPJ:</font></b><font size="2"> '.mb_strtoupper($clube->clube_cnpj).'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Certificado de Registro</b></td>
        <td width="50%" valign="top"><font size="2">'.$clube->clube_cr.'</font></td>
        <td width="25%" valign="top"><b><font size="2">Data:</font></b><font size="2"> '. ($clube->clube_validade_cr ? date('d/m/Y', strtotime($clube->clube_validade_cr)) : '') . '</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Endereço</b></td>
        <td width="75%" colspan="2" valign="top"><font size="1">'.mb_strtoupper($clube->clube_endereco).'</font></td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Dados de Filiação:</font></b></td>
        <td width="50%" valign="top"><b><font size="2">Numero:</b> </font><font size="2">'.$cliente->matricula.'</font></td>   
        <td width="25%" valign="top"><b><font size="2">Data:</b> </font><font size="2">'. ($cliente->data_filiacao ? date('d/m/Y', strtotime($cliente->data_filiacao)) : '') . '</font></td>
    </tr>     
</table>

<p align="justify" style="line-height: 160%; margin-left: 0; margin-right: 0">
Eu, <b>'.strtoupper($cliente->nome).'</b>, residente a <b>'.mb_strtoupper($cliente->rua).'</b> nº <b>'.$cliente->numero.'</b>, bairro: <b>'.mb_strtoupper($cliente->bairro).'</b>, CEP <b>'.$cliente->cep.'</b> - <b>'.mb_strtoupper($cliente->cidade).'</b>/<b>'.mb_strtoupper($cliente->siglauf).'</b>, CPF <b>'.formataCPFCNPJ($cliente->cpf).'</b>, identidade <b>'.$cliente->identidade.'</b>, D.Expedição <b>'. ($cliente->data_exped ? date('d/m/Y', strtotime($cliente->data_exped)) : '') . '</b>, filiado a Entidade de Tiro acima nomeada, ME COMPROMETO a comprovar, no mínimo, por calibre registrado, oito
treinamentos ou competições em Clube de Tiro, em eventos distintos, a cada doze meses, em cumprimento ao previsto no art. 35 do Decreto nº 11.615/2023, como requisitos de assiduidade em prática de Tiro Desportivo junto ao Exército Brasileiro.<br>

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

	$documento = base64_encode($dompdf->output());
	
	//Exibibir a página
	$dompdf->stream(
		"dc", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>