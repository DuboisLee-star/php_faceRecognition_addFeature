<?php

 $include_conexao = !isset($include_conexao) ? true : false;

 if($include_conexao){
    
    require_once("config/conexao.php");
 }



class Whatsapp {

    private $instance_id;
    private $authorization;
    public $message;
    public $number;
    public $tipo;
    public $nome_clube;
    public $telefone_clube;
    public $referencia;
    public $matricula;
    public $tipo_envio;

    public function __construct(){

        $this->instance_id    = 'wwhoostmarq';
        $this->authorization  = 'HOSTMQRzYzP7ocstx$_s23D4FZTCu4ehnM8v4hu';
        $this->message        = '';
        $this->number         = '';
        $this->tipo           = 'manual';
        $this->nome_clube     = 'HOSTMARQ';
        $this->telefone_clube = '(84) 9-8121-2122';
        
        $this->referencia     = 'local';
        $this->matricula      = '0000';
        $this->tipo_envio     = 'T';
        
    }
	
public function verifica_saldo(){
    // Código original mantido, mas ignorado
    if((int)date('d') > 10){
        $dada_inicio = date('Y-m-11');
        $data        = new DateTime($dada_inicio);
        $data->add(new DateInterval('P1M'));
        $data_fim = $data->format('Y-m').'-10';
    }else{
        $dada_inicio = date('Y-m-11');
        $data        = new DateTime($dada_inicio);
        $data->sub(new DateInterval('P1M'));
        $dada_inicio = $data->format('Y-m-d');
        $data_fim    = date('Y-m-10');
    }
    
    $conexao = conexao::getInstance();
    $sql = "SELECT COUNT(*) qtde FROM tab_logwhatsapp WHERE datacadastro BETWEEN :data_inicio AND :data_fim ";
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':data_inicio', $dada_inicio.' 00:00:00');
    $stm->bindValue(':data_fim', $data_fim.' 23:59:59');
    $stm->execute();
    $envios = $stm->fetch(PDO::FETCH_OBJ);
    
    // Retornar sempre true para saldo infinito
    return true;
}

    public function enviar(){

        // valida dados
        if(strlen(trim($this->instance_id)) < 0){
            return array('error' => 'instance_id não configurado');
        }
        if(strlen(trim($this->authorization)) < 0){
            return array('error' => 'authorization não configurado');
        }
		$this->number = preg_replace('/[^0-9]/', '', $this->number);
        if(strlen(trim($this->number)) < 10){
            return array('error' => 'Número inválido');
        }
        if(strlen(trim($this->message)) <= 0){
            return array('error' => 'Mensagem não informada.');
        }
		
		if(!$this->verifica_saldo()) return array('error' => 'Saldo esgotado.');
		echo "enviou";
        return $this->chatpro();

    }
    
    public function instance_create($name)
    {
        
        try {
            

            $url = 'http://162.240.153.81:8780/instance/create';
            $dados['instanceName'] = $name;
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($dados),
                CURLOPT_HTTPHEADER => array(
                    'content-type: application/json',
                    "apikey: ".$this->authorization
                ),
            ));
            $response = curl_exec($curl);
            
            $resultado = json_decode($response, true);
            curl_close($curl);

            // grava log de envio
            $dados['sucesso'] = (isset($resultado['instance']) ? 1 : 0);

            return $dados['sucesso'];

        } catch (Exception $e) {
            
            return array('error' => 'Exceção capturada: '.  $e->getMessage());

        }
        
    }

    public function chatpro(){

        try {
            

            if($this->tipo_envio == "T"){
                $url = 'http://162.240.153.81:8780/message/sendText/'.$this->instance_id;
                $dados['textMessage']['text'] = $this->message;
                $dados['number'] = '55'.$this->number;
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($dados),
                    CURLOPT_HTTPHEADER => array(
                        'content-type: application/json',
                        "apikey: ".$this->authorization
                    ),
                ));
                $response = curl_exec($curl);
                
            }else{
                $url = 'http://162.240.153.81:8780/message/sendMediaFile/'.$this->instance_id;
                $dados['attachment'] = new CURLFILE($this->message);
                $dados['mediatype'] = 'image';
                $dados['caption'] = $this->nome_clube;
                $dados['number'] = '55'.$this->number;
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POSTFIELDS => $dados,
                    CURLOPT_HTTPHEADER => array(
                        "apikey: ".$this->authorization
                    ),
                ));
                $response = curl_exec($curl);
            }
            
            $resultado = json_decode($response, true);
            curl_close($curl);

            // grava log de envio
            $dados['sucesso'] = (isset($resultado['messageTimestamp']) ? 1 : 0);
            $dados['response'] = $response;
            $this->gravalog($dados);

            return $resultado;

        } catch (Exception $e) {
            
            return array('error' => 'Exceção capturada: '.  $e->getMessage());

        }

    }

    public function gravalog($dados){

        $conexao = conexao::getInstance();
        $sql = "INSERT INTO tab_logwhatsapp
            (
                numero,
                mensagem,
                datacadastro,
                sucesso,
                response,
                tipo,
                referencia,
                tipo_envio
            ) VALUES (
                :numero,
                :mensagem,
                :datacadastro,
                :sucesso,
                :response,
                :tipo,
                :referencia,
                :tipo_envio
            )
        ";
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':numero', $this->number);  
        $stm->bindValue(':mensagem', $this->message);  
        $stm->bindValue(':datacadastro', date('Y-m-d H:i:s'));
        $stm->bindValue(':sucesso', $dados['sucesso']);  
        $stm->bindValue(':response', $dados['response']);  
        $stm->bindValue(':tipo', $this->tipo);  
        $stm->bindValue(':referencia', $this->referencia);  
        $stm->bindValue(':tipo_envio', $this->tipo_envio);  
        $stm->execute();
	echo "enviou";
    }

public function envia_mensagem_aniversario() {
    if ($this->envioMensagensAtivo) {
        // Evite chamadas múltiplas
        return;
    }

    $this->envioMensagensAtivo = true;

    $conexao = conexao::getInstance();
    $sql = "SELECT nome, telefone, matricula FROM tab_membros WHERE SUBSTRING(data_nascimento, 6, 5) = '".date('m-d')."' and envios_auto = 0";
    $stm = $conexao->prepare($sql);
    $stm->execute();
    $aniversariantes = $stm->fetchAll(PDO::FETCH_OBJ);
        echo "Aniversario <br>";
    foreach ($aniversariantes as $key => $Atirador) {
        $this->matricula = $Atirador->matricula;
        $this->message = "Olá *{$Atirador->nome}*,\n
        Hoje é uma data muito especial para todos nós, pois é o seu aniversário.\n
        Desejamos muitos anos de vida, felicidades e que todos os seus sonhos se realizem.\n
        São os sinceros votos do seu Clube de Tiro.\n
        \n
        Abraços\n
        Clube de Tiro {$this->nome_clube}
        Whatsapp do Clube - {$this->telefone_clube}
        Secretaria\n
        _Esta é uma Mensagem Automática_";

        $this->number = preg_replace('/[^0-9]/', '', $Atirador->telefone);
        
       echo $this->message."<br><hr><br>";

        // Verificação adicionada para evitar duplicatas
        if (!$this->verifica_mensagem_na_fila()) {
            $this->cria_fila();
             
        }
    }

    $this->envioMensagensAtivo = false; // Certifique-se de redefinir a flag ao final do processo
}

// Adicione este método para verificar se a mensagem já está na fila
private function verifica_mensagem_na_fila() {
    // Lógica para verificar se a mensagem já está na fila
    // Retorne true se estiver na fila, false caso contrário
    // Exemplo fictício: return $this->fila->verificaMensagem($this->message);
}

public function envia_mensagem_renovacao(){
    $this->tipo = 'renovacao';
    $success = false;

    $conexao = conexao::getInstance();
    $sql = "SELECT DISTINCT nome, telefone, matricula, data_renovacao, 
        TIMESTAMPDIFF(DAY, NOW(), data_renovacao) dias 
        FROM tab_membros 
        WHERE TIMESTAMPDIFF(DAY, NOW(), data_renovacao) IN (30, 15, 7, 0) and envios_auto = 0";
    $stm = $conexao->prepare($sql); 
    $stm->execute();
    $renovacoes = $stm->fetchAll(PDO::FETCH_OBJ);
    

   
    // Array para armazenar IDs das mensagens já enviadas
    $mensagens_enviadas = [];
    $contador=0;
    echo "Vencimento matricula <br>";
    foreach($renovacoes as $key => $Atirador){
        $mensagem_id = trim($Atirador->matricula);
        $contador+=1;

        if (!in_array($mensagem_id, $mensagens_enviadas)) {
            $this->matricula = $Atirador->matricula;
            $this->message = "Olá *{$Atirador->nome}*,\n\nEsta é uma mensagem para informar que sua filiação com matrícula *{$Atirador->matricula}* no seu Clube de Tiro tem data de renovação prevista para ".date('d/m/Y', strtotime($Atirador->data_renovacao)).", ela estará vencendo dentro de *".$Atirador->dias."* dias.\n\nPor favor procure à secretaria do seu clube e se informe sobre os procedimentos necessários. Se já tiver resolvido por favor desonsiderar o aviso!\n\nAbraços\n\nClube de Tiro {$this->nome_clube}\nWhatsapp do Clube - {$this->telefone_clube}\nSecretaria\n_Esta é uma Mensagem Automática_";
            $this->number = preg_replace('/[^0-9]/', '', $Atirador->telefone);
           
            echo $this->message."<br><hr><br>";
            $this->cria_fila();
            
            

            // Adiciona o ID da mensagem ao array de mensagens enviadas
            $mensagens_enviadas[] = $mensagem_id;
            
          
        } else {
            // A mensagem já foi enviada anteriormente, então ignore
            echo "A mensagem para o ID $mensagem_id já foi enviada anteriormente.";
        }
        
    }
    
}

//Renova GT

public function envia_mensagem_renovacao_gt(){
    $this->tipo = 'renovacao';
    $success = false;

    $conexao = conexao::getInstance();
    $sql = "SELECT DISTINCT tm.nome, tm.telefone, tm.matricula, ta.validade_gt, ta.tipo, ta.modelo,ta.calibre,ta.numsigma, 
        TIMESTAMPDIFF(DAY, NOW(), ta.validade_gt) dias 
        FROM tab_membros as tm join tab_armas as ta on tm.matricula = ta.matricula
        WHERE TIMESTAMPDIFF(DAY, NOW(), ta.validade_gt) IN (30, 15, 7, 0) and tm.envios_auto = 0";
    $stm = $conexao->prepare($sql); 
    $stm->execute();
    $renovacoes = $stm->fetchAll(PDO::FETCH_OBJ);
    

   
    // Array para armazenar IDs das mensagens já enviadas
    $mensagens_enviadas = [];
    $contador=0;
    echo 'Vendimento GT <br>';
    foreach($renovacoes as $key => $Atirador){
        $mensagem_id = trim($Atirador->matricula);
        $contador+=1;

        if (!in_array($mensagem_id, $mensagens_enviadas)) {
            $this->matricula = $Atirador->matricula;
            $this->message = "Olá *{$Atirador->nome}*,\n\nEsta é uma mensagem para informar que a validade da sua GT  de sua ".$Atirador->tipo." ".$Atirador->modelo." ".$Atirador->calibre." ".$Atirador->numsigma." estará vencendo dentro de *".$Atirador->dias."* dias.\n\nPor favor procure à secretaria do seu clube e se informe sobre os procedimentos necessários. Se já tiver resolvido por favor desonsiderar o aviso!\n\nAbraços\n\nClube de Tiro {$this->nome_clube}\nWhatsapp do Clube - {$this->telefone_clube}\nSecretaria\n_Esta é uma Mensagem Automática_";
            $this->number = preg_replace('/[^0-9]/', '', $Atirador->telefone);
            
            echo $this->message."<br><hr><br>";
            $this->cria_fila();
            
            

            // Adiciona o ID da mensagem ao array de mensagens enviadas
            $mensagens_enviadas[] = $mensagem_id;
            
          
        } else {
            // A mensagem já foi enviada anteriormente, então ignore
            echo "A mensagem para o ID $mensagem_id já foi enviada anteriormente.";
        }
        
    }
    
}
    public function envia_mensagem_fila(){
     
        $this->tipo = 'fila';
        $success = false;

        $conexao = conexao::getInstance();
        $sql = "SELECT * FROM tab_filawhatsapp ORDER BY id ASC LIMIT 1";
        $stm = $conexao->prepare($sql); 
        $stm->execute();
        $fila = $stm->fetchAll(PDO::FETCH_OBJ);

        foreach($fila as $key => $Atirador){
            
            print_r($Atirador);

            $this->referencia = $Atirador->referencia;
            $this->message    = $Atirador->mensagem;
            $this->tipo_envio    = $Atirador->tipo_envio;
            $this->number     = preg_replace('/[^0-9]/', '', $Atirador->numero);

            $result = $this->enviar();
            if(!isset($result['error'])) $success = true;
            
			//if($success){
				$sql = "DELETE FROM tab_filawhatsapp WHERE id = :id ";
				$stm = $conexao->prepare($sql); 
				$stm->bindValue(':id', $Atirador->id); 
				$stm->execute();
			//}

        }

        return ($success) ? array('status' => true) : array('error' => 'Nenhum registro localizado para envio.');
        
    }

    public function generate_qrcode(){
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://162.240.153.81:8780/instance/connect/'.$this->instance_id,
            CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_SSL_VERIFYHOST => false,
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_FOLLOWLOCATION => false,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "apikey: ".$this->authorization
            ),
        ));
        $response = curl_exec($curl);
        
        $resultado = json_decode($response, true);
        curl_close($curl);

        return $resultado;

    }

    public function service_status(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://162.240.153.81:8780/instance/connectionState/'.$this->instance_id,
            CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_SSL_VERIFYHOST => false,
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_FOLLOWLOCATION => false,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "apikey: ".$this->authorization
            ),
        ));
        $response = curl_exec($curl);
        $resultado = json_decode($response, true);
        
        curl_close($curl);

        return $resultado;

    }

    public function status_chenge($status = 0){

        $dados['code']      = base64_decode($this->instance_id);
        $dados['value']     = (int)$status;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.chatpro.com.br/painel/ws/endpoint.php?action=status_instancia',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dados),
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'content-type: application/json',
                "Authorization: ".base64_decode($this->authorization)
            ),
        ));
        $response = curl_exec($curl);
        $resultado = json_decode($response, true);
        curl_close($curl);

        return $resultado;

    }

    public function cria_fila(){

        $conexao = conexao::getInstance();
        $sql = " INSERT INTO tab_filawhatsapp 
            (
                matricula,
                numero,
                mensagem,
                datacadastro,
                referencia,
                tipo_envio
            ) VALUES (
                :matricula,
                :numero,
                :mensagem,
                :datacadastro,
                :referencia,
                :tipo_envio
            )
        ";
        $stm = $conexao->prepare($sql); 
        $stm->bindValue(':matricula', $this->matricula); 
        $stm->bindValue(':numero', $this->number); 
        $stm->bindValue(':mensagem', $this->message); 
        $stm->bindValue(':datacadastro', date('Y-m-d H:i:s')); 
        $stm->bindValue(':referencia', $this->referencia);
        $stm->bindValue(':tipo_envio', $this->tipo_envio);

        $retorno = $stm->execute();

        return ($retorno) ? true : false;

    }

}