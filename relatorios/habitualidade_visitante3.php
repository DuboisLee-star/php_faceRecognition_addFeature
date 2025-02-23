<?php	
//ini_set('display_errors', true);
//error_reporting(-1);
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
	include_once ("../config/assinatura4.php");
	include_once ("../config/texto.php");

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

	// Valida se existe um id e se ele é numérico
	if (!empty($id_cliente) && is_numeric($id_cliente)):

		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		/*$sql = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);
		*/
		// pega dados da habitualidade
		$sql2 = " SELECT * FROM tab_habitualidade WHERE id = :id ORDER BY id ASC ";
		$stm = $conexao->prepare($sql2);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);
		
		if($habitualidade[0]->id === NULL){
		    if(strlen(trim($habitualidade[0]->id)) <= 0){
		        exit ('Sem registro.');
		    }
		}
		
		
		
		$sql3 = " SELECT * FROM tab_habitualidade WHERE cr_visitante = :cr_visitante ORDER BY id DESC LIMIT 8";
		$stm = $conexao->prepare($sql3);
		$stm->bindValue(':cr_visitante', $habitualidade[0]->cr_visitante);
		$stm->execute();
		$habitualidadeAll = $stm->fetchAll(PDO::FETCH_OBJ);
		
		
/*
		if(!empty($cliente)):

			// Formata a data no formato nacional
			$array_data     = explode('-', $cliente->data_nascimento);
			$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

		endif;
		*/

	endif;

        $html = '<table border=1>';	
		
		$html .= '<tr><td>'.$habitualidade[0]->cr_visitante.'</td></tr>';
	
	#$result_membros = "SELECT * FROM tab_membros order by matricula";
	#$resultado_membros = mysqli_query($conn, $result_membros);
	#while($row_membros = mysqli_fetch_assoc($resultado_membros)){
    #    
    #        	$html .= '<tr><td>'.$row_membros['matricula'] . "</td>";
	#}
	
	$html .= '</tbody>';
	$html .= '</table>';
	
	// html habitualidade
	$html_habitualidade = "";
	if($habitualidadeAll){
		foreach($habitualidadeAll as $idh => $value){
			$html_habitualidade .= '
				<tr>
					<td width="17%" align="center">&nbsp;'.date('d/m/Y H:i', strtotime($habitualidadeAll[$idh]->datacadastro)).'</td>
					<td width="53%" align="center">&nbsp;'.utf8_decode($habitualidadeAll[$idh]->tipo).' | '.utf8_decode($habitualidadeAll[$idh]->modelo).' | '.utf8_decode($habitualidadeAll[$idh]->calibre).' | '.utf8_decode($habitualidadeAll[$idh]->sigma).'</td>
					<td width="30%" align="center">&nbsp;'.utf8_decode($habitualidadeAll[$idh]->evento).' | '.utf8_decode($habitualidadeAll[$idh]->qtdemunicoes).' munições</td>
				</tr>
			';
		}
	}

	
	
	//exit($html_habitualidade);
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
                                       DECLARAÇÃO DE HABITUALIDADE 2
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'

<br><br><br><br>
<h4 style="text-align: center;"><FONT SIZE="3">D E C L A R A Ç Ã O</h4><br>

<p align=justify style="line-height: 160%; margin-left: 15; margin-right: 15"><FONT SIZE="3">Declaramos para os devidos fins que <B>'.utf8_decode($habitualidade[0]->nome_visitante).'</b>, portador do CPF: <B>'.utf8_decode($habitualidade[0]->cpf_visitante).'</b>, fará uso do estande de tiro do <b>TIGER CLUBE DE TIRO</b>, INSCRITO NO CNPJ/MF: 44.961.166/0001-40 E CERTIFICADO DE REGISTRO Nº CR 799827, COM SEDE NO SÍTIO TIGRE 
Nº 29, ZONA RURAL - CEP 59.908-000 - SÃO FRANCISCO DO OESTE/RN, em: <B>'.utf8_decode($habitualidade[0]->data).'.</b><br><br>

<p align=justify style="line-height: 110%; margin-left: 05; margin-right: 05">Para que esta declaração surta o efeito desejado, dato e assino.</p><br><br>

<p align=center style="line-height: 100%; margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'
</p>'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(

		"declaracao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);

?>