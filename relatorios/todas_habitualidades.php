<?php	





//**************************************************


	// fun��o para formatar CPF e CNPJ | Tiago Moselli


	//**************************************************


	function formataCPFCNPJ($value){


		$cnpj_cpf = preg_replace("/\D/", '', $value);





		if(strlen($cnpj_cpf) === 11) {


			return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);


		} 





		return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);


	}





	//**************************************************





	


	include_once ("../config/cabecalho.php");


	include_once ("../config/assinatura.php");





	// Recebe o id do cliente do cliente via GET


	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';





	// Valida se existe um id e se ele � num�rico


	if (!empty($id_cliente) && is_numeric($id_cliente)):





		// Captura os dados do cliente solicitado


		$conexao = conexao::getInstance();


		$sql = 'SELECT * FROM tab_membros WHERE id = :id';


		$stm = $conexao->prepare($sql);


		$stm->bindValue(':id', $id_cliente);


		$stm->execute();


		$cliente = $stm->fetch(PDO::FETCH_OBJ);


		


	
		$matricula = $_GET['matricula'];
		$datainicial = isset($_GET['datainicial']) ? $_GET['datainicial'] : null;
		$datafinal = isset($_GET['datafinal']) ? $_GET['datafinal'] : null;
		

		$sql2 = "SELECT * FROM tab_habitualidade WHERE matricula = :matricula";
		
		
		if ($datainicial && $datafinal) {
			$sql2 .= " AND datacadastro BETWEEN :datainicial AND :datafinal";
		} elseif ($datainicial) {
			$sql2 .= " AND datacadastro >= :datainicial";
		} elseif ($datafinal) {
			$sql2 .= " AND datacadastro <= :datafinal";
		}
		

		$sql2 .= " ORDER BY datacadastro ASC";
		

		$stm = $conexao->prepare($sql2);
		

		$stm->bindParam(':matricula', $matricula);
		if ($datainicial) {
			$stm->bindParam(':datainicial', $datainicial);
		}
		if ($datafinal) {
			$stm->bindParam(':datafinal', $datafinal);
		}


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


	if($habitualidade){


		foreach($habitualidade as $idh => $value){


			$html_habilitualidade .= '


				<tr>


					<td width="5" align="center"><font size=2>'.date('d/m/Y', strtotime($habitualidade[$idh]->data)).'</td>	


					<td width="60">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->local).'</td>


					<td width="15">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->evento).'</td>


					<td width="25">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->tipo).'</td>


					<td width="25">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->modelo).'</td>


					<td width="3">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->calibre).'</td>


					<td width="3">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->sigma).'</td>


					<td width="2">&nbsp;<font size=2>'.utf8_decode($habitualidade[$idh]->qtdemunicoes).'</td>


				</tr>


			';


		}


	}





	


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


                                       DECLARA��O DE HABITUALIDADE


*****************************************************************************************************


-->





<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">


'.PDF_CABECALHO.'





<h4 style="text-align: center;">TODAS AS HABITUALIDADES DO ATIRADOR</h4><br>





<div align="center">


	<table border="1" width="100%" cellspacing="1">


		<tr>


			<td align="center" width="5" bgcolor="#C0C0C0"><b>DATA</b></td>


			<td align="center" width="60" bgcolor="#C0C0C0"><b>LOCAL</b></td>


			<td align="center" width="15" bgcolor="#C0C0C0"><b>EVENTO</b></td>


			<td align="center" width="25" bgcolor="#C0C0C0"><b>TIPO</b></td>


			<td align="center" width="25" bgcolor="#C0C0C0"><b>MODELO</b></td>


			<td align="center" width="3" bgcolor="#C0C0C0"><b>CALIBRE</b></td>


			<td align="center" width="3" bgcolor="#C0C0C0"><b>SIGMA</b></td>


			<td align="center" width="2" bgcolor="#C0C0C0"><b>QTDE</b></td>


		</tr>


		'.$html_habilitualidade.'


	</table>


</div>








<br><br><br>





<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15"></div>


<div align=center>_____________________________________________<br>


<div align="center">'.$cliente->nome.'</div>


<div align="center">'.$cliente->cpf.'</div>





		


		'));





	//Renderizar o html


	$dompdf->render();





	//Exibibir a página


	$dompdf->stream(


		"declaracao_8_habitualidades", 


		array(


			"Attachment" => false //Para realizar o download somente alterar para true


		)


	);


?>