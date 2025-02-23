<?php
/*
 * create by @Tiago Moselli
 * version 1.0.0
 */
include($_SERVER['DOCUMENT_ROOT'].'/autentique/src/autoloader.php');
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

    public function __construct(){

        $this->token                = "4699f49982de8fdfe9c40e56f0c8822a8a70040952a341a0271e184076be9759";
        $this->email                = "wpmarques@outlook.com";
        $this->debug                = false;
        $this->tipo_documento       = '';
        $this->documento            = '';
        $this->posicao_assinatura   = array(35, 63, 1);
        
    }

    public function criar_documento($documento_base64){
        
        // criar o arquivo temporario
        $diretorio_tmp          = __DIR__.'/tmp/';
        $nome_do_arquivo_tmp    = md5(date('Y-m-d H:i:s').microtime(true).rand(0,9999)).'.pdf';

        file_put_contents($diretorio_tmp.$nome_do_arquivo_tmp, base64_decode($documento_base64));

        $this->documento = $diretorio_tmp.$nome_do_arquivo_tmp;

        $this->envia_documento($diretorio_tmp.$nome_do_arquivo_tmp);

    }

    public function envia_documento($documento){

        // verifica se o documento existe
        if(file_exists($documento)){

            $l = new createDoc();
            $l->name = 'Documento '.$this->tipo_documento;
            $l->file = $documento;

            $signer = $l->addSigners($this->email);

            $posicao_assinatura = $this->posicao_assinatura;
            $signer->addPositions($posicao_assinatura[0], $posicao_assinatura[1], $posicao_assinatura[2], 'SIGNATURE');

            // envia o documento;
            $t = new autentique($l);
            $t->token=$this->token;
            $t->debug=$this->debug;
            $responseDoc = json_decode($t->transmit(), true);

            if(isset($responseDoc['data']['createDocument']['id'])){

                // remove o documento temporario
                unlink($this->documento);

                $this->assina_documento($responseDoc['data']['createDocument']['id']);
            }else{
                // remove o documento temporario
                unlink($this->documento);
                exit('Falha ao criar documento na Autentique.');
            }

        }else{
            exit('Documento não existe.');
        }

    }

    public function assina_documento($id_documento){

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

    }

    public function output(){
        header("Content-type:application/pdf");
        header("Content-Disposition:inline;filename=Documento Assinado.pdf");
        echo $this->pdf;
        exit();
    }

}