  
  <style>
  .modal .modal-dialog { width: 30%; }  
  @media(min-width:768px){  
  .modal .modal-dialog { width: 20%; }  
  
  } 
  </style>
  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Escolha um periodo</h5>
  </div>
  <div class="modal-body">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function(){  
  $('.data').on('keyup', function(){
  var $this = $(this);
  var mydate = $this.val();
  mydate = mydate.replace(/\D|\s/, '');  
  mydate = mydate.replace(/^(00)(.*)?/, '01$2');
  mydate = mydate.replace(/^([0-9]{2})(00)(.*)?/, '$101');
  mydate = mydate.replace(/^([3-9])([2-9])(.*)?/, '2$2');
  mydate = mydate.replace(/^(3[01])(02)(.*)?/, '29$2');
  mydate = mydate.replace(/^([0-9]{2})([2-9]|1[3-9])(.*)?/, '$112');
  mydate = mydate.replace(/^([0-9]{2})([0-9]{2})([0-9].*?)/, '$1/$2/$3');
  mydate = mydate.replace(/^([0-9]{2})([0-9])/, '$1/$2');    
  //ano bissexto
  var day = mydate.substr(0,2) || '01';
  var month = mydate.substr(3,2) || '01';
  var year = mydate.substr(6,4);
  if(year.length == 4 && day == '29' && month == '02' && (year % 4 != 0 || (year.substr(2,2) == '00' && year % 400 != 0))) {
  mydate = mydate.replace(/^29/,'28');
  }
  mydate = mydate.substr(0,10);
  $this.val(mydate);
  })
  })
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <form method="get" id="form" target="_blank" action="relatorios/rel_habitu.php" >
  <div class="row" >
  <div class="col"> 
  <label for="formGroupExampleInput">&nbsp;De:</label>
  <input type="text"  class="data form-control"  name="datainicial" value="" required>
  <label for="formGroupExampleInput">&nbsp;Até:</label>
  <input type="text" class="data form-control" name="datafinal" value="" required>
  </div> 
  </div>
  </form>
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
  <input type="submit"  onclick="document.getElementById('form').submit();" class="btn btn-primary" value="Abrir">
  
  </div>
  </div>
  </div>
  </div>
     <aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
    <ul class="sidebar-menu">
        <li class="">
            <a class="" href="painel.php">
            <i class="icon_house_alt"></i>
            <span>Home</span>
            </a>
         </li>
         
           <li class="sub-menu">
            <a href="javascript:;" class="">
            <i class="bi bi-webcam"></i>
            <span>FACIAL</span>
            <span class="menu-arrow arrow_carrot-right"></span>
            </a>
            <ul class="sub">
              <li><a class="" href="update_photo.php" target="_self">Capturar</a></li>
              <li><a class="" href="" target="_self">Presença</a></li>
              <li><a class="" href="" target="_self">Habitualidade</a></li> 
            </ul>
            </li>
            
         <li class="sub-menu">
            <a href="javascript:;" class="">
            <i class="fa fa-desktop" aria-hidden="true"></i>
            <span>Cadastro</span>
            <span class="menu-arrow arrow_carrot-right"></span>
            </a>
    <ul class="sub">
            <li><a class="" href="adminusers.php">Administradores</a></li>
            <li><a class="" href="cadastro.php">Associados</a></li> 
            <li><a class="" href="painel_opcoes_visitante.php">Visitantes</a></li> 
            <li><a class="" href="cadastro_procuradores.php">Procuradores</a></li>            
            <li><a class="" href="adminavisos.php">Avisos</a></li>             
            <li><a class="" href="cadastro_cursos.php">Cursos</a></li> 
            <li class="sub-menu">
            <ul class="sub">
    </ul>
        </li>

     </ul>
        </li>
			

<li class="sub-menu">
    <a href="javascript:;" class="">
        <i class="fa fa-usd" aria-hidden="true"></i>
        <span>Financeiro</span>
        <span class="menu-arrow arrow_carrot-right"></span>
    </a>
    <ul class="sub">
        <!-- Adicione o sub-tópico "$" com sua opção "Consultar" aqui -->
        <li class="sub-menu">
            <ul class="sub">
              <li><a class="" href="consultar_financeiro.php" >Consultar Finanças</a></li>
              <li><a class="" href="adminfinanceiro.php">Controle</a></li>                
              <li><a class="" href="relatorios/receber_mes_anterior.php" target="_blank">Receber mês anterior</a></li>               
              <li><a class="" href="relatorios/receber_mes_corrente.php" target="_blank">Receber mês corrente</a></li>
              <li><a class="" href="relatorios/receber_mes_seguinte.php" target="_blank">Receber mês seguinte</a></li> 
              <li><a class="" href="relatorios/rel_geral_valores2024.php" target="_blank">Valores 2024</a></li>
              <li><a class="" href="relatorios/rel_geral_valores2025.php" target="_blank">Valores 2025</a></li> 
              <li><a class="" href="cadastro_precos.php">Preços</a></li> 
              <li><a class="" href="comanda.php">Comanda</a></li> 
                <!-- Adicione mais opções $ aqui, se necessário -->
            </ul>
        </li>
    </ul>
</li>


			  
	
<li class="sub-menu">
    <a href="javascript:;" class="">
        <i class="icon_documents_alt"></i>
        <span>Relatórios</span>
        <span class="menu-arrow arrow_carrot-right"></span>
    </a>
    <ul class="sub">
        <li><a class="" href="relatorios/rel_aniversariantes.php" target="_blank">Aniversariantes</a></li>
        <li><a class="" href="relatorios/declaracoes.php" target="_blank">Declarações emitidas</a></li>
        <li><a class="" href="relatorios/rel_membros2.php" target="_blank">Lista de membros</a></li>
	    <li><a class="" href="relatorios/membros_ativos.php" target="_blank">Membros Ativos</a></li>
        <li><a class="" href="relatorios/membros_inativos.php" target="_blank">Membros Inativos</a></li>
        <li><a class="" href="relatorios/membros_desfiliados.php" target="_blank">Membros Desfiliados</a></li>            
        <li><a class="" data-toggle="modal" data-target="#modalExemplo" target="_self">Livro Habitualidades</a></li>
        <li><a class="" href="relatorios/habitualidades_oito.php" target="_blank">Habitualidade Inferior</a></li>
        <li><a class="" href="relatorios/habitualidades_origem.php" target="_blank">Munições Origem</a></li>
        <li><a class="" href="relatorios/controle_municoes.php" target="_blank">Controle Munições</a></li>
        <li><a class="" href="relatorio_presenca.php">Frequência Biométrica</a></li>
    </ul>
</li>
			
			
			
			
<li class="sub-menu">
    <a href="javascript:;" class="">
        <i class="fa fa-print"></i>
        <span>Impress&otilde;es</span>
        <span class="menu-arrow arrow_carrot-right"></span>
    </a>
    <ul class="sub">
        <li><a class="" href="declaracoes_atiradores.php">Declara&ccedil;&otilde;es</a></li>
        <li><a class="" href="diversos_atiradores.php">Diversos</a></li>              
        <li><a class="" href="procuracoes_atiradores.php">Procura&ccedil;&otilde;es</a></li>
    </ul>
</li>


<li class="sub-menu">
    <a href="javascript:;" class="">
        <i class="fa fa-id-card-o" aria-hidden="true"></i>
        <span>CR, GT e CRAF</span>
        <span class="menu-arrow arrow_carrot-right"></span>
    </a>
    <ul class="sub">
        <!-- Adicione o sub-tópico "CRS" com sua opção "CRs novos" aqui -->
        <li class="sub-menu">
            <ul class="sub">
                <li><a class="" href="consultar_crs.php">Pesquisar CRs</a></li>
                <li><a class="" href="consultar_gts.php">Pesquisar GTs</a></li>
                <li><a class="" href="consultar_crafs.php">Pesquisar CRAFs</a></li>
                <!-- Adicione mais opções CRS aqui, se necessário -->
            </ul>
        </li>
        <!-- Fim do sub-tópico "GTs e CRAFs" -->
    </ul>
</li>

<li class="sub-menu">
    <a href="javascript:;" class="">
        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
        <span>Armamentos</span>
        <span class="menu-arrow arrow_carrot-right"></span>
    </a>
    <ul class="sub">
        <!-- Adicione o sub-tópico "CRS" com sua opção "CRs novos" aqui -->
        <li class="sub-menu">
            <ul class="sub">
                <li><a class="" href="painel_consultas.php">Consultar Armas</a></li>
                <li><a class="" href="cadastro_armas.php">Armas do Clube</a></li>
                <li><a class="" href="cadastro_calibres.php">Calibres usados</a></li>
                <li><a class="" href="cadastro_eventos.php">Evento habitualidade</a></li> 
                <li><a class="" href="cadastro_local.php">Local habitualidade</a></li>
                <!-- Adicione mais opções CRS aqui, se necessário -->
            </ul>
        </li>
        <!-- Fim do sub-tópico "CRS" -->
    </ul>
</li>


          
<li>
            <a class="" href="editortexto.php">
		   <i class="fa fa-file-text-o" aria-hidden="true"></i>
            <span>Editor de Texto</span>
            </a>
</li>          


            <li class="sub-menu">
            <a href="javascript:;" class="">
            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
            <span>Envios</span>
            <span class="menu-arrow arrow_carrot-right"></span>
            </a>
            <ul class="sub">
              <li><a class="" href="whatsappglobal.php">Whatsapp</a></li>
              <li><a class="" href="rel_whatsapp.php" target="_self">Status Whatsapp</a></li>  
            </ul>
            </li>
	

          <li>          
          

          	 <li>
            <a class="" href="configuracoes.php">
		   <i class="fa fa-cog" aria-hidden="true"></i>
            <span>Configurações</span>
            </a>
          </li>
          
          
          
            <li class="sub-menu">
            <a href="javascript:;" class="">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            <span>Outros</span>
            <span class="menu-arrow arrow_carrot-right"></span>
            </a>
            <ul class="sub">
              <li><a class="" href="backup.php" target="_self">Backup</a></li>  
              <li><a class="" href="logs.php" target="_self">Logs do Sistema</a></li>  
            </ul>
            </li>



          <li>		  
          </li>
        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>