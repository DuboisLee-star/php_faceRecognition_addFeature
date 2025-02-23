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
                                        P R O C U R A Ç Ã O
*****************************************************************************************************
-->

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br>

<h3 style="text-align: center;">P R O C U R A &Ccedil; &Atilde; O</h3><br><br>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><b>POR MEIO DO PRESENTE INSTRUMENTO DE PROCURA&Ccedil;&Atilde;O</b><br>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">
<b>OUTORGANTE</b> '.utf8_decode($cliente->nome).', natural de '.utf8_decode($cliente->naturalidade).', '.utf8_decode($cliente->nacionalidade).', '.utf8_decode($cliente->estadocivil).', CPF nº: '.formataCPFCNPJ($cliente->cpf).', Identidade nº '.utf8_decode($cliente->identidade).', '.utf8_decode($cliente->orgaouf).', nascido em  '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente à rua '.utf8_decode($cliente->endereco).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).'.</p>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><b>OUTORGADO</b> _____________________________________________________________, natural de ____________________, nacionalidade __________________, estado civil ________________, RG nº: ________________________, CPF nº: ________.________.________-______, domiciliado à rua _____________________________________________________________ N° ___________, Bairro: _________________________________________________, CEP: _______._______-_____, cidade ______________________________ e UF __________.</p>

<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><b>PODERES</b> Ortogando-lhes amplos PODERES, com fins específicos para entregar e retirar documentos no SFPC do 16º Batalhão de Infantaria Motorizada de Natal-RN, podendo o referido procurador resolver para atos que forem necessários ao bom e fiel cumprimento deste mandato, junto ao <b>Exercito Brasileiro.</b>.</p><br><br><br>

<p align=center style="line-height: 150%; margin-left: 15; margin-right: 15">
'.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).', _____ de ______________ de ________.</p><br>

<p align=center style="line-height: 150%; margin-left: 15; margin-right: 15">
_____________________________________________<br>
<b>Outorgante:</b> '.utf8_decode($cliente->nome).'<br><b>

</p>

	'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"procuracao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>