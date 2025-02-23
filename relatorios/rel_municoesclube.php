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



	include_once ("../config/conecta_rel.php");

	include_once ("../config/cabecalho.php");



	$html = '<table border=1  width="100%"';	

	$html .= '<thead>';

	$html .= '<tr>';

	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;MAT</th>';

	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;ATIRADOR(A)</th>';

	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;DATA</th>';	

	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;EVENTO</th>';

	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;CALIBRE</th>';

	$html .= '<th bgcolor="#CCCCCC"><div align="left">&nbsp;QTDE</th>';

	$html .= '</tr>';

	$html .= '</thead>';

	$html .= '<tbody>';

	

	$result_transacoes = "

		SELECT 

			a.*,

			b.nome

		FROM 

			tab_municoesclube a,

			tab_membros b

		WHERE

			a.matricula = b.matricula

		order by 

			a.matricula

	";

	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);

	while($row_transacoes = mysqli_fetch_assoc($resultado_trasacoes)){



	$html .= '<tr><td>&nbsp;'.$row_transacoes['matricula'] ."</td>";

    $html .= '<td>&nbsp;'.$row_transacoes['nome'] ."</td>";

    $html .= '<td>&nbsp;'.date('d/m/Y', strtotime($row_transacoes['compra_datacadastro'])) . "</td>";

    $html .= '<td>&nbsp;'.$row_transacoes['compra_descricao'] . "</td>";

    $html .= '<td>&nbsp;'.$row_transacoes['compra_calibre'] . "</td>";

    $html .= '<td>&nbsp;'.$row_transacoes['compra_qtdecalibre'] . "</td>";

    $html .= "</tr>";



	}



	$html .= '</tbody>';

	$html .= '</table';



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

                                            RELAÇÃO DE MEMBROS

*****************************************************************************************************

-->



<p align=center style="line-height: 120%; margin-left: 15; margin-right: 15">

'.PDF_CABECALHO.'



<h3 style="text-align: center;">MUNI&Ccedil;&Otilde;ES RECARREGADAS PARA ATIRADORES</h3>



'. $html .'



	'));



	//Renderizar o html

	$dompdf->render();



	//Exibibir a pÃ¡gina

	$dompdf->stream(

		"relacao_municoes_recarregadas", 

		array(

			"Attachment" => false //Para realizar o download somente alterar para true

		)

	);

?>