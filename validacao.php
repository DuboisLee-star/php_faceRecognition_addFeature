<?php

require 'config/conexao.php';

// Recebe o id do cliente via GET
$codigo = (isset($_GET['codigo'])) ? $_GET['codigo'] : '';
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
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

</head>
<body>

  <!-- container section start -->
  
  <section id="container" class="">
    <!--header start-->
    
    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"></div>
      </div>

      <!--logo start-->
     <a href="" class="logo">ADM <span class="lite">CLUBE</span></a>

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
          </li>

          <!-- user login dropdown end -->

        </ul>
        <!-- notificatoin dropdown end-->

      </div>
    </header>

    <style>
        #preload {
            display: none;
        }
        .loading {
            display: block;
        }
    </style>

    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-user" aria-hidden="true"></i>VERIFICAR AUTENCIDADE</h3>
            <form id="validationForm">
              <input class="breadcrumb" id="codigo" name="codigo" value="<?php echo $codigo; ?>" style="width:80%" type="text"><button type="submit" class="btn btn-info">VERIFICAR</button>
    </form>
        
    <div id="preload">Carregando...</div>
    <div id="resultado"></div>

<script>
    document.getElementById('validationForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let codigo = document.getElementById('codigo').value;
        let resultadoDiv = document.getElementById('resultado');
        let preloadDiv = document.getElementById('preload');

        resultadoDiv.textContent = '';
        preloadDiv.classList.add('loading');

        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'check_code.php?codigo=' + encodeURIComponent(codigo), true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                preloadDiv.classList.remove('loading');
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'Dados Validados com Sucesso!') {
                    let output = `<p>${response.status}</p>`;
                    if (response.data) {
                        // Formatando as datas para dd/mm/yyyy
                        let formatDate = (dateString) => {
                            let date = new Date(dateString);
                            return ("0" + date.getDate()).slice(-2) + '/' + ("0" + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();
                        };

                        let dataInicialFormatada = formatDate(response.data.data_inicial_visitante);
                        let dataFinalFormatada = formatDate(response.data.data_final_visitante);

                        output += `
                            <table class="table table-bordered">
                                <tr><th>Data Inicial</th><td>${dataInicialFormatada}</td></tr>
                                <tr><th>Data Final</th><td>${dataFinalFormatada}</td></tr>
                                <tr><th>Nome</th><td>${response.data.nome_visitante}</td></tr>
                                <tr><th>Evento</th><td>${response.data.evento}</td></tr>
                            </table>
                        `;
                        
                        if (response.armas.length > 0) {
                            let armasTable = '<h3>Detalhamento</h3><table class="table table-bordered"><tr><th>Tipo</th><th>Calibre</th><th>Sigma</th><th>Qtde</th><th>Modalidade</th><th>Pontos</th><th>Classificação</th></tr>';
                            response.armas.forEach(function(arma) {
                                armasTable += `<tr>
                                    <td>${arma.tipo}</td>
                                    <td>${arma.calibre}</td>
                                    <td>${arma.numsigma}</td>
                                    <td>${arma.qtdemunicoes}</td>
                                    <td>${arma.modalidade}</td>
                                    <td>${arma.pontos}</td>
                                    <td>${arma.classificacao}</td>                                    
                                </tr>`;
                            });
                            armasTable += '</table>';
                            output += armasTable;
                        } else {
                            output += `<p>Nenhuma informação adicional encontrada.</p>`;
                        }
                    } else {
                        output += `<p>Nenhuma informação adicional encontrada.</p>`;
                    }
                    resultadoDiv.innerHTML = output;
                } else {
                    resultadoDiv.innerHTML = `<p>${response.status}</p>`;
                }
            }
        };

        xhr.send();
    });
</script>

          </div>
        </div>

        <!--main content end-->
        <!------------------------------------------------------------------------------------------------------------ page end-->

      </section>
    </section>

    <!--main content end-->
    <div class="text-center">
      <div class="credits">Design by HOSTMARQ<br><br>
        </div>
    </div>
  </section>

  <!-- container section end -->
  <!-- javascripts -->
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>

  <!--custome script for all page-->
  <script src="js/scripts.js"></script>
</body>
</html>