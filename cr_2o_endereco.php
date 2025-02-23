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
                                 DECLARA��O DE ENDERE�O DE GUARDA DO ACERVO
*****************************************************************************************************
-->
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br><br>

<h3 style="text-align: center;">DECLARA��O DE ENDERE�O DE GUARDA DO ACERVO</h3><br><br><br>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador da carteira de identidade n� <B>'.utf8_decode($cliente->identidade).'</b> � <B>'.utf8_decode($cliente->orgaouf).'</b>, <B>'.utf8_decode($cliente->nacionalidade).'</b>, natural de <B>'.utf8_decode($cliente->naturalidade).'</b>, CPF n� <B>'.formataCPFCNPJ($cliente->cpf).'</b>, nascido em <B>'.date('d/m/Y', strtotime($cliente->data_nascimento)).'</b>, filho de  <B>'.utf8_decode($cliente->pai).'</b> e <B>'.utf8_decode($cliente->mae).'</b>,, residente � '.utf8_decode($cliente->segundo_rua).' '.utf8_decode($cliente->segundo_num).', '.utf8_decode($cliente->segundo_bairro).' - Cep: '.utf8_decode($cliente->segundo_cep).', '.utf8_decode($cliente->segundo_cidade).'/'.utf8_decode($cliente->segundo_estado).', Telefone: <B>'.utf8_decode($cliente->telefone).'</b>, e-mail: <B>'.utf8_decode($cliente->email).'</b>, DECLARO para fins de <b>COMPROVA��O DE RESID�NCIA</b> perante o Ex�rcito Brasileiro, sob as penas da Lei que estou residindo e domiciliado no endere�o informado.

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Por ser a express�o da verdade, assumindo inteira responsabilidade pela declara��o acima sob as penas da lei, assino para que produza seus efeitos legais.</p>

<br><br><br><br>
<p align=center>
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), _________ de ___________________ de _____________.<br><br>

<p align=center>
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                    DECLARA��O DE SEGURAN�A DO ACERVO
*****************************************************************************************************
-->

<br><br><br><br><br><br><br><br>
<h3 style="text-align: center;">ANEXO D</h3><br>

<h3 style="text-align: center;">DECLARA��O DE SEGURAN�A DO ACERVO (DSA)</h3><br>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">EU, <B>'.utf8_decode($cliente->nome).'</b>, nacionalidade <B>'.utf8_decode($cliente->nacionalidade).'</b>, natural de <B>'.utf8_decode($cliente->naturalidade).'</b>, nascido em <b>'.date('d/m/Y', strtotime($cliente->data_nascimento)).'</b>, profiss�o <B>'.utf8_decode($cliente->profissao).'</b>, estado civil <B>'.utf8_decode($cliente->estadocivil).'</b>, residente � '.utf8_decode($cliente->segundo_rua).' '.utf8_decode($cliente->segundo_num).', '.utf8_decode($cliente->segundo_bairro).' - Cep: '.utf8_decode($cliente->segundo_cep).', '.utf8_decode($cliente->segundo_cidade).'/'.utf8_decode($cliente->segundo_estado).' e CPF n� <B>'.formataCPFCNPJ($cliente->cpf).'</b>.

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">DECLARO, para fim de <b>CONCESS�O DE CERTIFICADO DE REGISTRO</b> no Ex�rcito Brasileiro que o local de guarda do meu acervo de <b>ATIRADOR DESPORTIVO</b> atende as condi��es de seguran�a previstas no anexo F da portaria 150 - COLOG/2019.

<br><br><br><br>
<p align=center>
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), _________ de ___________________ de _____________.<br><br>

<p align=center>
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>
<br><br><br><br><br><br>

		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"cr_2o_endereco", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>