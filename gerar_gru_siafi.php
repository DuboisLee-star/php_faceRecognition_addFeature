<?php
    header('Content-Type: text/plain; charset="ISO-8859-1"');    
        
	if(($_POST['cpf_usuario']!='') and ($_POST['nome_usuario']!='') and ($_POST['referencia']!='') and ($_POST['competencia']!='') and ($_POST['valor_principal']!='')){
            
            //troca o espaco por + para colocar na url
            $nome = utf8_decode(str_replace(" ", "+", $_POST['nome_usuario']));

            $competencia = utf8_decode($_POST['competencia']);

            header("Content-type:application/pdf");
            header("Content-Disposition:inline");
                        
            // Get cURL resource
            try{
                    $curl = curl_init();
                    // Set some options - we are passing in a useragent too here
                   curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                   curl_setopt_array($curl, array(
                   CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'http://consulta.tesouro.fazenda.gov.br/gru_novosite/gerarPDF.asp',
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/65.0.3325.181 Chrome/65.0.3325.181 Safari/537.36',
                        CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'),
                        CURLOPT_REFERER => 'http://consulta.tesouro.fazenda.gov.br/gru_novosite/gru_simples.asp',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => 'codigo_favorecido=167086&gestao=00001&codigo_correlacao=2185&nome_favorecido=FUNDO+DO+EXERCITO&codigo_recolhimento=11300-0&nome_recolhimento=&referencia='.$_POST['referencia'].'&competencia='.$competencia.'&vencimento='.$_POST['vencimento'].'&cnpj_cpf='.$_POST['cpf_usuario'].'&nome_contribuinte='.$nome.'&valorPrincipal='.$_POST['valor_principal'].'&descontos=&deducoes=&multa=&juros=&acrescimos=&valorTotal='.$_POST['valor_principal'].'&boleto=1&impressao=SA&pagamento=1&campo=NRCR&ind=0'
                    ));
                    // Send the request & save response to $resp
                    $resp = curl_exec($curl);
                    // Close request to clear up some resources
                    curl_close($curl);
                    // var_dump($resp);
                    echo $resp;
		} 
            catch (Exception $e) {
                echo $e->getMessage();
            }	
	}
        else{
            echo '<script>alert("Não foi possível gerar a GRU.")</script>';
        }

?>
