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
                        FOLHA PARA DECLARAÇÃO DE NÃO ESTAR RESPONDENDO A INQUÉRITO
*****************************************************************************************************
-->

<br><br><br><br><br>

<h3 style="text-align: center;">D E C L A R A Ç Ã O</h3><br><br><br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador da carteira de identidade n° <B>'.utf8_decode($cliente->identidade).'</b> – <B>'.utf8_decode($cliente->orgaouf).'</b>, <B>'.utf8_decode($cliente->nacionalidade).'</b>, natural de <B>'.utf8_decode($cliente->naturalidade).'</b>, CPF n° <B>'.formataCPFCNPJ($cliente->cpf).'</b>, nascido em <B>'.date('d/m/Y', strtotime($cliente->data_nascimento)).'</b>, filho de  <B>'.utf8_decode($cliente->pai).'</b> e <B>'.utf8_decode($cliente->mae).'</b>,, residente à <B>'.utf8_decode($cliente->rua).'</b> nº <B>'.utf8_decode($cliente->numero).'</b>, bairro <B>'.utf8_decode($cliente->bairro).'</b>, cep: <B>'.utf8_decode($cliente->cep).'</b>, <B>'.utf8_decode($cliente->cidade).'</b>/<B>'.utf8_decode($cliente->siglauf).'</b>, Telefone: <B>'.utf8_decode($cliente->telefone).'</b>, e-mail: <B>'.utf8_decode($cliente->email).'</b>, DECLARO para fins de prova perante o Exército Brasileiro, por meio do SERVIÇO DE FISCALIZAÇÃO DE PRODUTOS CONTROLADOS, que não respondo Inquérito Policial, nem Processo Criminal, não possuindo condenação penal, conforme Certidões Negativas apresentadas.

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Declaro, sob penas da lei, que possuo bons antecedentes e idoneidade moral e estou ciente de que em caso de falsidade ideológica, ficarei sujeito as sanções prescritas no código penal e às demais cominações legais aplicáveis.</p>

<br><br><br><br><br>
<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10"><font size="2">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<br><br><br><br><br><br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                DECLARAÇÃO DE ENDEREÇO DE GUARDA DO ACERVO
*****************************************************************************************************
-->

<br><br><br><br><br>

<h3 style="text-align: center;">DECLARAÇÃO DE ENDEREÇO DE GUARDA DO ACERVO</h3><br><br><br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu, <B>'.utf8_decode($cliente->nome).'</b>, portador da carteira de identidade n° <B>'.utf8_decode($cliente->identidade).'</b> – <B>'.utf8_decode($cliente->orgaouf).'</b>, <B>'.utf8_decode($cliente->nacionalidade).'</b>, natural de <B>'.utf8_decode($cliente->naturalidade).'</b>, CPF n° <B>'.formataCPFCNPJ($cliente->cpf).'</b>, nascido em <B>'.date('d/m/Y', strtotime($cliente->data_nascimento)).'</b>, filho de  <B>'.utf8_decode($cliente->pai).'</b> e <B>'.utf8_decode($cliente->mae).'</b>,, residente à <B>'.utf8_decode($cliente->rua).'</b> nº <B>'.utf8_decode($cliente->numero).'</b>, bairro <B>'.utf8_decode($cliente->bairro).'</b>, cep: <B>'.utf8_decode($cliente->cep).'</b>, <B>'.utf8_decode($cliente->cidade).'</b>/<B>'.utf8_decode($cliente->siglauf).'</b>, Telefone: <B>'.utf8_decode($cliente->telefone).'</b>, e-mail: <B>'.utf8_decode($cliente->email).'</b>, DECLARO para fins de <b>COMPROVAÇÃO DE RESIDÊNCIA</b> perante o Exército Brasileiro, sob as penas da Lei que estou residindo e domiciliado no endereço informado.

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Por ser a expressão da verdade, assumindo inteira responsabilidade pela declaração acima sob as penas da lei, assino para que produza seus efeitos legais.</p>

<br><br><br><br>
<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10"><font size="2">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10"><font size="2">
_______________________________________________<br><B>'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                     DECLARAÇÃO DE SEGURANÇA DO ACERVO
*****************************************************************************************************
-->

<br><br><br><br><br><br><br><br>

<h3 style="text-align: center;">ANEXO D</h3><br>

<h3 style="text-align: center;">DECLARAÇÃO DE SEGURANÇA DO ACERVO (DSA)</h3><br>

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
EU, <B>'.utf8_decode($cliente->nome).'</b>, nacionalidade <B>'.utf8_decode($cliente->nacionalidade).'</b>, natural de <B>'.utf8_decode($cliente->naturalidade).'</b>, nascido em <b>'.date('d/m/Y', strtotime($cliente->data_nascimento)).'</b>, profissão <B>'.utf8_decode($cliente->profissao).'</b>, estado civil <B>'.utf8_decode($cliente->estadocivil).'</b>, residente à <b>'.utf8_decode($cliente->rua).'</b> nº <b>'.utf8_decode($cliente->numero).'</b>, <b>'.utf8_decode($cliente->bairro).'</b>, <b>'.utf8_decode($cliente->cep).'</b>, cidade <B>'.utf8_decode($cliente->cidade).'</b>/<B>'.utf8_decode($cliente->siglauf).'</b> e CPF nº <B>'.formataCPFCNPJ($cliente->cpf).'</b>.

<p align=justify style="line-height: 150%; margin-left: 10; margin-right: 10">
DECLARO, para fim de <b>CONCESSÃO DE CERTIFICADO DE REGISTRO</b> no Exército Brasileiro que o local de guarda do meu acervo de <b>ATIRADOR DESPORTIVO</b> atende as condições de segurança previstas no anexo F da portaria 150 - COLOG/2019.

<br><br><br><br><br><br><BR><BR>
<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10"><font size="2">
'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.<br><br>

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10"><font size="2">
_______________________________________________<br><B>
'.utf8_decode($cliente->nome).'</b><br><b>CPF:</b>'.formataCPFCNPJ($cliente->cpf).'<br>

<br><br><br><br><br><br>

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"cr", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>