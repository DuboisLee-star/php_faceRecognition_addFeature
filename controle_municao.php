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


	include_once("../config/conexao.php");
	
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
		
		$conexao = conexao::getInstance();
		$sql = "SELECT * FROM tab_compras WHERE matricula = :matricula ORDER BY compra_data";
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->execute();
		$compras = $stm->fetchAll(PDO::FETCH_OBJ);
		
		$conexao = conexao::getInstance();
		$sql = "SELECT * FROM tab_habitualidade WHERE matricula = :matricula ORDER BY data";
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':matricula', $cliente->matricula);
		$stm->execute();
		$habitualidades = $stm->fetchAll(PDO::FETCH_OBJ);

		if(!empty($cliente)):

			// Formata a data no formato nacional
			$array_data     = explode('-', $cliente->data_nascimento);
			$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

		endif;

	endif;
	
       $html = '<table border=1>';	
		
		$html .= '<tr><td>'.$cliente->matricula.'</td></tr>';
	
	#$result_membros = "SELECT * FROM tab_membros order by matricula";
	#$resultado_membros = mysqli_query($conn, $result_membros);
	#while($row_membros = mysqli_fetch_assoc($resultado_membros)){
    #    
    #        	$html .= '<tr><td>'.$row_membros['matricula'] . "</td>";
	#}
	
	$html .= '</tbody>';
	$html .= '</table>';

	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF();
	

	// Carrega seu HTML
	//$dompdf->load_html(utf8_encode('
	$pdf = '
	
<!--
*****************************************************************************************************
                                        CONTROLE DE MUNI��O
*****************************************************************************************************
-->

<div align="center"><b><h3>CONTROLE DE MUNI��ES E INSUMOS</h3></b><br>

<table border="1" width="100%" cellspacing="0">
	<tr>
		<td colspan="4">&nbsp;<b>Nome do(a) Atirador(a):</b> &nbsp;'.utf8_decode($cliente->nome).'</td>
		<td colspan="3">&nbsp;<b>CR: </b> &nbsp;'.utf8_decode($cliente->cr).'</td>
	</tr>
	<tr>
		<td colspan="7" bgcolor="#808080"><p align="center"><b>ENTRADA DE MUNI��ES</b></td>
	</tr>
	<tr>
		<td align="center" rowspan="3" bgcolor="#C0C0C0" width="22%">ORIGEM</td>
		<td align="center" rowspan="3" bgcolor="#C0C0C0" width="13%">DATA</td>
		<td align="center" rowspan="3" bgcolor="#C0C0C0" width="14%">DESCRI��O<br>ou NF</td>
		<td align="center" colspan="4">TIPO</td>
	</tr>
	<tr>
		<td align="center" colspan="2">MUNI��ES</td>
		<td align="center" colspan="2">INSUMOS</td>
	</tr>
	<tr>
		<td align="center" width="14%">CALIBRE</td>
		<td align="center" width="12%">QTDE</td>
		<td align="center" width="13%">ESP�CIE</td>
		<td align="center" width="9%">QTDE</td>
	</tr>';
	
	

	exit(print_r($compras));
	$array_compradas = array();
	if($compras){
		foreach($compras as $idx => $value){
			
			
			$pdf .= '
			<tr>
				<td align="center" width="22%">'.utf8_decode($compras[$idx]->compra_loja).'</td>
				<td align="center" width="13%">'.date('d/m/Y', strtotime($compras[$idx]->compra_data)).'</td>
				<td align="center" width="14%">'.$compras[$idx]->compra_nf.'</td>
				<td align="center" width="14%">'.$compras[$idx]->compra_calibre.'</td>
				<td align="center" width="12%">'.$compras[$idx]->compra_qtdecalibre.'</td>
				<td align="center" width="13%">'.$compras[$idx]->compra_insumos.'</td>
				<td align="center" width="9%">'.$compras[$idx]->compra_qtdeinsumos.'</td>
			</tr>';
			
			$array_compradas[$compras[$idx]->compra_calibre] = (int)$array_compradas[$compras[$idx]->compra_calibre] + $compras[$idx]->compra_qtdecalibre;
			
		}
	}
	
	$array_rel_compradas = $array_compradas;
	
	// print_r($array_compradas);exit();
	
	$pdf .= '
	</table>

<br><br>

<table border="1" width="100%" cellspacing="0">
	<tr>
		<td colspan="7" bgcolor="#C0C0C0"><p align="center"><b>SA�DA DE MUNI��ES</B></td>
	</tr>
	<tr>
		<td align="center" width="4%"><font size="2">N�</td>
		<td align="center" width="8%"><font size="2">DATA</td>
		<td align="center" width="37%"><font size="2">LOCAL</td>
		<td align="center" width="19%"><font size="2">EVENTO</td>
		<td align="center" width="9%"><font size="2">CALIBRE</td>
		<td align="center" width="9%"><font size="2">QTDE<br>MUNI��ES<br>USADAS</td>
		<td align="center" width="12%"><font size="2">SALDO ATUAL</td>
	</tr>';
	
	
	$array_rel_usadas = array();
	if($habitualidades){
		foreach($habitualidades as $idx => $value){
			
			$saldo = isset($array_compradas[$habitualidades[$idx]->calibre]) ? $array_compradas[$habitualidades[$idx]->calibre] : '';
			if(isset($array_compradas[$habitualidades[$idx]->calibre])){
				$saldo = $saldo - $habitualidades[$idx]->qtdemunicoes;
				$array_compradas[$habitualidades[$idx]->calibre] = $saldo;
			}
			
			$pdf .= '
			<tr>
				<td align="center" width="4%">'.($idx+1).'</td>
				<td align="center" width="8%">'.date('d/m/Y', strtotime($habitualidades[$idx]->data)).'</td>
				<td align="center" width="37%">'.utf8_decode($habitualidades[$idx]->local).'</td>
				<td align="center" width="19%">'.utf8_decode($habitualidades[$idx]->evento).'</td>
				<td align="center" width="9%">'.$habitualidades[$idx]->calibre.'</td>
				<td align="center" width="9%">'.$habitualidades[$idx]->qtdemunicoes.'</td>
				<td align="center" width="12%">'.$saldo.'</td>
			</tr>';
			
			$array_rel_usadas[$habitualidades[$idx]->calibre] += $habitualidades[$idx]->qtdemunicoes;
			
		}
	}
	
	
	$lista_compradas = '';
	$lista_usadas = '';
	$lista_restantes = '';
	foreach($array_rel_compradas as $key => $value){
		$lista_compradas .= "- {$key} = {$value} unidade(s);<br>";
	}
	foreach($array_rel_usadas as $key => $value){
		$lista_usadas .= "- {$key} = {$value} unidade(s);<br>";
	}
	foreach($array_compradas as $key => $value){
		$lista_restantes .= "- {$key} = {$value} unidade(s);<br>";
	}
	
	
	
	$pdf .= '
</table>
<br><br>
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td bgcolor="#C0C0C0"><b>Muni��es Compradas:</b></td>
		<td bgcolor="#C0C0C0"><b>Muni��es Utilizadas:</b></td>
		<td bgcolor="#C0C0C0"><b>Muni��es Restantes:</b></td>
	</tr>
	<tr>
		<td valign="top">'.$lista_compradas.'</td>
		<td valign="top">'.$lista_usadas.'</td>
		<td valign="top">'.$lista_restantes.'</td>
	</tr>
</table>
<br>
<br>
<br>
<div align="center">__________________________________________<br>'.utf8_decode($cliente->nome).'<br>CPF: '.formataCPFCNPJ($cliente->cpf).'

	';
	
	$dompdf->load_html(utf8_encode($pdf));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"mapa_municoes", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>