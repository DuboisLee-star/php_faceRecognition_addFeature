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

                        <h3 style="text-align: center;">R E Q U E R I M E N T O</b></h3><br><br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">AO SFPC 16BiMTZ - 7ª RM.<br><br>

                        <p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador do CPF: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', órgão emissor: '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente a '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', número de CR <b>'.utf8_decode($cliente->cr).'</b>, telefone '.utf8_decode($cliente->telefone).', email: '.utf8_decode($cliente->email).', membro do <b>CTC - CLUBE DE TIRO CARCARÁ</b>, na cidade de Mossoró/RN, venho pelo presente comunicar o furto de minha arma de acervo de atirador desportivo, conforme descriminado abaixo. Estou solicitando a mudança de status do referido armamento e enviando anexo cópia do B.O - Boletim de Ocorrênia em que detalha-se o ocorrido. Segue junto a este requerimento documentação de identificação pessoal do atirador.   

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
    <td width="10%" align="center" bgcolor="#C0C0C0"><font size="2"><b>OBS</b></td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;<font size="2">ARMA DE FOGO</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_sigma_arma4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->num_serie_arma4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">Pistola</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->calibre4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->modelo4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">'.utf8_decode($cliente->marca4).'</td>
    <td width="10%" align="center">&nbsp;<font size="2">&nbsp;-&nbsp;</td>
  </tr>
</table>

<BR><bR>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Nestes termos,

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Pede deferimento.


<br><br><br><br>

<p align=center style="line-height: 110%; margin-left: 0; margin-right: 0">
Mossoró (RN), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>



		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"requerimento_furto", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>