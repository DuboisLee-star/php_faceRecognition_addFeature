<?php
/*
 * create by @Tiago Rodrigues
 * version 1.0.0
 */
class IcpBrasil {
    private $pdf;

    public function __construct(){}

    public function assinar_documento($base64, $assinante = null){
        $api_url = "https://izpnf26vsl.execute-api.us-east-1.amazonaws.com/prod/sign/pdf";
        // $api_url = "https://7d82-2804-2d2c-efe5-4f00-00-1.ngrok-free.app/sign/pdf";
        $ch = curl_init($api_url);

        $payload = json_encode([ "pdf_base64" => $base64, "assinante" => $assinante ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $response_data = json_decode($response, true);
            if (!empty($response_data['pdf_assinado'])) {
                header("Content-Type: application/pdf");
                header("Content-Disposition: inline; filename=habitualidade_assinado.pdf");
                $this->$pdf = base64_decode($response_data['pdf_assinado']);
            } else {
                exit("Erro: Resposta da API não contém um PDF assinado.");
            }
        } else {
            exit("Erro ao assinar PDF. Código HTTP: $http_code");
        }
    }

    public function output(){
        header("Content-type:application/pdf");
        header("Content-Disposition:inline;filename=Documento Assinado.pdf");
        echo $this->$pdf;
        exit();
    }

}