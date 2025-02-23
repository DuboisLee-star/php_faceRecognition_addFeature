<?php

include "config/config.php";

// Check user login or not
if (!isset($_SESSION['uname'])) {
  header('Location: index.php');
}

// logout
if (isset($_POST['but_logout'])) {
  session_destroy();
  header('Location: index.php');
}

function restante($data)
{

  $date1 = new DateTime(date('Y-m-d'));
  $date2 = new DateTime($data); // YYYY-MM-DD
  $interval = $date1->diff($date2);
  return $interval->days;
}

?>
<?php

require 'config/conexao.php';

// Recebe o id do cliente do cliente via GET
$id_cliente = (isset($_GET['id'])) ? $_GET['id'] : '';

// Valida se existe um id e se ele é numérico
if (!empty($id_cliente) && is_numeric($id_cliente)) :

  // Captura os dados do cliente solicitado
  $conexao = conexao::getInstance();

  $sql = 'SELECT m.cpf, m.nome, m.email, m.data_nascimento, m.categoria as codigo, a.*
  FROM tab_membros AS m
  LEFT JOIN tab_autentique_membros AS a ON a.membro_id = m.id
  WHERE m.id = :id';

  $stm = $conexao->prepare($sql);
  $stm->bindValue(':id', $id_cliente);
  $stm->execute();
  $cliente = $stm->fetch(PDO::FETCH_OBJ);

endif;

//Novo Registro
if (isset($_POST['pagamento'])) {
  $pagamento = $_POST['pagamento'];
  $token = $_POST['token'];
  $email = $_POST['email'];
  
  if($pagamento != 'Não solicitado') {

    $conexao = conexao::getInstance();
    $sql = $_POST['autentique_id'] != '' ? 
    'UPDATE tab_autentique_membros SET status_pgto = :pagamento, token = :token, email = :email WHERE membro_id = :id' : 
    'INSERT INTO tab_autentique_membros (status_pgto, token, membro_id, email) VALUES (:pagamento, :token, :id, :email)';

    $stm = $conexao->prepare($sql);
    $stm->bindValue(':pagamento', $pagamento);
    $stm->bindValue(':token', $token);
    $stm->bindValue(':id', $id_cliente);
    $stm->bindValue(':email', $email);
    $retorno = $stm->execute();

    if ($retorno) {
      echo "<script>alert('Dados salvos com sucesso!');</script>";
      echo "<script>window.location.href = 'assinatura_digital.php?id=$id_cliente';</script>";
    } else {
      echo "<script>alert('Erro ao salvar os dados!');</script>";
    }
  } else {
    echo "<script>alert('Selecione uma opção (Pago ou Pendente!)');</script>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <link rel="shortcut icon" href="img/favicon.png">

  <title>ADM</title>

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- bootstrap theme -->
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <!--external css-->
  <!-- font icon -->
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- Custom styles -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
  <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->
  <script>
    function formatar(mascara, documento) {
      var i = documento.value.length;
      var saida = mascara.substring(0, 1);
      var texto = mascara.substring(i)

      if (texto.substring(0, 1) != saida) {
        documento.value += texto.substring(0, 1);
      }

    }
  </script>

</head>

<body>
  <!-- container section start -->
  <section id="container" class="">
    <!--header start-->

    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="painel.php" class="logo">ADM <span class="lite">CLUBE</span></a>
      <!--logo end-->

      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
          </li>
        </ul>
        <!--  search form end -->
      </div>

      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">
          <li class="dropdown">
            <form method='post' action=""><input type="submit" class="btn btn-danger btn-sm" value="SAIR" name="but_logout"></form>
          </li>
          <!-- user login dropdown end -->
        </ul>
        <!-- notificatoin dropdown end-->
      </div>
    </header>
    <!--header end-->


    <!-- menu lateral inicio -->

    <?php include 'menu_lateral_esq.php'; ?>

    <!-- menu lateral fim -->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-book" aria-hidden="true"></i><?= $cliente->nome ?></h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="painel.php">Home</a></li>
              <li><i class="fa fa-bars"></i>Matrícula > <?= $cliente->matricula ?></li>
            </ol>
          </div>
        </div>

        <fieldset>

          <?php if (empty($cliente)) : ?>
            <h3 class="text-center text-danger">Cliente não encontrado!</h3>
          <?php else : ?>

            <form action="" method="post">
              <input type="hidden" name="autentique_id" value="<?= $cliente->id ?>">

              <div class="row">
                <div class="col-lg-12">
                  <section class="panel">
                    <header class="panel-heading">
                      Dados da assinatura eletrônica
                    </header>
                    <div class="panel-body">
                      <div class="form">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Campo</th>
                              <th>Dados</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Pagamento</td>
                              <td>
                                <select class="form-control" name="pagamento" id="pagamento">
                                  <?php if (empty($cliente->status_pgto)) : ?>
                                  <option value="Não solicitado" <?= ($cliente->status_pgto == '') ? 'selected' : '' ?>>Não solicitado</option>
                                  <?php endif; ?>
                                  <option value="Pendente" <?= ($cliente->status_pgto == 'Pendente') ? 'selected' : '' ?>>Pendente</option>
                                  <option value="Pago" <?= ($cliente->status_pgto == 'Pago') ? 'selected' : '' ?>>Pago</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Token</td>
                              <td>
                                <input type="text" class="form-control" name="token" id="token" value="<?= $cliente->token ?>">
                              </td>
                            </tr>
                            <tr>
                              <td>E-mail</td>
                              <td>
                                <input type="email" class="form-control" name="email" id="email" value="<?= $cliente->email ?>">
                              </td>
                            </tr>

                          </tbody>
                        </table>
                  </section>
                </div>
              </div>
              
              <button type="submit" class="btn btn-info" id='botao' onclick="salva()">Salvar</button>
            </form>
          <?php endif; ?>
        </fieldset>

        <!--main content end-->
        <!--------------------------------------------------------- page end-->
      </section>
    </section>
    <!--main content end-->
    <div class="text-center">
      <div class="credits">
        <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://bootstrapmade.com/license/
            Purchase the pro version form: https://bootstrapmade.com/buy/?theme=NiceAdmin
          -->
        by <a href="https://hostmarq.com.br/">HOSTMARQ</a><br><br>
      </div>
    </div>
  </section>


  <!-- container section end -->
  <!-- javascripts -->

  <script src="https://code.jquery.com/jquery-3.3.1.min.js">
  </script>

  <script>
    const selectorAll = document.querySelectorAll.bind(document);
    const id = document.getElementById.bind(document);

    function mascara(o, f) {
      obj = o;
      fun = f;
      setTimeout("execmascara()", 1);
    }

    function execmascara() {
      obj.value = fun(obj.value);
    }

    function validaCep(v) {
      v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
      v = v.replace(/^(\d{5})(\d{3})$/g, "$1-$2");
      return v;
    }


    window.onload = () => {

      let cep = id("cep");
      cep.onkeyup = function() {
        mascara(this, validaCep);
      };
    };

    function limpa_formulário_cep() {
      //Limpa valores do formulário de cep.
      id("rua").value = "";
      id("bairro").value = "";
      id("cidade").value = "";
      id("siglauf").value = "";

    }

    function meu_callback(conteudo) {
      if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        id("rua").value = conteudo.logradouro;
        id("bairro").value = conteudo.bairro;
        id("cidade").value = conteudo.localidade;
        id("siglauf").value = conteudo.uf;

      } else {
        //end if.
        //CEP não Encontrado.
        limpa_formulário_cep();
        respostaCep.style.display = "block";
        respostaCep.innerHTML = "CEP não encontrado.";
      }
    }

    function pesquisacep(valor) {
      //Preenche os campos com "..." enquanto consulta webservice.
      /**
      document.getElementById('rua').value="...";
      document.getElementById('bairro').value="...";
      document.getElementById('cidade').value="...";
      document.getElementById('uf').value="...";
      document.getElementById('ibge').value="...";
      */
      //Nova variável "cep" somente com dígitos.
      let cep = valor.replace(/\D/g, "");
      //Verifica se campo cep possui valor informado.
      if (cep != "") {
        //Expressão regular para validar o CEP.
        let validacep = /^[0-9]{8}$/;
        //Valida o formato do CEP.
        if (validacep.test(cep)) {
          //Cria um elemento javascript.
          let script = document.createElement("script");
          //Sincroniza com o callback.
          script.src =
            "https://viacep.com.br/ws/" + cep + "/json/?callback=meu_callback";
          //Insere script no documento e carrega o conteúdo.
          document.body.appendChild(script);
          respostaCep.style.display = "none";
          respostaCep.innerHTML = "";
        } else {
          //end if.
          //cep é inválido.
          limpa_formulário_cep();
          respostaCep.style.display = "block";
          respostaCep.innerHTML = "Formato de CEP inválido.";
        }
      } else {
        //end if.
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
      }
    }

    cadastro.cep.onblur = function() {
      var cep = id("cep");
      pesquisacep(cep.value);
    };
  </script>

  <script type="text/javascript">
    $("#segundo_cep").focusout(function() {
      //Início do Comando AJAX
      $.ajax({
        //O campo URL diz o caminho de onde virá os dados
        //É importante concatenar o valor digitado no CEP
        url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
        //Aqui você deve preencher o tipo de dados que será lido,
        //no caso, estamos lendo JSON.
        dataType: 'json',
        //SUCESS é referente a função que será executada caso
        //ele consiga ler a fonte de dados com sucesso.
        //O parâmetro dentro da função se refere ao nome da variável
        //que você vai dar para ler esse objeto.
        success: function(resposta) {
          //Agora basta definir os valores que você deseja preencher
          //automaticamente nos campos acima.
          $("#segundo_rua").val(resposta.logradouro);

          $("#segundo_bairro").val(resposta.bairro);
          $("#segundo_cidade").val(resposta.localidade);
          $("#segundo_estado").val(resposta.uf);
          //Vamos incluir para que o Número seja focado automaticamente
          //melhorando a experiência do usuário
          $("#segundo_num").focus();
        }
      });
    });
    const habilitaWebcam = () => {

      if (!cameraOn) {
        $("#screenshots").html('');
        $("#image_webcam").val('');
        webCamRequired = true;
        playVideoStream();
        $("#btnwebcam").addClass('btn-danger').html('<i class="fa fa-camera"></i> Desabilitar Webcam');
        $("#btncaptura").fadeIn(100);
      } else {
        webCamRequired = false;
        stopVideoStream();
        $("#btnwebcam").removeClass('btn-danger').html('<i class="fa fa-camera"></i> Habilitar Webcam');
        $("#btncaptura").fadeOut(100);
      }

    }
    const capturaWebcam = () => {
      if (cameraOn) {

        const img = document.createElement("img");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext("2d").drawImage(video, 0, 0);
        var image = canvas.toDataURL("image/png");
        $("#screenshots").html('<img src="' + image + '" width="235">');
        habilitaWebcam();
        $(".thumbnail").fadeOut(100);
        $("#image_webcam").val(image);

      } else {
        alert('Webcam não habilitada.');
      }
    }
  </script>

  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
  <script>
    // lib webcam
    cameraOn = false;
    webCamRequired = false;

    if (
      !"mediaDevices" in navigator ||
      !"getUserMedia" in navigator.mediaDevices
    ) {
      alert("A API da câmera não está disponível no seu navegador");

    } else {

      // get page elements
      const video = document.querySelector("#video");
      const btnPlay = document.querySelector("#btnPlay");
      const btnPause = document.querySelector("#btnPause");
      const btnScreenshot = document.querySelector("#btnScreenshot");
      const btnChangeCamera = document.querySelector("#btnChangeCamera");
      const screenshotsContainer = document.querySelector("#screenshots");
      const canvas = document.querySelector("#canvas");
      const devicesSelect = document.querySelector("#devicesSelect");

      // video constraints
      const constraints = {
        video: {
          width: {
            min: 300,
            ideal: 300,
            max: 300,
          },
          height: {
            min: 336,
            ideal: 336,
            max: 336,
          },
          zoom: true,
          aspectRatio: {
            max: 555
          }
        },
        aspectRatio: {
          max: 555
        }
      };

      // use front face camera
      let useFrontCamera = true;

      // current video stream
      var videoStream;

      // stop video stream
      function stopVideoStream() {
        if (videoStream) {
          videoStream.getTracks().forEach((track) => {
            track.stop();
            cameraOn = false;
            $(".thumbnail").fadeIn(100);
            $("video").fadeOut(100);
          });
        }
      }

      function playVideoStream() {
        initializeCamera();
      }

      // initialize
      async function initializeCamera() {

        stopVideoStream();
        constraints.video.facingMode = useFrontCamera ? "user" : "environment";

        try {
          videoStream = await navigator.mediaDevices.getUserMedia(constraints);
          video.srcObject = videoStream;

          cameraOn = true;
          $("video").fadeIn(100);
          $(".thumbnail").fadeOut(100);
        } catch (err) {
          alert(err);
          alert("Não foi possivel acessar webcam");
        }
      }

    }

    function salva() {

      if (cameraOn) {
        var img = $("#image_webcam").val();
        if (img == "") {
          alert('Foto da webcam não capturada.');
          return false;
        }
      }

    }
  </script>
  <style>
    .is-hidden {
      display: none;
    }

    #video {
      width: 100%;
      max-width: 300px;
      border-radius: 5px;
    }

    .img_face_webcam {
      position: absolute;
      width: 31%;
      z-index: 99999999;
      top: 47%;
      left: 33%;
      margin-top: -17%;
      margin-left: -70px;
    }
  </style>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2();
    });
  </script>
</body>

</html>