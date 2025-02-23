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

	

	// Recebe o id do cliente do cliente via GET

	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

    

	// Valida se existe um id e se ele � num�rico

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

	

	// Recebe o id do cliente do cliente via GET

	$id_procurador1 = (isset($_GET['a'])) ? $_GET['a'] : '';

	

	// Valida se existe um id e se ele � num�rico

	if (!empty($id_procurador1) && is_numeric($id_procurador1)):

	

	// Captura os dados do cliente solicitado

	$conexao = conexao::getInstance();

	$sql = 'SELECT *  FROM tab_procuradores WHERE id = :a';

	$stm = $conexao->prepare($sql);

	$stm->bindValue(':a', $id_procurador1);

	$stm->execute();

	$procurador1 = $stm->fetch(PDO::FETCH_OBJ);

	

	 endif; 

	 

	 

	 // Recebe o id do cliente do cliente via GET

	 $id_procurador2 = (isset($_GET['f'])) ? $_GET['f'] : '';

	 

	 // Valida se existe um id e se ele � num�rico

	 if (!empty($id_procurador2) && is_numeric($id_procurador2)):

	 

	 // Captura os dados do cliente solicitado

	 $conexao = conexao::getInstance();

	 $sql = 'SELECT *  FROM tab_procuradores WHERE id = :f';

	 $stm = $conexao->prepare($sql);

	 $stm->bindValue(':f', $id_procurador2);

	 $stm->execute();

	 $procurador2 = $stm->fetch(PDO::FETCH_OBJ);

	 

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

                                        P R O C U R A � � O

*****************************************************************************************************

-->



<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><br><br><br>



<h3 style="text-align: center;">P R O C U R A &Ccedil; &Atilde; O</h3><br>



<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><b></b>



<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">

<b>OUTORGANTE:</b> '.utf8_decode($cliente->nome).', natural de '.utf8_decode($cliente->naturalidade).', '.utf8_decode($cliente->nacionalidade).', '.utf8_decode($cliente->estadocivil).', CPF n&#x00B0; '.formataCPFCNPJ($cliente->cpf).', Identidade n&#x00B0;'.utf8_decode($cliente->identidade).', '.utf8_decode($cliente->orgaouf).', nascido em '.date('d/m/Y', strtotime($cliente->data_nascimento)).', filho de '.utf8_decode($cliente->pai).' e '.utf8_decode($cliente->mae).', residente e domiciliado na  '.utf8_decode($cliente->rua).' n&#x00B0; '.utf8_decode($cliente->numero).', bairro: '.utf8_decode($cliente->bairro).', Cep: '.utf8_decode($cliente->cep).', '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).'.</p>


<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><b>OUTORGADO(s):</b>

<p align=justify style="line-height: 150%; margin-left: 65; margin-right: 15">

<b>'.utf8_decode($procurador1->nome).'</b>, '.utf8_decode($procurador1->estadocivil).', '.utf8_decode($procurador1->nacionalidade).', residente e domiciliado na '.utf8_decode($procurador1->rua).' n&#x00B0; '.utf8_decode($procurador1->numero).', bairro: '.utf8_decode($procurador1->bairro).', Cep: '.utf8_decode($procurador1->cep).', Cidade '.utf8_decode($procurador1->cidade).'/'.utf8_decode($procurador1->siglauf).', CPF n&#x00B0; '.utf8_decode($procurador1->cpf).', Identidade '.utf8_decode($procurador1->identidade).'.<br><br> 

<b>'.utf8_decode($procurador2->nome).'</b>, '.utf8_decode($procurador2->estadocivil).', '.utf8_decode($procurador2->nacionalidade).', residente e domiciliado na '.utf8_decode($procurador2->rua).' n&#x00B0; '.utf8_decode($procurador2->numero).', bairro: '.utf8_decode($procurador2->bairro).', Cep: '.utf8_decode($procurador2->cep).', Cidade '.utf8_decode($procurador2->cidade).'/'.utf8_decode($procurador2->siglauf).', CPF n&#x00B0; '.utf8_decode($procurador2->cpf).', Identidade '.utf8_decode($procurador2->identidade).'</br></br>.</p>



<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15"><b>PODERES</b> Outorgando-lhe  amplos  poderes  para  o  foro  em  geral,  com  a  cl&aacute;usula  ad judicia  et  extra,  em  reparti&ccedil;&atilde;o  da  Pol&iacute;cia  Federal,  incluindo  protocolo, assinatura  e recebimento  de  documentos,  transfer&ecirc;ncia  de  propriedade  no  SINARM, transfer&ecirc;ncia para o SIGMA, apostilamento no SIGMA, guia de tr&acirc;nsito, porte, 2a  via,  furto/roubo/extravio,  apreens&atilde;o  e  recupera&ccedil;&atilde;o,  agindo  em  conjunto ou separadamente, dando tudo por bom, firme e valioso.</p><br><br><br>





<p align=center style="line-height: 120%; margin-left: 0; margin-right: 0"><font size="2">

'.utf8_decode($cliente->cidade).' ('.utf8_decode($cliente->siglauf).'), '.date('d').' de '.$meses[date('m')].' de '.date('Y').'.</p>





<div align=center style="line-height: 120%; margin-left: 10; margin-right: 10">

_______________________________________________<br>

OUTORGADO<br>

<b>'.utf8_decode($cliente->nome).'</b><br>

CPF:'.formataCPFCNPJ($cliente->cpf).'<br>



</p>



	'));



	//Renderizar o html

	$dompdf->render();



	//Exibibir a página

	$dompdf->stream(

		"procuracao", 

		array(

			"Attachment" => false //Para realizar o download somente alterar para true

		)

	);

?>