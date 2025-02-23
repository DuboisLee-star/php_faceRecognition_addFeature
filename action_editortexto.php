<?php 
	require('config/config.php');
	require_once("config/cabecalho.php");
	require_once("config/assinatura6.php");

	// Atribui uma conexão PDO
	$conexao = conexao::getInstance();

	// Recebe os dados enviados pela submissão
	$acao            = (isset($_POST['acao'])) ? $_POST['acao'] : '';
	$id              = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
	$atirador        = (isset($_POST['atirador'])) ? $_POST['atirador'] : '';
	$titulo          = (isset($_POST['titulo'])) ? $_POST['titulo'] : '';
	$texto           = (isset($_POST['texto'])) ? $_POST['texto'] : '';
	$delete          = (isset($_GET['del'])) ? true : false;
	
	// excluir texto
	if($delete){
		
		$sql = "DELETE FROM tab_editortexto WHERE id = :id";
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':id', $id);
		
		$retorno = $stm->execute();
		
		if($retorno){
			echo "<script>alert('Texto excluído com sucesso.'); window.location='editortexto.php';</script>";
		}else{
			echo "<script>alert('Não foi possivel excluir este texto.'); window.location='editortexto.php';</script>";
		}
		
		exit();
	}
	
	// inclui novo texto
	if($acao == 'incluir'){
		
		$sql = "INSERT INTO tab_editortexto
			(
				titulo,
				texto,
				datacadastro
			) VALUES (
				:titulo,
				:texto,
				:datacadastro
			)
		";
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':titulo', $titulo);
		$stm->bindValue(':texto', base64_encode($texto));
		$stm->bindValue(':datacadastro', date('Y-m-d H:i:s'));
		
		$retorno = $stm->execute();
		
	}
	
	if($acao == 'editar'){
		
		$sql = " 
			UPDATE 
				tab_editortexto 
			SET
				titulo = :titulo,
				texto = :texto
			WHERE
				id = :id
		";
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':titulo', $titulo);
		$stm->bindValue(':texto', base64_encode($texto));
		$stm->bindValue(':id', $id);
		
		$retorno = $stm->execute();
		
	}
	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("relatorios/dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF(array('enable_remote' => true));
	
	// pega dados do atirador
	if($atirador != ''){
		$conexao = conexao::getInstance();
		$sql = 'SELECT * FROM tab_membros WHERE matricula = :matricula order by nome ASC';
		$stm = $conexao->prepare($sql);
		$stm->bindValue(':matricula', $atirador);
		$stm->execute();
		$atirador = $stm->fetch(PDO::FETCH_OBJ);
	}
	
	$array_de = array(
		'{matricula}',
		'{nome}',
		'{cpf}',
		'{cr}'
	);
	$array_para = array(
		isset($atirador->matricula) ? $atirador->matricula : '',
		isset($atirador->nome) ? $atirador->nome : '',
		isset($atirador->cpf) ? $atirador->cpf : '',
		isset($atirador->cr) ? $atirador->cr : ''
	);
	
	$html_texto = str_replace($array_de, $array_para, $texto);
	
	$dompdf->load_html('<style>table{border-collapse: collapse;} td {border: 1px solid #000000; font-size: 12px;}</style>'.PDF_CABECALHO.'<table width="100%"><tr><td style="border: none !important">'.$html_texto.'<br>'.PDF_ASSINA.'</td></tr></table>');

	//Renderizar o html
	$dompdf->render();
	// exit();
	//Exibibir a pÃ¡gina
	/******$dompdf->stream(
		"declaracao_filiacao", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);********/

	$documento = base64_encode($dompdf->output());
	
	/* cria documento assinado */
	require_once($_SERVER['DOCUMENT_ROOT'].'/autentique/autentique.class.php');
    $aut = new AutentiqueH();
    $aut->tipo_documento = utf8_encode('Texto').' '.$cliente->matricula.' '.date('d/m/Y H:i:s');
    $aut->posicao_assinatura = array(70, 85, 1);
	$aut->criar_documento($documento);
	$aut->output();

?>