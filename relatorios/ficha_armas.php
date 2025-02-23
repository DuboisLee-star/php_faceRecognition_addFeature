<?php	

	include_once("../config/conexao.php");
	include_once ("../config/cabecalho.php");

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
		
		echo '<tr><td>'.$cliente->matricula.'</td></tr>';
	
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
                                 FICHA DO 1o ARMAMENTO DO(A) ATIRADOR(A)
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">FICHA DE ARMAMENTO</h3>
						
<table cellpadding="0" cellspacing="0" border="0" width="550">
<tr>
<td align="rigth" valign="top" width="100"><img src="https://www.clubetirocarcara.com.br/fotos/'.$cliente->foto.'" height="100" width="100" id="foto-cliente" style="border-style: solid"></td>
<td valign="top" width="350">
								
								
<span style="line-height: 1.6;"><font size="2">

<b>MATRÍCULA:</b> '.utf8_decode($cliente->matricula).'<br>

<b>DATA FILIAÇÃO:</b> '.date('d/m/Y', strtotime($cliente->data_filiacao)).'<br>

<b>CR:</b> '.utf8_decode($cliente->cr).'<br>

<b>Validade CR:</b> '.date('d/m/Y', strtotime($cliente->validade_cr)).'<br>

<b>NOME:</b> '.utf8_decode($cliente->nome).'<br>

<hr>

<b>NUM. SÉRIE ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_serie_arma1).'<br>

<b>NUM. SIGMA ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_sigma_arma1).'<br>

<b>TIPO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->especie1).'<br>

<B>CALIBRE:</b>&nbsp;&nbsp;'.utf8_decode($cliente->calibre1).'<BR>

<b>MARCA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->marca1).'<BR>

<B>MODELO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->modelo1).'<BR>

<b>TIPO FUNCIONAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tp_funcionamento1).'<br>

<B>CAP. CARREGAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->cap_carregamento1).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos1).'<br>

<B>ACABAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->acabamento1).'<BR>

<b>NUM. RAIAS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_raias1).'<br>

<B>SENTIDO RAIA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->sentido_raia1).'<BR>

<b>TIPO ALMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tipo_alma1).' <br>

<B>COMPRIMENTO CANO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->comp_cano1).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos1).'<br>

<B>PAIS FABRICAÇÃO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->pais_fab1).'<BR>


		</span>

		</td>
		</tr>
		</table>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                 FICHA DO 2o ARMAMENTO DO(A) ATIRADOR(A)
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">FICHA DE ARMAMENTO</h3><br>

			<table cellpadding="0" cellspacing="0" border="0" width="550">
			<tr>
			<td align="rigth" valign="top" width="100"><img src="https://www.clubetirocarcara.com.br/fotos/'.$cliente->foto.'" height="100" width="100" id="foto-cliente" style="border-style: solid"></td>
			<td valign="top" width="350">
								
								
<span style="line-height: 1.6;"><font size="2">

<b>MATRÍCULA:</b> '.utf8_decode($cliente->matricula).'<br>

<b>DATA FILIAÇÃO:</b> '.date('d/m/Y', strtotime($cliente->data_filiacao)).'<br>

<b>CR:</b> '.utf8_decode($cliente->cr).'<br>

<b>Validade CR:</b> '.date('d/m/Y', strtotime($cliente->validade_cr)).'<br>

<b>NOME:</b> '.utf8_decode($cliente->nome).'<br>

<hr>

<b>NUM. SÉRIE ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_serie_arma2).'<br>

<b>NUM. SIGMA ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_sigma_arma2).'<br>

<b>TIPO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->especie2).'<br>

<B>CALIBRE:</b>&nbsp;&nbsp;'.utf8_decode($cliente->calibre2).'<BR>

<b>MARCA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->marca2).'<BR>

<B>MODELO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->modelo2).'<BR>

<b>TIPO FUNCIONAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tp_funcionamento2).'<br>

<B>CAP. CARREGAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->cap_carregamento2).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos2).'<br>

<B>ACABAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->acabamento2).'<BR>

<b>NUM. RAIAS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_raias2).'<br>

<B>SENTIDO RAIA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->sentido_raia2).'<BR>

<b>TIPO ALMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tipo_alma2).'<br>

<B>COMPRIMENTO CANO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->comp_cano2).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos2).'<br>

<B>PAIS FABRICAÇÃO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->pais_fab2).'<BR>


		</span>

		</td>
		</tr>
		</table>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                 FICHA DO 3o ARMAMENTO DO(A) ATIRADOR(A)
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">FICHA DE ARMAMENTO</h3><br>

			<table cellpadding="0" cellspacing="0" border="0" width="550">
			<tr>
			<td align="rigth" valign="top" width="100"><img src="https://www.clubetirocarcara.com.br/fotos/'.$cliente->foto.'" height="100" width="100" id="foto-cliente" style="border-style: solid"></td>
			<td valign="top" width="350">
								
								
<span style="line-height: 1.6;"><font size="2">

<b>MATRÍCULA:</b> '.utf8_decode($cliente->matricula).'<br>

<b>DATA FILIAÇÃO:</b> '.date('d/m/Y', strtotime($cliente->data_filiacao)).'<br>

<b>CR:</b> '.utf8_decode($cliente->cr).'<br>

<b>Validade CR:</b> '.date('d/m/Y', strtotime($cliente->validade_cr)).'<br>

<b>NOME:</b> '.utf8_decode($cliente->nome).'<br>

<hr>

<b>NUM. SÉRIE ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_serie_arma3).'<br>

<b>NUM. SIGMA ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_sigma_arma3).'<br>

<b>TIPO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->especie3).'<br>

<B>CALIBRE:</b>&nbsp;&nbsp;'.utf8_decode($cliente->calibre3).'<BR>

<b>MARCA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->marca3).'<BR>

<B>MODELO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->modelo3).'<BR>

<b>TIPO FUNCIONAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tp_funcionamento3).'<br>

<B>CAP. CARREGAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->cap_carregamento3).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos3).'<br>

<B>ACABAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->acabamento3).'<BR>

<b>NUM. RAIAS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_raias3).'<br>

<B>SENTIDO RAIA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->sentido_raia3).'<BR>

<b>TIPO ALMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tipo_alma3).'<br>

<B>COMPRIMENTO CANO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->comp_cano3).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos3).'<br>

<B>PAIS FABRICAÇÃO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->pais_fab3).'<BR>


		</span>

		</td>
		</tr>
		</table>

<div style="page-break-after: always"></div>

<!--
*****************************************************************************************************
                                 FICHA DO 4o ARMAMENTO DO(A) ATIRADOR(A)
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">FICHA DE ARMAMENTO</h3><br>

						
			<table cellpadding="0" cellspacing="0" border="0" width="550">
			<tr>
			<td align="rigth" valign="top" width="100"><img src="https://www.clubetirocarcara.com.br/fotos/'.$cliente->foto.'" height="100" width="100" id="foto-cliente" style="border-style: solid"></td>
			<td valign="top" width="350">
								
								
<span style="line-height: 1.6;"><font size="2">

<b>MATRÍCULA:</b> '.utf8_decode($cliente->matricula).'<br>

<b>DATA FILIAÇÃO:</b> '.date('d/m/Y', strtotime($cliente->data_filiacao)).'<br>

<b>CR:</b> '.utf8_decode($cliente->cr).'<br>

<b>Validade CR:</b> '.date('d/m/Y', strtotime($cliente->validade_cr)).'<br>

<b>NOME:</b> '.utf8_decode($cliente->nome).'<br>

<hr>

<b>NUM. SÉRIE ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_serie_arma4).'<br>

<b>NUM. SIGMA ARMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_sigma_arma4).'<br>

<b>TIPO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->especie4).'<br>

<B>CALIBRE:</b>&nbsp;&nbsp;'.utf8_decode($cliente->calibre4).'<BR>

<b>MARCA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->marca4).'<BR>

<B>MODELO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->modelo4).'<BR>

<b>TIPO FUNCIONAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tp_funcionamento4).'<br>

<B>CAP. CARREGAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->cap_carregamento4).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos4).'<br>

<B>ACABAMENTO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->acabamento4).'<BR>

<b>NUM. RAIAS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->num_raias4).'<br>

<B>SENTIDO RAIA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->sentido_raia4).'<BR>

<b>TIPO ALMA:</b>&nbsp;&nbsp;'.utf8_decode($cliente->tipo_alma4).'<br>

<B>COMPRIMENTO CANO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->comp_cano4).'<BR>

<b>QUANT. CANOS:</b>&nbsp;&nbsp;'.utf8_decode($cliente->quant_canos4).'<br>

<B>PAIS FABRICAÇÃO:</b>&nbsp;&nbsp;'.utf8_decode($cliente->pais_fab4).'<BR>


		</span>

		</td>
		</tr>
		</table>

			
		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"ficha_de_armamentos", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>