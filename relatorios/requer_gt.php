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

	include_once("../config/conexao.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura.php");

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
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma1).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma1).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie1).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre1).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo1).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca1).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre1).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma2).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma2).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie2).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre2).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo2).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca2).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre2).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma3).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma3).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie3).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre3).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo3).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca3).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre3).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca4).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre4).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma5).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma5).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie5).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre5).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo5).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca5).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre5).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma6).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma6).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie6).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre6).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo6).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca6).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre6).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma7).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma7).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie7).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre7).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo7).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca7).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre7).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma8).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma8).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie8).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre8).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo8).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca8).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre8).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma9).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma9).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie9).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre9).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo9).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca9).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre9).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                      REQUERIMENTO AO COMANDANTE DA 7RM
*****************************************************************************************************
-->

                        <br><br><br><br><br><br>

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO EXMO SR COMANDANTE DA 7° REGIAO MILITAR.

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', vem pelo presente requerer a V. Exa. a autorização para adquirir, junto ao SFPC a Guia de Tráfego da arma(municiada) discriminada abaixo de acordo com o Art. 14 da Portaria 51 - COLOG - de 08 de Setembro de 2015, pleiteando a atividade de USO DESPORTIVO - ATIRADOR.   

<br><br>
<table border="1" width="100%">
  <tr>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>PRODUTO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº SIGMA</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>Nº DE SÉRIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>ESPÉCIE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>CALIBRE</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MODELO</b></td>
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>MARCA</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma10).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma10).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->especie10).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre10).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo10).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca10).'</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">MUNIÇÕES</td>
    <td width="10%" align="center">&nbsp;<font size="2">-</td>
    <td width="10%" align="center">&nbsp;<font size="2">5000</td>
    <td width="10%" align="center">&nbsp;<font size="2">CARTUCHOS</td>
     <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre10).'</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
     <td width="10%" align="center">&nbsp;<font size="2">-</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', ______ de ______________ de ________.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"solicitacao_gt", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>