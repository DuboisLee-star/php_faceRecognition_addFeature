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
	
	$datainicial1 = DateTime::createFromFormat('d/m/Y',$_GET["datainicial"])->format("Y-m-d");
    $datafinal1 = DateTime::createFromFormat('d/m/Y',$_GET["datafinal"])->format("Y-m-d");


	//**************************************************

	include_once ("../config/conecta_rel.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura.php");

	$html = '<table border=1  width="100%"';	
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th bgcolor="#CCCCCC" width="10%"><div align="center"><font size="2">DATA</th>';
	$html .= '<th bgcolor="#CCCCCC" width="30%"><div align="center"><font size="2">DESCRIÇÃO</th>';
	$html .= '<th bgcolor="#CCCCCC" width="15%"><div align="center"><font size="2">ARMAMENTO</th>';
	$html .= '<th bgcolor="#CCCCCC" width="22%"><div align="center"><font size="2">ASSINATURA</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	
	$result_transacoes = "
	
	/*SELECT nome, cr, local, evento, tipo, calibre, serie, qtdemunicoes FROM tab_membros, tab_habitualidade WHERE matricula = :matricula ORDER BY datacadastro ASC*/
	
	
		select 
			a.*,
			b.cr,
			b.nome
		from 
			tab_habitualidade a,
			tab_membros b
		where
	        `datacadastro` BETWEEN '$datainicial1'  AND '$datafinal1' AND a.matricula = b.matricula
		order by
			a.datacadastro asc
	";

	// }
	
	$html = "";
	
	// Captura os dados do cliente solicitado
	$linha = 1;
	$page = 1;
	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
	while($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)){
		
		$html .= '
		    <p align=justify style="line-height: 150%; margin-left: 5; margin-right: 5">
			<table bgcolor="#000000" cellpadding="3" cellspacing="1" style="font-size: 10px;" width="100%">
				<tr>
					<td bgcolor="#C0C0C0" align="center"><b>DATA</b></td>
					<td bgcolor="#C0C0C0" align="center"><b>LOCAL/EVENTO</b></td>						
					<td bgcolor="#C0C0C0" align="center"><b>ATIRADOR(A)</b></td>
					<td bgcolor="#C0C0C0" align="center"><b>N&deg; SERIE</b></td>
					<td bgcolor="#C0C0C0" align="center"><b>TIPO</b></td>
					<td bgcolor="#C0C0C0" align="center"><b>CALIBRE</b></td>
					<td bgcolor="#C0C0C0" align="center"><b>QUANT</b></td>				

</tr>
				<tr>
					<td bgcolor="#CCCCFF" align="center"><b>'.date('d/m/Y ', strtotime($row_transacoes['datacadastro'])).'</b></td>
                    <td bgcolor="#ffffff" align="center"><b>'.utf8_decode($row_transacoes['local']).'</b>:<br>  '.utf8_decode($row_transacoes['evento']).'<br></td>		
					<td bgcolor="#ffffff" align="center"><b>N&deg; CR: '.$row_transacoes['cr'].' '.utf8_decode($row_transacoes['cr_visitante']).'<br></b> '.utf8_decode($row_transacoes['nome']).' '.utf8_decode($row_transacoes['nome_visitante']).'</td>
					<td bgcolor="#ffffff" align="center">'.$row_transacoes['serie'].'</td>
					<td bgcolor="#ffffff" align="center">'.$row_transacoes['tipo'].'</td>
					<td bgcolor="#ffffff" align="center">'.$row_transacoes['calibre'].'</td>
					<td bgcolor="#ffffff" align="center">'.$row_transacoes['qtdemunicoes'].'</td>

</tr>
			</table><br><br>
		';
		
		$linha++;
		if($linha == 6){
			$html .= '
				
			';
			$linha = 1;
			$page++;
		}
		
	}
	
	// echo $html;
	// exit();

	// $html .= '</tbody>';
	// $html .= '</table>';

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true));

	// Carrega seu HTML
	// $dompdf->page_text(50, 20, "Seite {PAGE_NUM}", Font_Metrics::get_font("sans-serif"), 10, array(0,0,0));
	$dompdf->load_html(utf8_encode('

<!--
*****************************************************************************************************
                                         RELAÇÃO DE MEMBROS
*****************************************************************************************************
-->

<p align=center style="line-height: 120%; margin-left: 10; margin-right: 10">
'.PDF_CABECALHO.'

<h3 style="text-align: center;">REGISTRO DE HABITUALIDADES</h3>

'. str_replace('XXX', $page, $html) .'

<div style="page-break-after: always"></div>


<style>
footer {position: absolute; bottom: 0;}
</style>


<br><br>
<p align=justify style="line-height: 130%; margin-left: 20; margin-right: 0">
<font size="2"><b>Portaria 150 COLOG</b><br><br>

Art 13. <br><br>

§1° Registros de habitualidade são anotações <b>permanentes</b> das entidades de prática ou de
administração de tiro que comprovam a presença do atirador desportivo no estande de tiro para
treinamento ou competição oficial.<br><br>

§2° Devem constar nessas anotações a <b>data</b>, o <b>nome</b> e o <b>registro</b> do atirador, o <b>evento</b> ou a
atividade, a <b>arma</b> (tipo e calibre), o <b>consumo</b> de munição (quantidade e calibre) e a <b>assinatura do
atirador</b> desportivo.<br><br>

§3° Os registros de habitualidade devem estar <b>disponíveis</b>, <b>acessíveis</b> e <b>facilmente
identificáveis</b>, a qualquer momento, quando solicitados pela fiscalização de produtos controlados. 
</p>
    
<footer>
<div class="pagenum-container"><span class="pagenum"></span></div>
</footer>
		'));

	//Renderizar o html
	$dompdf->render();

	//Exibibir a pÃ¡gina
	$dompdf->stream(
		"registro_de_habitualidades", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>