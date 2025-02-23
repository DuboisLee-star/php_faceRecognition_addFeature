<?php

include "config/config.php";

// Desativar o cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$msg_erro = false;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	if(strlen(trim($_POST['txt_uname'])) > 0 && strlen(trim($_POST['txt_pwd'])) > 0){
		
		// check user basic login
		if(isset($usuarios[trim($_POST['txt_uname'])])){
			
			// valida senha
			if($usuarios[trim($_POST['txt_uname'])]['pass'] == trim($_POST['txt_pwd'])){
				
				$_SESSION['is_user'] = 1;
				$_SESSION['user_id'] = $usuarios[trim($_POST['txt_uname'])]['id'];
				$_SESSION['user_matricula'] = trim($_POST['txt_uname']);
				
				header('Location: ../atirador/index.php?id='.$usuarios[trim($_POST['txt_uname'])]['id']);
				exit();
			}else{
				$msg_erro = '<div class="msg_errologin">Usuário ou senha inválido.</div>';
			}
			
		}else{
	
			$uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
			$password = mysqli_real_escape_string($con,$_POST['txt_pwd']);


			if ($uname != "" && $password != ""){

				$sql_query = "select count(*) as cntUser from users where username='".$uname."' and password='".$password."'";
				$result = mysqli_query($con,$sql_query);
				$row = mysqli_fetch_array($result);

				$count = $row['cntUser'];

				if($count > 0){
					$_SESSION['uname'] = $uname;
					header('Location: painel.php');
					exit();
				}else{
					$msg_erro = '<div class="msg_errologin">Usuário ou senha inválido.</div>';
				}

			}
		}
    }

}
?>
<!DOCTYPE html>
<html lang="zxx">
<!-- Head -->

<head>
    <title>HOSTMARQ</title>
    <!-- Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta name="keywords" content="Prime login Form a Responsive Web Template, Bootstrap Web Templates, Flat Web Templates, Android Compatible Web Template, Smartphone Compatible Web Template, Free Webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design">
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta-Tags -->
    <!-- Index-Page-CSS -->
    <link rel="stylesheet" href="<?php echo $url_site; ?>atirador/css/style2.css" type="text/css" media="all">
    <!-- //Custom-Stylesheet-Links -->
    <!--fonts -->
    <link href="//fonts.googleapis.com/css?family=Mukta+Mahee:200,300,400,500,600,700,800" rel="stylesheet">
    <!-- //fonts -->
    <link rel="stylesheet" href="<?php echo $url_site; ?>atirador/css/font-awesome2.css" type="text/css" media="all">
    <!-- //Font-Awesome-File-Links -->
</head>
<!-- //Head -->
<!-- Body -->
<script src="<?php echo $url_site; ?>atirador/js/prefixfree.min.js"></script>
	<script>
	function checkLogin(){
		
		var txt_uname = document.getElementById("txt_uname").value;
		var txt_pwd = document.getElementById("txt_pwd").value;
		
		if(txt_uname == ""){alert("Nome não informado."); return false;}
		if(txt_pwd == ""){alert("Senha não informada."); return false;}
		
		document.getElementById("form_login").submit();
		
	}
	</script>
<body>
    <h1 class="title-agile text-center"></h1>
    <div class="content-w3ls">
        <div class="content-bottom">
            <form onsubmit="return checkLogin()" method="post" action="" name="form_login" id="form_login">
				<?php echo $msg_erro; ?>
				<div align=center><img src="<?php echo $url_site; ?>img/logo_site.png" height=110></div><br><div align=left>
			<font color="white"><b>ADMINISTRA&Ccedil;&Atilde;O</b></font><br><br>
                <div class="field-group">
                    <span class="fa fa-user" aria-hidden="true"></span>
                    <div class="wthree-field">
                        <input name="txt_uname" id="txt_uname" type="text" value="" placeholder="Usuario" required>
                    </div>
                </div>
                <div class="field-group">
                    <span class="fa fa-lock" aria-hidden="true"></span>
                    <div class="wthree-field">
                        <input name="txt_pwd" id="txt_pwd" type="password" placeholder="Senha">
                    </div>
                </div>
                <div class="field-group">
                </div><br>
                <div class="wthree-field">
                    <input id="saveForm" name="saveForm" type="submit" value="entrar" />
					</div><br>
                </ul>
            </form>
        </div>
    </div>
    <div class="copyright text-center">
        <p>© 2020 | Design by <a href="https://sistema.hostmarq.com.br">HOSTMARQ</a><br><a href="<?php echo $url_site; ?>politica_de_privacidade.php" target="_self">Política de Privacidade </a>
        </p>
    </div>
</body>
<!-- //Body -->
</html>