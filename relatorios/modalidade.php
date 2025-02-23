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
// 	include_once ("../config/assinatura4.php");
	include_once ("../config/assinatura7.php");
	include_once ("../config/texto.php");

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

	// Valida se existe um id e se ele � num�rico
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
                                         DECLARAÇÃO DE MODALIDADE E PROVA
*****************************************************************************************************
-->

<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">DECLARA&Ccedil;&Atilde;O DE MODALIDADE E PROVA</h3><br>

<div align=justify style="line-height: 120%; margin-left: 15; margin-right: 15"><font size="2">
'.PDF_TEXTO.' DECLARA, mediante solicitação de <B>'.strtoupper($cliente->nome).'</b>, CR <B>'.$cliente->cr.'</b>, está regularmente inscrito nesta entidade sob o nº  <b>'.$cliente->matricula.'</b>, datado de <b>'.date('d/m/Y', strtotime($cliente->data_filiacao)).'</b> e para fim de comprovação junto ao Exército Brasileiro, que promove, realiza ou sedia competições e provas de tiro desportivo, conforme quadro abaixo:<br><br>
                       
                      <div align="center">
                      <center>
                      <table border="1" width="100%">
                        <tr>
                           <td width="10%" align="center" bgcolor="#CCCCCC"><font size="2"><b>PROVA</b></p>
                           </td>
                           <td width="20%" align="center" bgcolor="#CCCCCC"><font size="2"><b>MODALIDADE</b></td>
                           <td width="60%" align="justify" bgcolor="#CCCCCC"><font size="2"><b>ARMAMENTO</b></td>
                       </tr>
                        <tr>
                           <td width="10%" align="center"><font size="2">Competição</td>
                           <td width="20%" align="center"><font size="2">Saque Rápido, Silhueta Metálica, IPSC, NRA, Duelo 20 segundos e SHOTGUN</td>
                           <td width="60%" align="justify"><font size="2"><b>Carabina:</b> .22, 17 HMR, 22LR, 38, 40 SW, 44 MAG , 5,56x45 e 7,62 , 308 , 223;<br><hr><b>Espingarda:</b> 12 Manual, 12 Semi-Automática, Astra 20 GA e Beretta 12 GA;<br><hr><b>Pistolas:</b> 22 LR .38 Super, . 38 Super Auto, .380, .380 ACP, .40, .40S&W, .45, .45 ACP, 10 mm Auto, 380 ACP (9mm curto), 38SA, 40, 40SW, 9mm, 9 mm Luger e 9mx21m;<br><hr><b>Revólveres:</b> 22,22 MAG, 22LR, .38, .38 SPL, 9MM e .45
						   </td>
                       </tr>
                       </table></p><Br>


<div align=left>Esta declaração tem validade de 90 dias.</p><br><br><br>

<div align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'

</p>

			
	');
	//Renderizar o html
	$dompdf->render();

	$documento = base64_encode($dompdf->output());
	require_once($_SERVER['DOCUMENT_ROOT'].'/icpbrasil/icpbrasil.class.php');
    $icp = new icpBrasil();
    $icp->assinar_documento($documento);
    $icp->output();
	
	//Exibibir a página
	$dompdf->stream(
		"dic", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>