<?php
/*
 * create by @Tiago Moselli
 * version 1.0.0
 */
include($_SERVER['DOCUMENT_ROOT'].'/autentique/src/autoloader.php');
require_once($_SERVER['DOCUMENT_ROOT']."/config/conexao.php");

use sysborg\autentiquev2\createDoc;
use sysborg\autentiquev2\autentique;
use sysborg\autentiquev2\signDoc;
use sysborg\autentiquev2\rescueDoc;

class AutentiqueH {

    private $token;
    private $email;
    private $debug;
    public  $tipo_documento;
    public  $posicao_assinatura;
    private $documento;
    private $pdf = null;

    //Dados da assinatura do membro
    private $email_membro;
    private $token_membro;
    private $assinado;
    private $naoAssinar;

    public function __construct(){

        $this->token                        = "";
        $this->email                        = "";
        $this->debug                        = false;
        $this->tipo_documento               = '';
        $this->documento                    = '';
        $this->posicao_assinatura           = array(35, 63, 1);
        $this->posicao_assinatura_membro    = array(5, 63, 1);
        $this->email_membro                 = '';
        $this->token_membro                 = '';
        $this->assinado                     = false;
        
    }

    public function pega_dados_do_assinante(){

        $conexao = conexao::getInstance();
        $sql = "
            SELECT
                a.*
            FROM
                tab_autentique a,
                info_clube b
            WHERE
                a.id = b.id_autentique
        ";
        $stm = $conexao->prepare($sql);
        $stm->execute();
		$assinante = $stm->fetch(PDO::FETCH_OBJ);

        if(isset($assinante->token)){

            $this->token    = trim($assinante->token);
            $this->email    = trim($assinante->email);

        }else{
            exit('Dados do assinante não configurado.');
        }


    }

    public function pega_dados_do_membro($matricula){
        
            $conexao = conexao::getInstance();
            $sql = "
                SELECT
                    a.nome, a.cpf, b.*
                FROM
                    tab_membros a
                LEFT JOIN
                    tab_autentique_membros b ON a.id = b.membro_id
                WHERE
                    a.matricula = :matricula
            ";
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':matricula', $matricula);
            $stm->execute();
            $assinante = $stm->fetch(PDO::FETCH_OBJ);

        if(isset($assinante->token) && $assinante->status_pgto == 'Pago'){

            $this->token_membro    = trim($assinante->token);
            $this->email_membro    = trim($assinante->email);
            $this->assinado        = true;

        }
    }

    public function criar_documento($documento_base64, $membro_matricula, $exibirAssinaturaClube = true, $posicao_assinatura_membro = array(5, 63), $posicao_assinatura = array(35, 63)){
        if($exibirAssinaturaClube){
            $this->pega_dados_do_assinante();
        }
        $this->pega_dados_do_membro($membro_matricula);
        
        if(!$exibirAssinaturaClube && !$this->assinado){
           $this->naoAssinar = true;
           return;
        }
        
        // criar o arquivo temporario
        $diretorio_tmp          = __DIR__.'/tmp/';
        $nome_do_arquivo_tmp    = md5(date('Y-m-d H:i:s').microtime(true).rand(0,9999)).'.pdf';

        file_put_contents($diretorio_tmp.$nome_do_arquivo_tmp, base64_decode($documento_base64));

        $this->documento = $diretorio_tmp.$nome_do_arquivo_tmp;

        $this->envia_documento($diretorio_tmp.$nome_do_arquivo_tmp, $exibirAssinaturaClube, $posicao_assinatura_membro, $posicao_assinatura);

    }

    public function envia_documento($documento, $exibirAssinaturaClube, $posicao_assinatura_membro, $posicao_assinatura){

        // verifica se o documento existe
        if(file_exists($documento)){
    
            $l = new createDoc();
            $l->name = 'Documento '.$this->tipo_documento;
            $l->file = $documento;
            
            if($exibirAssinaturaClube){
                // Adiciona a primeira assinatura
                // $posicao_assinatura = $this->posicao_assinatura;
                $signer1 = $l->addSigners($this->email);
                $signer1->addPositions($posicao_assinatura[0], $posicao_assinatura[1], $this->posicao_assinatura, 'SIGNATURE');
        
                // Envia o documento com a primeira assinatura
                $t = new autentique($l);
                $t->token=$this->token;
                $t->debug=$this->debug;
                $responseDoc = json_decode($t->transmit(), true);
            }
    
            // Adiciona a segunda assinatura
            if($this->assinado){
                // $posicao_assinatura = $this->posicao_assinatura;
                $signer2 = $l->addSigners($this->email_membro);
                $signer2->addPositions($posicao_assinatura_membro[0], $posicao_assinatura_membro[1], $this->posicao_assinatura, 'SIGNATURE');
        
                // Envia o documento com a segunda assinatura
                $t = new autentique($l);
                $t->token=$this->token_membro;
                $t->debug=$this->debug;
                $responseDoc = json_decode($t->transmit(), true);
            }
    
            if(isset($responseDoc['data']['createDocument']['id'])){

                // remove o documento temporario
                unlink($this->documento);
            
                if($exibirAssinaturaClube){
                    // Assina o documento com a primeira assinatura
                    $this->assina_documento($responseDoc['data']['createDocument']['id'], $this->token);
                }
            
                // Assina o documento com a segunda assinatura
                if($this->assinado){
                    $this->assina_documento($responseDoc['data']['createDocument']['id'], $this->token_membro);
                }
            }else{
                // remove o documento temporario
                unlink($this->documento);
                exit('Falha ao criar documento na Autentique.');
            }
    
        }else{
            exit('Documento não existe.');
        }
    }

    public function assina_documento($id_documento, $token){

        // assina o documento
        $l = new signDoc();
        $l->document_id = $id_documento;
    
        $t = new autentique($l);
        $t->token=$token;
        $t->debug=$this->debug;
        $responseAss = json_decode($t->transmit(), true);
    
        // verifica se assinou
        if(isset($responseAss['data']['signDocument'])){
            if($responseAss['data']['signDocument'] == 1){
    
                // recupera o documento
                $l = new rescueDoc();
                $l->document_id = $id_documento;
    
                $t = new autentique($l);
                $t->token=$token;
                $response = json_decode($t->transmit(), true);
    
                if(isset($response['data']['document']['files']['signed'])){
                    header("Content-type:application/pdf");
                    header("Content-Disposition:inline;filename=Documento Assinado.pdf");
                    $this->pdf = file_get_contents($response['data']['document']['files']['signed']);
                }
    
            }
        }else{
            exit('Falha ao assinar documento');
        }
    
    }
    
    /*public function assina_documento($id_documento){

        // assina o documento
        $l = new signDoc();
        $l->document_id = $id_documento;

        $t = new autentique($l);
        $t->token=$this->token;
        $t->debug=$this->debug;
        $responseAss = json_decode($t->transmit(), true);

        // verifica se assinou
        if(isset($responseAss['data']['signDocument'])){
            if($responseAss['data']['signDocument'] == 1){

                // recupera o documento
                $l = new rescueDoc();
                $l->document_id = $id_documento;

                $t = new autentique($l);
                $t->token=$this->token;
                $response = json_decode($t->transmit(), true);

                if(isset($response['data']['document']['files']['signed'])){
                    header("Content-type:application/pdf");
                    header("Content-Disposition:inline;filename=Documento Assinado.pdf");
                    $this->pdf = file_get_contents($response['data']['document']['files']['signed']);
                }

            }
        }else{
            exit('Falha ao assinar documento');
        }

    }*/

    public function output(){
        if($this->naoAssinar){
            return;
        }
        header("Content-type:application/pdf");
        header("Content-Disposition:inline;filename=Documento Assinado.pdf");
        echo $this->pdf;
        exit();
    }

}