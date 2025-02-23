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

	include_once("../config/config.php");
	include_once ("../config/cabecalho.php");
	include_once ("../config/assinatura2.php");
	include_once ("../config/texto.php");

	// Recebe o id do cliente do cliente via GET
	$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';
	$calibre = (isset($_GET['calibre'])) ? $_GET['calibre'] : '';

	// Valida se existe um id e se ele é numérico
	if (!empty($id_cliente) && is_numeric($id_cliente)):

		// Captura os dados do cliente solicitado
		$conexao = conexao::getInstance();
		$sql1 = 'SELECT * FROM tab_membros WHERE id = :id';
		$stm = $conexao->prepare($sql1);
		$stm->bindValue(':id', $id_cliente);
		$stm->execute();
		$cliente = $stm->fetch(PDO::FETCH_OBJ);
		
		// Faz a conexão com o banco
        $conexao = conexao::getInstance();
        $sql2 = 'SELECT * FROM info_clube WHERE id = :id';
        $stm = $conexao->prepare($sql2);
        $stm->bindValue(':id', 1);
        $stm->execute();
        $clube = $stm->fetch(PDO::FETCH_OBJ);			
		
        // pega dados da habitualidade
         $sql3 = "SELECT matricula, data, evento, calibre, sigma, local, qtdemunicoes FROM tab_habitualidade 
         WHERE tipo_atirador = '' AND matricula = :matricula";
		 $sql3 .= " AND calibre = :calibre";
		 $sql3 .= " ORDER BY data DESC";
         
         $stm = $conexao->prepare($sql3);
         $stm->bindValue(':matricula', $cliente->matricula);
		 $stm->bindValue(':calibre', $calibre);
         $stm->execute();
         $habitualidade = $stm->fetchAll(PDO::FETCH_OBJ);

		if(!empty($cliente)):

			// Formata a data no formato nacional
			$array_data     = explode('-', $cliente->data_nascimento);
			$data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];

		endif;

	endif;

        $html = '<table border=1>';	
		$html .= '<tr><td>'.$cliente->matricula.'</td></tr>';
	
// html habitualidade
// Inicialize o contador
$contador = 1;

// Inicialize a variável que controla a quebra de página
$quebraPagina = false;

// HTML para habitualidade
$html_habitualidade = "";

if ($habitualidade) {
    foreach ($habitualidade as $idh => $value) {
        // Verifique se o contador atingiu o número 6 e se não houve quebra de página
        if ($contador == 6 && !$quebraPagina) {
            // Adicione código para quebrar a página
            $html_habitualidade .= '</table>'; // Fecha a tabela atual
            $html_habitualidade .= '<div style="page-break-before: always;"></div>'; // Quebra de página
            $html_habitualidade .= '<table border=1 cellspacing=0 cellpadding=5 width=100%>'; // Inicia uma nova tabela na nova página

            // Adicione tags e estilo da tabela
            $html_habitualidade .= '
                <tr>
                    <td align="center" width="5%" bgcolor="#C0C0C0"><font size="2"><b>Ordem</b></td>
                    <td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>Data</b></td>
                    <td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>SIGMA</b></td>
                    <td align="center" width="10%" bgcolor="#C0C0C0"><font size="2"><b>Qtde Munições</b></td>
                    <td align="center" width="55%" bgcolor="#C0C0C0"><font size="2"><b>Treinamento ou Competição<br>(Estadual, Distrital, Regional, Nacional ou Internacional)</b></td>
                </tr>
            ';

            $quebraPagina = true; // Atualize a variável para indicar que houve quebra de página
            // Não reinicie o contador aqui
        }

            $html_habitualidade .= '
            <tr>
                <td width="5%" align="center">&nbsp;<font size="2">' . $contador . '</td>                
                <td width="15%" align="center">&nbsp;<font size="2">' . date('d/m/Y', strtotime($habitualidade[$idh]->data)) . '</td>
                <td width="15%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->sigma) . '</td>
                <td width="10%" align="center">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->qtdemunicoes) . '</td>
                <td width="55%" align="left">&nbsp;<font size="2">' . utf8_decode($habitualidade[$idh]->local) . '</td>
            </tr>
        ';

        // Incrementa o contador sempre
        $contador++;


    }
}

	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true, 'enable_html5_parser'=>true));
	
	$dompdf->set_option('isHtml5ParserEnabled', true);
	
	// Carrega seu HTML
	$dompdf->load_html(utf8_encode('

<div align=center style="line-height: 120%; margin-left: 15; margin-right: 15">
'.PDF_CABECALHO.'
</div>
<div align="center"><font size="2"><b>COMPROVAÇÃO DE PARTICIPAÇÕES EM TREINAMENTOS E/OU COMPETIÇÕES DE TIRO</b><br>(H A B I T U A L I D A D E)<br><br><b>ANEXO E</b><br>(art. 35 do Decreto nº 11.615/2023)<br><br>

<div align="center"><font size="2">DADOS DA ENTIDADE DE TIRO DECLARANTE</div>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b>Nome:</b></td>
        <td width="50%" valign="top">'.utf8_decode($clube->clube_nome).'</td>
        <td width="25%" valign="top"><b>CNPJ:</b> '.utf8_decode($clube->clube_cnpj).'</td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b>Certificado de Registro</b></td>
        <td width="50%" valign="top">'.utf8_decode($clube->clube_cr).'</td>
        <td width="25%" valign="top"><b>Data:</b> </td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b>Endereço</b></td>
        <td width="75%" colspan="2" valign="top">'.utf8_decode($clube->clube_endereco).'</td>
    </tr>
</table>

<br>
<div align="center"><font size="2">DADOS DO ATIRADOR DESPORTIVO</div>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="30%" valign="top"><b><font size="2">Nome:</b></td>
        <td width="50%" valign="top"><font size="2">'.utf8_decode($cliente->nome).'</td>
        <td width="25%" valign="top"><b><font size="2">CPF:</b> '.utf8_decode($cliente->cpf).'</td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Certificado de Registro</b></td>
        <td width="50%" valign="top"><font size="2">'.utf8_decode($cliente->cr).'</td>
        <td width="25%" valign="top"><b><font size="2">Data:</b> '.date('d/m/Y', strtotime($cliente->validade_cr)).'</td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Endereço:</b></td>
        <td width="75%" colspan="2" valign="top"><font size="2">'.utf8_decode($cliente->rua).' nº '.utf8_decode($cliente->numero).', bairro: '.utf8_decode($cliente->bairro).', CEP '.utf8_decode($cliente->cep).' - '.utf8_decode($cliente->cidade).'/'.utf8_decode($cliente->siglauf).'</td>
    </tr>
    <tr>
        <td width="30%" valign="top"><b><font size="2">Filiação a Entidade de Tiro</b></td>
        <td width="50%" valign="top"><font size="2">'.$cliente->matricula.'</td>
        <td width="25%" valign="top"><b><font size="2">Data:</b> '.date('d/m/Y', strtotime($cliente->data_filiacao)).'</td>
    </tr>
</table>

<br>
<div align="center"><font size="2">DADOS DA HABITUALIDADE</div>

<div align="center">

	<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <tr>
        <td width="20%" valign="top"  colspan="3"><b><font size="2">Calibre de uso restrito</b></td>
        <td width="10%" valign="top" align="center"><font size="2">' . htmlspecialchars($_GET['calibre']) . '</td>
        <td width="25%" valign="top"><b><font size="2">Tipo de Evento:</b> ' . utf8_decode($habitualidade[$idh]->evento) . '</td>
    </tr>	
		<tr>
	     	<td align="center" width="5%" bgcolor="#C0C0C0"><font size="2"><b>Ordem</b></td>
	     	<td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>Data</b></td>
            <td align="center" width="15%" bgcolor="#C0C0C0"><font size="2"><b>SIGMA</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><font size="2"><b>Qtde Munições</b></td>             
			<td align="center" width="55%" bgcolor="#C0C0C0"><font size="2"><b>Treinamento ou Competição<br>(Estadual, Distrital, Regional, Nacional ou Internacional)</b></td>
		</tr>
		'.$html_habitualidade.'
	</table>
</div>
</div>

<br>
<div align=center style="margin-left: 0; margin-right: 0">
'.PDF_ASSINA.'
</div>

			
		'));

	//Renderizar o html
	$dompdf->render();
	$pagesCount = $dompdf->get_canvas()->get_page_number();
	
	// -------------------------------------------------------
	// grava log
	// -------------------------------------------------------
	$sql = 'INSERT INTO tab_declaracoes 
		(matricula, relatorio, data_emissao, ip_emissao) VALUES
		(:matricula, :relatorio, :data_emissao, :ip_emissao)
	';
	$stm = $conexao->prepare($sql);
	$stm->bindValue(':matricula', $cliente->matricula);
	$stm->bindValue(':relatorio', utf8_encode('Declaração Habitualidade'));
	$stm->bindValue(':data_emissao', date('Y-m-d H:i:s'));
	$stm->bindValue(':ip_emissao', $_SERVER['REMOTE_ADDR']);
	$stm->execute();
	// -------------------------------------------------------

	//Exibibir a pÃ¡gina
	/******$dompdf->stream(
		"declaracao_filiacao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);********/

	$documento = base64_encode($dompdf->output());
	/*header("Content-type:application/pdf");
	echo $dompdf->output();
	exit();*/
	
	/* cria documento assinado */
	require_once($_SERVER['DOCUMENT_ROOT'].'/autentique/autentique.class.php');
    $aut = new AutentiqueH();
    $aut->tipo_documento = utf8_encode('Habitualidade').' '.$cliente->matricula.' '.date('d/m/Y H:i:s');
    $aut->posicao_assinatura = array(70, 85, $pagesCount);
	$aut->criar_documento($documento);
	$aut->output();
	
	
?>