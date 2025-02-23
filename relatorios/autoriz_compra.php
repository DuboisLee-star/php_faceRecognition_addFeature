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

	include_once("../config/conexao.php");
	

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
	
	#$result_membros = "SELECT * FROM tab_membros order by matricula";
	#$resultado_membros = mysqli_query($conn, $result_membros);
	#while($row_membros = mysqli_fetch_assoc($resultado_membros)){
    #    
    #        	$html .= '<tr><td>'.$row_membros['matricula'] . "</td>";
	#}
	
	$html .= '</tbody>';
	$html .= '</table>';
	
	// armas
	$armas = explode(",", $_GET['a']);
	$lista_armars = "";
	for($i=0;$i<count($armas);$i++){
		
		if(strlen(trim($armas[$i])) > 0){
			$especie = "especie".$armas[$i];
			$calibre = "calibre".$armas[$i];
			$marca = "marca".$armas[$i];
			$modelo = "modelo".$armas[$i];
			$qtde = "qtde".$armas[$i];
			
			$lista_armars .= '<tr>';
			$lista_armars .= '	<td width="14%" align="center"><font size="2">'.utf8_decode($cliente->$especie).'</td>';
			$lista_armars .= '	<td width="9%" align="center"><font size="2">'.utf8_decode($cliente->$calibre).'</td>';
			$lista_armars .= '	<td width="59%" align="center"><font size="2">'.utf8_decode($cliente->$marca).' / '.utf8_decode($cliente->$modelo).'</td>';
			$lista_armars .= '	<td width="17%" align="center"><font size="2">'.utf8_decode($cliente->$qtde).'</td>';
			$lista_armars .= '</tr>';
		}
		
	}
	
	$fornecedores = explode(",", $_GET['f']);
	$lista_fornecedor = "";
	for($i=0;$i<count($fornecedores);$i++){
		
		if(strlen(trim($fornecedores[$i])) > 0){
			$fornecedor = "fornecedor".$fornecedores[$i];
			$crfornecedor = "crfornecedor".$fornecedores[$i];
			
			$lista_fornecedor .= '<tr>';
			$lista_fornecedor .= '		<td width="90%" align="left" colspan="2">&nbsp; <font size="2">&nbsp;Fornecedor:</font>&nbsp;<font size="1">'.utf8_decode($cliente->$fornecedor).'</font></td>';
			$lista_fornecedor .= '		<td width="10%" align="left" colspan="2">&nbsp; <font size="2">&nbsp;CR:&nbsp;<B>'.utf8_decode($cliente->$crfornecedor).'</B></td>';
			$lista_fornecedor .= '	</tr>';
		}
		
	}

	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF();
	
	// Carrega seu HTML
	$dompdf->load_html(utf8_encode('

<!--
*****************************************************************************************************
                                        REQUERIMENTO AUTORIZAÇÃO DE COMPRA
*****************************************************************************************************
-->

<p align="center"><font size="3"><b>Anexo E</font><br><font size="2">REQUERIMENTO PARA AQUISIÇÃO DE ARMA DE FOGO E ACESSÓRIO</b><br>(colecionador, atirador desportivo, caçador e entidades de tiro desportivo)</p>
<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-left-width: 0px; border-right-width: 0px; border-top-width: 0px">
	<tr>
		<td style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium" colspan="2">
		<b><font size="2">1. REQUERENTE</b></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp; <font size="2">Nome completo/razão social: &nbsp; '.utf8_decode($cliente->nome).'</td>
	</tr>
	<tr>
		<td>&nbsp; <font size="2">Certificado de Registro (CR):&nbsp; '.utf8_decode($cliente->cr).' </td>
		<td>&nbsp; <font size="2">CPF/CNPJ: &nbsp; '.formataCPFCNPJ($cliente->cpf).'</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp; <font size="2">Representante legal: &nbsp;Werber Marques (werbermarques@gmail.com)</td>
	</tr>
	<tr>
		<td width="54%">&nbsp; <font size="2">Telefones: &nbsp; '.utf8_decode($cliente->telefone).'</td>
		<td width="46%">&nbsp; <font size="2">Email:&nbsp; '.utf8_decode($cliente->email).'</td>
	</tr>
</table>
</div><br>

<div align="center">

<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-left-width: 0px; border-right-width: 0px; border-top-width: 0px">
	<tr>
		<td style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium" colspan="4">
		<b><font size="2">2. OBJETO</b></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp; <font size="2">Solicitação de autorização para aquisição de arma de fogo para: </td>
	</tr>
	<tr>
		<td width="24%">&nbsp; <font size="2">(&nbsp;&nbsp;) Colecionamento</td>
		<td width="24%">&nbsp; <font size="2">(&nbsp;<b>X</b>&nbsp;) Tiro Desportivo</td>
		<td width="24%">&nbsp; <font size="2">(&nbsp;&nbsp;) Caça</td>
		<td width="27%">&nbsp; <font size="2">(&nbsp;&nbsp;) Entidade de Tiro Desportivo</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp; <font size="2">(&nbsp;&nbsp;) aquisição de acessório de arma de fogo para tiro desportivo/entidade de tiro desportivo/caça </td>
	</tr>
</table>

</div>

<br>

<div align="center">

<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-left-width: 0px; border-right-width: 0px; border-top-width: 0px">
	<tr>
		<td style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium" colspan="4">
		<b><font size="2">3. ARMAS DE FOGO/ACESSÓRIO</b></td>
	</tr>
	<tr>
		<td width="14%" align="center" bgcolor="#C0C0C0"><font size="2">TIPO</td>
		<td width="9%" align="center" bgcolor="#C0C0C0"><font size="2">CALIBRE</td>
		<td width="59%" align="center" bgcolor="#C0C0C0"><font size="2">MARCA/MODELO</td>
		<td width="17%" align="center" bgcolor="#C0C0C0"><font size="2">QUANTIDADE</td>
	</tr>
	'.$lista_armars.'
	<tr>
		<td width="14%" align="center">&nbsp;</td>
		<td width="9%" align="center">&nbsp;</td>
		<td width="59%" align="center">&nbsp;</td>
		<td width="17%" align="center">&nbsp;</td>
	</tr>
	<tr>

		<td width="99%" align="left" colspan="4"><font size="2">&nbsp; <font size="2">Dados técnicos esclarecedores do acessório: </p></td>
	</tr>
	'.$lista_fornecedor.'
</table>

</div>

<div align="justify"><font size="2"><br><b>4. ANEXOS</b> <br>
<div align="justify">____________________________________________________________________________________________________________
____________________________________________________________________________________________________________</p>

<div align="justify"><font size="2"><br><b>5. OUTRAS INFORMAÇÕES</b> <br>
<div align="justify">____________________________________________________________________________________________________________
____________________________________________________________________________________________________________</p>


<div align="justify"><font size="2"><br>Declaro que tenho conhecimento das prescrições dos art. 9º ao 12. da Portaria ____-COLOG/2019 quanto à aquisição de arma de fogo e que as informações ora prestadas são verdadeiras, sob pena de responsabilidade administrativa, civil e penal, conforme art. 299 do Código Penal Brasileiro (falsidade ideológica). </p>
<br><br>

<div align=center>'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), _________ de ___________________ de _____________.<br><br>

<div align=center><font size="2">_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><br>

<table border="1" width="100%">
					<tr>
						<td bgcolor="#C0C0C0">
						<DIV align="center"><font size="2">DESPACHO DA OM DO SISFPC</td>
					</tr>
					<tr>
						<td><BR>&nbsp; <font size="2">(&nbsp;&nbsp;) Deferido – AUTORIZAÇÃO PARA AQUISIÇÃO nº ______________-SFPC/ , de	____/____/____
						<br>&nbsp; <font size="2">(&nbsp;&nbsp;) Indeferido <BR>
		<DIV align="center"><font size="1">__________________________________________________________________________________________________________________________________ <br><br>
		<DIV align="center"><font size="1">__________________________________________________________________________________________________________________________________ 
						<br><br><br>
						<div align="center"><font size="2"><br>
						<div align="center"><font size="2">___________________________________________ <br>
						<div align="center"><font size="2"><br><br></td>
					</tr>
				</table>


                       
		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"autoriz_compra", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>
