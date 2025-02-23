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
                                        CONTROLE DE MUNIÇÃO
*****************************************************************************************************
-->

<div align="center"><b><h3>CONTROLE DE MUNIÇÕES E INSUMOS</h3></b><br>

<table border="1" width="100%" cellspacing="0">
	<tr>
		<td colspan="4">&nbsp;<b>Nome do(a) Atirador(a):</b> &nbsp;'.$cliente->nome.'</td>
		<td colspan="3">&nbsp;<b>CR: </b> &nbsp;'.$cliente->cr.'</td>
	</tr>
	<tr>
		<td colspan="7" bgcolor="#808080"><p align="center"><b>ENTRADA DE MUNIÇÕES</b></td>
	</tr>
	<tr>
		<td align="center" rowspan="3" bgcolor="#C0C0C0" width="22%"><font size="2">ORIGEM</td>
		<td align="center" rowspan="3" bgcolor="#C0C0C0" width="13%"><font size="2">DATA</td>
		<td align="center" rowspan="3" bgcolor="#C0C0C0" width="14%"><font size="2">NF</td>
		<td align="center" colspan="4"><font size="2">TIPO</td>
	</tr>
	<tr>
		<td align="center" colspan="2"><font size="2">MUNIÇÕES</td>
		<td align="center" colspan="2"><font size="2">INSUMOS</td>
	</tr>
	<tr>
		<td align="center" width="14%"><font size="2">CALIBRE</td>
		<td align="center" width="12%"><font size="2">QTDE</td>
		<td align="center" width="13%"><font size="2">ESPÉCIE</td>
		<td align="center" width="9%"><font size="2">QTDE</td>
	</tr>';

	//exit(print_r($compras));
	$array_compradas = array();
	if($compras){
		foreach($compras as $idx => $value){
			
			$pdf .= '
			<tr>
				<td align="center" width="22%"><font size="2">'.($compras[$idx]->compra_loja).'</td>
				<td align="center" width="13%"><font size="2">'.date('d/m/Y', strtotime($compras[$idx]->compra_data)).'</td>
				<td align="center" width="14%"><font size="2">'.$compras[$idx]->compra_nf.'</td>
				<td align="center" width="14%"><font size="2"><font size="2">'.$compras[$idx]->compra_calibre.'</td>
				<td align="center" width="12%"><font size="2">'.$compras[$idx]->compra_qtdecalibre.'</td>
				<td align="center" width="13%"><font size="2">'.$compras[$idx]->compra_insumos.'</td>
				<td align="center" width="9%"><font size="2">'.$compras[$idx]->compra_qtdeinsumos.'</td>
			</tr>';
			
			$qtde_municao = ((int)$compras[$idx]->compra_qtdecalibre > 0) ? $compras[$idx]->compra_qtdecalibre : 0;
			$qtde_insumo = ((int)$compras[$idx]->compra_qtdeinsumos > 0) ? $compras[$idx]->compra_qtdeinsumos : 0;

			$array_compradas[$compras[$idx]->compra_calibre] = (int)$array_compradas[$compras[$idx]->compra_calibre] + $qtde_municao + ($qtde_insumo);
			
		}
	}
	
	$array_rel_compradas = $array_compradas;
	
	// print_r($array_compradas);exit();
	
	$pdf .= '
	</table>

<br><br>

<table border="1" width="100%" cellspacing="0">
	<tr>
		<td colspan="7" bgcolor="#C0C0C0"><p align="center"><b>SAÍDA DE MUNIÇÕES</B></td>
	</tr>
	<tr>
		<td align="center" width="5%"><font size="2">N.</td>
		<td align="center" width="10%"><font size="2">DATA</td>
		<td align="center" width="10%"><font size="2">LOCAL</td>
		<td align="center" width="36%"><font size="2">EVENTO</td>
		<td align="center" width="12%"><font size="2">CALIBRE</td>
		<td align="center" width="12%"><font size="2">QTDE<br>MUNIÇÕES<br>USADAS</td>
		<td align="center" width="15%"><font size="2">SALDO ATUAL</td>
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
				<td align="center" width="5%">'.($idx+1).'</td>
				<td align="center" width="10%">&nbsp;<font size="2">'.date('d/m/Y', strtotime($habitualidades[$idx]->data)).'&nbsp;</td>
				<td align="center" width="10%">&nbsp;<font size="2">'.($habitualidades[$idx]->local).'&nbsp;</td>
				<td align="center" width="36%">&nbsp;<font size="2">'.($habitualidades[$idx]->evento).'&nbsp;</td>
				<td align="center" width="12%">&nbsp;<font size="2">'.$habitualidades[$idx]->calibre.'&nbsp;</td>
				<td align="center" width="12%">&nbsp;<font size="2">'.$habitualidades[$idx]->qtdemunicoes.'&nbsp;</td>
				<td align="center" width="15%">&nbsp;<font size="2">'.$saldo.'&nbsp;</td>
			</tr>';
			
			$array_rel_usadas[$habitualidades[$idx]->calibre] += $habitualidades[$idx]->qtdemunicoes;
			
		}
	}
	
	$lista_compradas = '';
	$lista_usadas = '';
	$lista_restantes = '';
	foreach($array_rel_compradas as $key => $value){
		$lista_compradas .= "- {$key} = {$value} unid;<br>";
	}
	foreach($array_rel_usadas as $key => $value){
		$lista_usadas .= "- {$key} = {$value} unid;<br>";
	}
	foreach($array_compradas as $key => $value){
		$lista_restantes .= "- {$key} = {$value} unid;<br>";
	}
	
	$pdf .= '
</table>
<br><br>
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td bgcolor="#C0C0C0"><font size="2"><b>Munições Compradas:</b></td>
		<td bgcolor="#C0C0C0"><font size="2"><b>Munições Utilizadas:</b></td>
		<td bgcolor="#C0C0C0"><font size="2"><b>Munições Restantes:</b></td>
	</tr>
	<tr>
		<td valign="top"><font size="2">'.$lista_compradas.'</td>
		<td valign="top"><font size="2">'.$lista_usadas.'</td>
		<td valign="top"><font size="2">'.$lista_restantes.'</td>
	</tr>
</table>

	';
	
	$dompdf->load_html(($pdf));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"controle_municao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>