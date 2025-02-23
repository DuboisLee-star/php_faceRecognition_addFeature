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
	include_once ("../config/cabecalho.php");

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
    #   	$html .= '<tr><td>'.$row_membros['matricula'] . "</td>";
	#}
	
	$html .= '</tbody>';
	$html .= '</table>';

	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true));
	
	// Carrega seu HTML
	$dompdf->load_html(utf8_encode('

<br><br>

<!--
*****************************************************************************************************
                       REQUERIMENTO AO COMANDANTE PARA APOSTILAMENTO E CRAF - 01
*****************************************************************************************************
-->

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br>

<h4 style="text-align: center;">SERVIÇO DE FISCALIZAÇÃO DE PRODUTOS CONTROLADOS / 7ª RM</h4><br>

<h3 style="text-align: center;">REQUERIMENTO PARA EMISSÃO DE CRAF</h3><br>

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15">Exmo Sr Comandante da 7ª Região Militar

<p align="justify" style="line-height: 150%">&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, nacionalidade <B>'.utf8_decode($cliente->nacionalidade).'</b>, <B>'.utf8_decode($cliente->profissao).'</b>, Identidade: <B>'.utf8_decode($cliente->identidade).'</b>, CPF: <B>'.formataCPFCNPJ($cliente->cpf).',</b> número de CR <B>'.utf8_decode($cliente->cr).'</b>, residente e domiciliado na <B>'.utf8_decode($cliente->endereco).'</b>, Cep: <B>'.utf8_decode($cliente->cep).'</b>, <b>'.utf8_decode($cliente->cidade).'</b>/<b>'.utf8_decode($cliente->siglauf).'</b>, telefone <b>'.utf8_decode($cliente->telefone).'</b>, email <b>'.utf8_decode($cliente->email).'</b>, venho pelo presente requerer a Vossa Excelência, a emissão da 2a via do CRAF (Certificado de Registro de Arma de Fogo), da arma de fogo abaixo especificada ao Certificado de Registro de Atirador de Tiro CR <b>'.utf8_decode($cliente->cr).'</b>, conforme Portaria nº 51-COLOG, de 8 de setembro de 2015 e Portaria nº 40-COLOG, de 28 de março de 2018.</p>

<div align="center">
  <center>

<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>QTDE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MARCA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nº SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>PAÍS</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CANO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nª SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ATIVIDADE</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->especie1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->qtde1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->calibre1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->marca1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->modelo1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->num_serie_arma1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->pais_fab1).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->comp_cano1).'</td>
    <td width="10%" align="center">&nbsp;-</td>
    <td width="10%" align="center">&nbsp;<font size="1">ATIRADOR<br>DESPORTIVO</td>
  </tr>
</table>

<br><br><br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>&nbsp;'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<br><br>

<!--
*****************************************************************************************************
                        REQUERIMENTO AO COMANDANTE PARA APOSTILAMENTO E CRAF - 02
*****************************************************************************************************
-->

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br>

<h4 style="text-align: center;">SERVIÇO DE FISCALIZAÇÃO DE PRODUTOS CONTROLADOS / 7ª RM</h4><br>

<h3 style="text-align: center;">REQUERIMENTO PARA EMISSÃO DE CRAF</h3><br>

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15">Exmo Sr Comandante da 7ª Região Militar

<p align="justify" style="line-height: 150%">&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, nacionalidade <B>'.utf8_decode($cliente->nacionalidade).'</b>, <B>'.utf8_decode($cliente->profissao).'</b>, Identidade: <B>'.utf8_decode($cliente->identidade).'</b>, CPF: <B>'.formataCPFCNPJ($cliente->cpf).',</b> número de CR <B>'.utf8_decode($cliente->cr).'</b>, residente e domiciliado na <B>'.utf8_decode($cliente->endereco).'</b>, Cep: <B>'.utf8_decode($cliente->cep).'</b>, <b>'.utf8_decode($cliente->cidade).'</b>/<b>'.utf8_decode($cliente->siglauf).'</b>, telefone <b>'.utf8_decode($cliente->telefone).'</b>, email <b>'.utf8_decode($cliente->email).'</b>, venho pelo presente requerer a Vossa Excelência, a emissão da 2a via do CRAF (Certificado de Registro de Arma de Fogo), da arma de fogo abaixo especificada ao Certificado de Registro de Atirador de Tiro CR <b>'.utf8_decode($cliente->cr).'</b>, conforme Portaria nº 51-COLOG, de 8 de setembro de 2015 e Portaria nº 40-COLOG, de 28 de março de 2018.</p>

<div align="center">
  <center>

<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>QTDE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MARCA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nº SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>PAÍS</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CANO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nª SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ATIVIDADE</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->especie2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->qtde2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->calibre2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->marca2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->modelo2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->num_serie_arma2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->pais_fab2).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->comp_cano2).'</td>
    <td width="10%" align="center">&nbsp;-</td>
    <td width="10%" align="center">&nbsp;<font size="1">ATIRADOR<br>DESPORTIVO</td>
  </tr>
</table>

<br><br><br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>&nbsp;'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<br><br>

<!--
*****************************************************************************************************
                        REQUERIMENTO AO COMANDANTE PARA APOSTILAMENTO E CRAF - 03
*****************************************************************************************************
-->

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br>

<h4 style="text-align: center;">SERVIÇO DE FISCALIZAÇÃO DE PRODUTOS CONTROLADOS / 7ª RM</h4><br>

<h3 style="text-align: center;">REQUERIMENTO PARA EMISSÃO DE CRAF</h3><br>

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15">Exmo Sr Comandante da 7ª Região Militar

<p align="justify" style="line-height: 150%">&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, nacionalidade <B>'.utf8_decode($cliente->nacionalidade).'</b>, <B>'.utf8_decode($cliente->profissao).'</b>, Identidade: <B>'.utf8_decode($cliente->identidade).'</b>, CPF: <B>'.formataCPFCNPJ($cliente->cpf).',</b> número de CR <B>'.utf8_decode($cliente->cr).'</b>, residente e domiciliado na <B>'.utf8_decode($cliente->endereco).'</b>, Cep: <B>'.utf8_decode($cliente->cep).'</b>, <b>'.utf8_decode($cliente->cidade).'</b>/<b>'.utf8_decode($cliente->siglauf).'</b>, telefone <b>'.utf8_decode($cliente->telefone).'</b>, email <b>'.utf8_decode($cliente->email).'</b>, venho pelo presente requerer a Vossa Excelência, a emissão da 2a via do CRAF (Certificado de Registro de Arma de Fogo), da arma de fogo abaixo especificada ao Certificado de Registro de Atirador de Tiro CR <b>'.utf8_decode($cliente->cr).'</b>, conforme Portaria nº 51-COLOG, de 8 de setembro de 2015 e Portaria nº 40-COLOG, de 28 de março de 2018.</p>

<div align="center">
  <center>

<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>QTDE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MARCA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nº SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>PAÍS</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CANO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nª SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ATIVIDADE</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->especie3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->qtde3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->calibre3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->marca3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->modelo3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->num_serie_arma3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->pais_fab3).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->comp_cano3).'</td>
    <td width="10%" align="center">&nbsp;-</td>
    <td width="10%" align="center">&nbsp;<font size="1">ATIRADOR<br>DESPORTIVO</td>
  </tr>
</table>

<br><br><br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>&nbsp;'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<br><br>

<!--
*****************************************************************************************************
                         REQUERIMENTO AO COMANDANTE PARA APOSTILAMENTO E CRAF - 04
*****************************************************************************************************
-->

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br><br>

<h4 style="text-align: center;">SERVIÇO DE FISCALIZAÇÃO DE PRODUTOS CONTROLADOS / 7ª RM</h4><br>

<h3 style="text-align: center;">REQUERIMENTO PARA EMISSÃO DE CRAF</h3><br>

<p align="justify" style="line-height: 150%; margin-left: 15; margin-right: 15">Exmo Sr Comandante da 7ª Região Militar

<p align="justify" style="line-height: 150%">&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, nacionalidade <B>'.utf8_decode($cliente->nacionalidade).'</b>, <B>'.utf8_decode($cliente->profissao).'</b>, Identidade: <B>'.utf8_decode($cliente->identidade).'</b>, CPF: <B>'.formataCPFCNPJ($cliente->cpf).',</b> número de CR <B>'.utf8_decode($cliente->cr).'</b>, residente e domiciliado na <B>'.utf8_decode($cliente->endereco).'</b>, Cep: <B>'.utf8_decode($cliente->cep).'</b>, <b>'.utf8_decode($cliente->cidade).'</b>/<b>'.utf8_decode($cliente->siglauf).'</b>, telefone <b>'.utf8_decode($cliente->telefone).'</b>, email <b>'.utf8_decode($cliente->email).'</b>, venho pelo presente requerer a Vossa Excelência, a emissão da 2a via do CRAF (Certificado de Registro de Arma de Fogo), da arma de fogo abaixo especificada ao Certificado de Registro de Atirador de Tiro CR <b>'.utf8_decode($cliente->cr).'</b>, conforme Portaria nº 51-COLOG, de 8 de setembro de 2015 e Portaria nº 40-COLOG, de 28 de março de 2018.</p>

<div align="center">
  <center>

<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>QTDE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MARCA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nº SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>PAÍS</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>CANO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>Nª SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="1"><b>ATIVIDADE</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->especie4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->qtde4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->calibre4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->marca4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->modelo4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->num_serie_arma4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->pais_fab4).'</td>
    <td width="10%" align="center">&nbsp;<font size="1">'.utf8_decode($cliente->comp_cano4).'</td>
    <td width="10%" align="center">&nbsp;-</td>
    <td width="10%" align="center">&nbsp;<font size="1">ATIRADOR<br>DESPORTIVO</td>
  </tr>
</table>

<br><br><br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>&nbsp;'.formataCPFCNPJ($cliente->cpf).'<br>


		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"2avia_craf", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>