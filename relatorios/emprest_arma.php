<?php
//**************************************************
// função para formatar CPF e CNPJ | Tiago Moselli
//**************************************************
function formataCPFCNPJ($value) {
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } 

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

//**************************************************

include_once("../config/conexao.php");

// Recebe o id do cliente via GET
$id_cliente = isset($_GET['id']) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)) {

    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql = 'SELECT * FROM tab_membros WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id_cliente);
    $stm->execute();
    $cliente = $stm->fetch(PDO::FETCH_OBJ);

    if (!empty($cliente)) {
        // Formata a data no formato nacional
        $array_data = explode('-', $cliente->data_nascimento);
        $data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];
    }
}

$html = '<table border=1>';
$html .= '<tr><td>' . utf8_encode($cliente->matricula) . '</td></tr>';
$html .= '</tbody>';
$html .= '</table>';

// Referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// Include autoloader
require_once("dompdf/autoload.inc.php");

// Criando a Instancia
$dompdf = new Dompdf();

// Carrega seu HTML
$dompdf->load_html('
<!--
*****************************************************************************************************
                                        DECLARAÇÃO EMPRÉSTIMO DE ARMA
*****************************************************************************************************
-->
<br><br>
<h3 style="text-align: center;">DECLARA&Ccedil;&Atilde;O DE EMPRÉSTIMO DE ARMA</h3><br>
<h4 style="text-align: center;">§1º, §2º e §3º, art. 91 da Portaria nº 51- COLOG, de 08/Set/15<br><br>
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu <b>' . utf8_encode($cliente->emprest_nome1) . '</b>, Certificado de Registro nº <b>' . utf8_encode($cliente->emprest_cert1) . '</b>, da <b>' . utf8_encode($cliente->emprest_rm1) . '</b> Região Militar, atirador praticante e devidamente registrado no Exército Brasileiro, DECLARO para os devidos fins de direito, conforme o §1º, §2º e §3º, art. 91 da Portaria nº 51-COLOG, de 08 de Setembro de 2015, junto ao Exército Brasileiro, para fins de cessão de uso de arma de fogo, que estou cedendo arma de minha propriedade para que o <b>' . utf8_encode($cliente->emprest_nome2) . '</b>, Certificado de Registro nº <b>' . utf8_encode($cliente->emprest_cert2) . '</b>, da <b>' . utf8_encode($cliente->emprest_rm2) . '</b> Região Militar, participe de Campeonatos e treinamentos, por tempo indeterminado com a arma de minha propriedade, sempre com sua presença e exclusivamente em estandes de tiro.
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Descrição das armas a serem cedidas:</p>
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">I – Tipo: <b>' . utf8_encode($cliente->emprest_tipo) . '</b>, Marca: <b>' . utf8_encode($cliente->emprest_marca) . '</b>, Calibre: <b>' . utf8_encode($cliente->emprest_calibre) . '</b>, número de série: <b>' . utf8_encode($cliente->emprest_numserie) . '</b>, SIGMA: <b>' . utf8_encode($cliente->emprest_sigma) . '</b></p>
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Esta declaração tem validade durante campeonato, treinamentos, compra de insumos e equipamento de recarga de munições.</p>
<p align=justify style="line-height: 100%; margin-left: 25; margin-right: 15"><font size="2">Portaria 51, COLOG /2015:<br>Art. 91.<br>§1o O atirador desportivo poderá adquirir equipamentos de recarga para uso exclusivo no tiro desportivo.<br>§2o As munições, os insumos e os equipamentos de recarga devem corresponder às armas apostiladas no CR do atirador desportivo, ressalvado o previsto no § 3º deste artigo.<br>§3o No requerimento utilizado pelo atirador desportivo para informar que utiliza arma da entidade de tiro ou de outro atirador desportivo deve ser registrado o número SIGMA e anexada declaração do proprietário da arma. Essa declaração será assinada pelo Presidente ou seu substituto legal (no caso de entidade de tiro) ou pelo proprietário da arma (no caso de atirador desportivo), com reconhecimento de firma em cartório.</font></p><br><br>
<p align=center>' . utf8_encode($cliente->emprest_cidade) . '/' . utf8_encode($cliente->emprest_estado) . ', _______ de ___________________ de ________.</p><br>
<p align=center>_____________________________________________<br><b>' . utf8_encode($cliente->emprest_nome1) . '</b><br><b>CR:</b>&nbsp;' . utf8_encode($cliente->emprest_cert1) . ' - ' . utf8_encode($cliente->emprest_rm1) . '&nbsp;RM<br>
</p>
');

// Renderizar o html
$dompdf->render();

// Exibir a página
$dompdf->stream(
    "emprestimo_arma",
    array(
        "Attachment" => false // Para realizar o download somente alterar para true
    )
);
?>
<?php
//**************************************************
// função para formatar CPF e CNPJ | Tiago Moselli
//**************************************************
function formataCPFCNPJ($value) {
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } 

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

//**************************************************

include_once("../config/conexao.php");

// Recebe o id do cliente via GET
$id_cliente = isset($_GET['id']) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)) {

    // Captura os dados do cliente solicitado
    $conexao = conexao::getInstance();
    $sql = 'SELECT * FROM tab_membros WHERE id = :id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id_cliente);
    $stm->execute();
    $cliente = $stm->fetch(PDO::FETCH_OBJ);

    if (!empty($cliente)) {
        // Formata a data no formato nacional
        $array_data = explode('-', $cliente->data_nascimento);
        $data_formatada = $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];
    }
}

$html = '<table border=1>';
$html .= '<tr><td>' . utf8_encode($cliente->matricula) . '</td></tr>';
$html .= '</tbody>';
$html .= '</table>';

// Referenciar o DomPDF com namespace
use Dompdf\Dompdf;

// Include autoloader
require_once("dompdf/autoload.inc.php");

// Criando a Instancia
$dompdf = new Dompdf();

// Carrega seu HTML
$dompdf->load_html('
<!--
*****************************************************************************************************
                                        DECLARAÇÃO EMPRÉSTIMO DE ARMA
*****************************************************************************************************
-->
<br><br>
<h3 style="text-align: center;">DECLARA&Ccedil;&Atilde;O DE EMPRÉSTIMO DE ARMA</h3><br>
<h4 style="text-align: center;">§1º, §2º e §3º, art. 91 da Portaria nº 51- COLOG, de 08/Set/15<br><br>
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Eu <b>' . utf8_encode($cliente->emprest_nome1) . '</b>, Certificado de Registro nº <b>' . utf8_encode($cliente->emprest_cert1) . '</b>, da <b>' . utf8_encode($cliente->emprest_rm1) . '</b> Região Militar, atirador praticante e devidamente registrado no Exército Brasileiro, DECLARO para os devidos fins de direito, conforme o §1º, §2º e §3º, art. 91 da Portaria nº 51-COLOG, de 08 de Setembro de 2015, junto ao Exército Brasileiro, para fins de cessão de uso de arma de fogo, que estou cedendo arma de minha propriedade para que o <b>' . utf8_encode($cliente->emprest_nome2) . '</b>, Certificado de Registro nº <b>' . utf8_encode($cliente->emprest_cert2) . '</b>, da <b>' . utf8_encode($cliente->emprest_rm2) . '</b> Região Militar, participe de Campeonatos e treinamentos, por tempo indeterminado com a arma de minha propriedade, sempre com sua presença e exclusivamente em estandes de tiro.
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Descrição das armas a serem cedidas:</p>
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">I – Tipo: <b>' . utf8_encode($cliente->emprest_tipo) . '</b>, Marca: <b>' . utf8_encode($cliente->emprest_marca) . '</b>, Calibre: <b>' . utf8_encode($cliente->emprest_calibre) . '</b>, número de série: <b>' . utf8_encode($cliente->emprest_numserie) . '</b>, SIGMA: <b>' . utf8_encode($cliente->emprest_sigma) . '</b></p>
<p align=justify style="line-height: 150%; margin-left: 15; margin-right: 15">Esta declaração tem validade durante campeonato, treinamentos, compra de insumos e equipamento de recarga de munições.</p>
<p align=justify style="line-height: 100%; margin-left: 25; margin-right: 15"><font size="2">Portaria 51, COLOG /2015:<br>Art. 91.<br>§1o O atirador desportivo poderá adquirir equipamentos de recarga para uso exclusivo no tiro desportivo.<br>§2o As munições, os insumos e os equipamentos de recarga devem corresponder às armas apostiladas no CR do atirador desportivo, ressalvado o previsto no § 3º deste artigo.<br>§3o No requerimento utilizado pelo atirador desportivo para informar que utiliza arma da entidade de tiro ou de outro atirador desportivo deve ser registrado o número SIGMA e anexada declaração do proprietário da arma. Essa declaração será assinada pelo Presidente ou seu substituto legal (no caso de entidade de tiro) ou pelo proprietário da arma (no caso de atirador desportivo), com reconhecimento de firma em cartório.</font></p><br><br>
<p align=center>' . utf8_encode($cliente->emprest_cidade) . '/' . utf8_encode($cliente->emprest_estado) . ', _______ de ___________________ de ________.</p><br>
<p align=center>_____________________________________________<br><b>' . utf8_encode($cliente->emprest_nome1) . '</b><br><b>CR:</b>&nbsp;' . utf8_encode($cliente->emprest_cert1) . ' - ' . utf8_encode($cliente->emprest_rm1) . '&nbsp;RM<br>
</p>
');

// Renderizar o html
$dompdf->render();

// Exibir a página
$dompdf->stream(
    "emprestimo_arma",
    array(
        "Attachment" => false // Para realizar o download somente alterar para true
    )
);
?>