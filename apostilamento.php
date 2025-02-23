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
	
	
	$html_anexos = "";
	
	
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
			$num_serie_arma = "num_serie_arma".$armas[$i];
			$tp_funcionamento = "tp_funcionamento".$armas[$i];
			$pais_fab = "pais_fab".$armas[$i];
			$acabamento = "acabamento".$armas[$i];
			$quant_canos = "quant_canos".$armas[$i];
			$comp_cano = "comp_cano".$armas[$i];
			$tipo_alma = "tipo_alma".$armas[$i];
			$num_raias = "num_raias".$armas[$i];
			$cap_carregamento = "cap_carregamento".$armas[$i];
			$sentido_raia = "sentido_raia".$armas[$i];
			
			$lista_armars .= '<tr>';
			$lista_armars .= '	<td width="14%" align="center"><font size="2">'.utf8_decode($cliente->$especie).'</td>';
			$lista_armars .= '	<td width="9%" align="center"><font size="2">'.utf8_decode($cliente->$calibre).'</td>';
			$lista_armars .= '	<td width="59%" align="center"><font size="2">'.utf8_decode($cliente->$marca).' / '.utf8_decode($cliente->$modelo).'</td>';
			$lista_armars .= '	<td width="17%" align="center"><font size="2">'.utf8_decode($cliente->$qtde).'</td>';
			$lista_armars .= '</tr>';
			
			
			
			$html_anexos .= '<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                                    ANEXO F1
*****************************************************************************************************
-->
                        
                        <h3 style="text-align: center;">ANEXO F1</h3><br>

                        <h3 style="text-align: center;">FICHA DE REGISTRO DE ARMA DE FOGO NO SIGMA</h3><br>

<div align="center">
  <center>
  <table align="center" border="1" width="85%">
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Número de série da arma</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$num_serie_arma).'</td>
      <td width="25%">&nbsp;<b><font size="2">Marca</b></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$marca).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Modelo</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$modelo).'</td>
      <td width="25%">&nbsp;<b><font size="2">Espécie</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$especie).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Tp funcionamento</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$tp_funcionamento).'</td>
      <td width="25%">&nbsp;<b><font size="2">País fabricação</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$pais_fab).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Calibre</b></font></td>
      <td width="75%" colspan="3">&nbsp;'.utf8_decode($cliente->$calibre).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Acabamento</b></font></td>
      <td width="75%" colspan="3">&nbsp;'.utf8_decode($cliente->$acabamento).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Quant canos</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$quant_canos).'</td>
      <td width="25%">&nbsp;<b><font size="2">Comp do cano</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$comp_cano).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Tipo alma</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$tipo_alma).'</td>
      <td width="25%">&nbsp;<b><font size="2">Nº de raias</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$num_raias).'</td>
    </tr>
    <tr>
      <td width="25%">&nbsp;<b><font size="2">Cap carregamento</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$cap_carregamento).'</td>
      <td width="25%">&nbsp;<b><font size="2">Sentido da raia</b></font></td>
      <td width="25%" align="center">&nbsp;'.utf8_decode($cliente->$sentido_raia).'</td>
    </tr>
  </table>
  </center>
</div>
<br><br><br>
<div align=center><font size="2">'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br><BR>
<p align=center>_____________________________________________<br>'.utf8_decode($cliente->nome).'<b><br>CPF: '.formataCPFCNPJ($cliente->cpf).'</b><br>';
			
			
			
		}
		
	}
	
	$fornecedores = explode(",", $_GET['f']);
	$lista_fornecedor = "";
	for($i=0;$i<count($fornecedores);$i++){
		
		if(strlen(trim($fornecedores[$i])) > 0){
			$fornecedor = "fornecedor".$fornecedores[$i];
			$crfornecedor = "crfornecedor".$fornecedores[$i];
			$num_nf = "num_nf".$fornecedores[$i];
			$data_nf = "data_nf".$fornecedores[$i];
			$num_autoriz = "num_autoriz".$fornecedores[$i];
			$data_autoriz = "data_autoriz".$fornecedores[$i];
			
			$lista_fornecedor .= '<tr>';
			$lista_fornecedor .= '		<td width="23%" align="center">&nbsp; <font size="2"><font size="1">'.utf8_decode($cliente->$fornecedor).'</font></td>';
			$lista_fornecedor .= '		<td width="18%" align="center">&nbsp; <font size="2">'.utf8_decode($cliente->$crfornecedor).'</font></td>';
			$lista_fornecedor .= '		<td width="34%" align="center">&nbsp; <font size="2">'.utf8_decode($cliente->$num_nf).'<br>'.date('d/m/Y', strtotime($cliente->$data_nf)).'</font></td>';
			$lista_fornecedor .= '		<td width="24%" align="center">&nbsp; <font size="2">'.utf8_decode($cliente->$num_autoriz).'<br>'.date('d/m/Y', strtotime($cliente->$data_autoriz)).'</font></td>';
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

                        <br><br>
<!--
*****************************************************************************************************
                                  REQUERIMENTO PARA APOSTILAMENTO
*****************************************************************************************************
-->

                        <p align="center"><font size="3"><b>Anexo F<br>REQUERIMENTO PARA REGISTRO E APOSTILAMENTO</b><br>(colecionador, atirador desportivo, caçador e entidades de tiro desportivo)</p>
<table border="1" width="100%" cellspacing="0" cellpadding="0" style="border-left-width: 0px; border-right-width: 0px; border-top-width: 0px">
	<tr>
		<td style="border-left-style: none; border-left-width: medium; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium" colspan="2">
		<b><font size="2">1. REQUERENTE</b></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp; <font size="2">Nome completo/razão social: &nbsp; '.utf8_decode($cliente->nome).'</td>
	</tr>
	<tr>
		<td>&nbsp; <font size="2">Certificado de Registro (CR):&nbsp; '.utf8_decode($cliente->cr).'</td>
		<td>&nbsp; <font size="2">CPF/CNPJ: &nbsp; '.formataCPFCNPJ($cliente->cpf).'</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp; <font size="2">Representante legal: &nbsp;Werber Marques -  werbermarques@gmail.com (84-8121-2122)</td>
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
		<td width="23%" align="center" bgcolor="#C0C0C0"><font size="2">TIPO</td>
		<td width="18%" align="center" bgcolor="#C0C0C0"><font size="2">CALIBRE</td>
		<td width="34%" align="center" bgcolor="#C0C0C0"><font size="2">MARCA/MODELO</td>
		<td width="24%" align="center" bgcolor="#C0C0C0"><font size="2">QUANTIDADE</td>
	</tr>
	'.$lista_armars.'
	<tr>
		<td width="23%" align="center"></td>
		<td width="18%" align="center">&nbsp;</td>
		<td width="34%" align="center">&nbsp;</td>
		<td width="24%" align="center">&nbsp;</td>
	</tr>
	<tr>
		<td width="23%" align="left" bgcolor="#C0C0C0">
		<p align="center">&nbsp; <font size="2">&nbsp;FORNECEDOR:</font>&nbsp;</td>
		<td width="18%" align="left" bgcolor="#C0C0C0">
		<p align="center"> <font size="2">CR</td>
		<td width="34%" align="left" bgcolor="#C0C0C0">
		<p align="center"><font size="2">NOTA FISCAL / DATA</td>
		<td width="24%" align="left" bgcolor="#C0C0C0">
		<p align="center"><font size="2">AUTORIZAÇÃO PARA AQUISIÇÃO / DATA</font></td>
	</tr>
	'.$lista_fornecedor.'
	
</table>

</div>

<div align="justify"><font size="2"><br><b>4. ANEXOS</b> <br>
<div align="justify">____________________________________________________________________________________________________________<br>
____________________________________________________________________________________________________________<br>

<font size="2"><div align="justify"><br>Declaro que as informações ora prestadas são verdadeiras, sob pena de responsabilidade administrativa, cifil e penal, conforme art. 299 do Código Penal Brasileiro (falsidade ideológica).<br>
<br><br>

<div align=center><font size="2">'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br><BR>

<div align=center><font size="2">_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><br>

	</font>

</div><br><br>
<div align="left"><font size="2">(*) Conforme art. 6º ao 8º da Port. __________ COLOG/2019.<br>
- nota fiscal da arma;<br>
- comprovante de pagamento das taxas de registro e de apostilamento da arma de fogo;<br>
- ficha para cadastro de arma de fogo no SIGMA (anexo F1 Port. _____ COLOG/2019).</p>


'.$html_anexos.'

		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"apostilamento", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>