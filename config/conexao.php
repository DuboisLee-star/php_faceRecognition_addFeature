<?php

/*
 * Constantes de parâmetros para configuração da conexão
 */
define('SGBD', 'mysql');
define('HOST', 'localhost');
define('DBNAME', 'wwhost_hostmarq');
define('CHARSET', 'utf8');
define('USER', 'wwhost_hostmarq');
define('PASSWORD', '?gOP?PHH}AwHH{{{P??OT0gG');
define('SERVER', 'linux');

class conexao {
    
    /*
     * Atributo estático de conexão
     */
    private static $pdo;

    /*
     * Escondendo o construtor da classe
     */
    private function __construct() {
        //
    }

    /*
     * Método privado para verificar se a extensão PDO do banco de dados escolhido
     * está habilitada
     */
    private static function verificaExtensao() {

        switch(SGBD):
            case 'mysql':
                $extensao = 'pdo_mysql';
                break;
            case 'mssql':{
                if(SERVER == 'linux'):
                    $extensao = 'pdo_dblib';
                else:
                    $extensao = 'pdo_sqlsrv';
                endif;
                break;
            }
            case 'postgre':
                $extensao = 'pdo_pgsql';
                break;
        endswitch;

        if(!extension_loaded($extensao)):
            echo "<h1>Extensão {$extensao} não habilitada!</h1>";
            exit();
        endif;
    }

    /*
     * Método estático para retornar uma conexão válida
     * Verifica se já existe uma instância da conexão, caso não, configura uma nova conexão
     */
    public static function getInstance() {

        self::verificaExtensao();

        if (!isset(self::$pdo)) {
            try {
                $opcoes = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
                switch (SGBD) :
                    case 'mysql':
                        self::$pdo = new \PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        break;
                    case 'mssql':{
                        if(SERVER == 'linux'):
                            self::$pdo = new \PDO("dblib:host=" . HOST . "; database=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        else:
                            self::$pdo = new \PDO("sqlsrv:server=" . HOST . "; database=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        endif;
                        break;
                    }
                    case 'postgre':
                        self::$pdo = new \PDO("pgsql:host=" . HOST . "; dbname=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        break;
                endswitch;
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                print "Erro: " . $e->getMessage();
            }
        }
        return self::$pdo;
    }

    public static function isConectado(){
        
        if(self::$pdo):
            echo "conectado conexao.php";
            return true;
        else:
            return false;
        endif;
    }

}



// verifica se esta logado como usuarios e se esta usando o mesmo id
// if(!isset($_SESSION)) session_start();
// if(isset($_SESSION['is_user']) && !isset($_SESSION['uname'])){
	// $user_logado_id = (isset($_GET['id'])) ? $_GET['id'] : '';
	// if($user_logado_id == '' || $user_logado_id != $_SESSION['user_id']){
		// header('location: /membros/index.php');
		// exit();
	// }
// }
$membro = isset($atirador) ? false : true;
// verifica se esta logado como usuarios e se esta usando o mesmo id
// if(!isset($_SESSION)) session_start();
// if(isset($_SESSION['is_user']) && !isset($_SESSION['uname'])){
//     if($membro){
//         $user_logado_id = (isset($_GET['id'])) ? $_GET['id'] : '';

//         if($user_logado_id == '' || $user_logado_id != $_SESSION['user_id']){
//             header('location: /index.php?ok');
//             exit();
//         }
// 	}
// }

function geraMatricula(){
    #ini_set('display_errors', true);
    #error_reporting(-1);
    $conexao = conexao::getInstance();
	$sql = "SELECT MAX(matricula) + 1 matricula FROM tab_membros";
	$stm = $conexao->prepare($sql);
	$stm->execute();
	$cliente = $stm->fetch(PDO::FETCH_OBJ);
	$matricula = isset($cliente->matricula) ? $cliente->matricula : '1';
	return str_pad($matricula, 4 , '0' , STR_PAD_LEFT);
}