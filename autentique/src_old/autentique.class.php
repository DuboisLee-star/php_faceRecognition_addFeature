<?php
    namespace sysborg\autentiquev2;

    class autentique{
        use utils;

        /**
         * @description-en-US:       Stores API's informations
         * @description-pt-BR:       Armazena as informações da API
         * @var                      array
         */
        private $apiInfo = [
            'url'       => 'https://api.autentique.com.br/v2/graphql',
            'token'     => NULL,
            'devMode'   => false,
            'debug'     => false
        ];

        /**
         * @description-en-US:       Stores CURL_INFO
         * @description-pt-BR:       Armazena CURL_INFO
         * @var                      string
         */
        private $curlError;

        /**
         * @description-en-US:       Stores CURL_ERROR
         * @description-pt-BR:       Armazena CURL_ERROR
         * @var                      array
         */
        private $curlInfo;

        /**
         * @description-en-US       Construct the class with the desired layout
         * @description-pt-BR       Constrói a classe com o layout desejado
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   private \sysborg\autentiquev2\layouts $layout
         * @return                  string
         */
        //public function __construct(private \sysborg\autentiquev2\layouts $layout){}
        private $layout;
        public function __construct(\sysborg\autentiquev2\layouts $layout){
            $this->layout = $layout;
          }

        /**
         * @description-en-US       Set values to API's informations
         * @description-pt-BR       Seta valores para as informações da API
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   string $apiInfoName
         * @param                   mixed  $val
         * @return                  void
         */
        public function __set(string $apiInfoName, $val)
        {
            $this->verifyColumn($this->apiInfo, $apiInfoName);

            if($apiInfoName==='devMode' && !is_bool($val))
                throw new \Exception('en-US: devMode expects boolean and '. gettype($val). ' are given! | pt-BR: devMode espera boolean e '. gettype($val). ' foi passado!');

            $this->apiInfo[$apiInfoName] = $val;
        }

        /**
         * @description-en-US       Get values to API's informations
         * @description-pt-BR       Pega valores para as informações da API
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   string $apiInfoName
         * @return                  mixed
         */
        public function __get(string $apiInfoName)
        {
            $this->verifyColumn($this->apiInfo, $apiInfoName);
            return $this->apiInfo[$apiInfoName];
        }

        /**
         * @description-en-US       CURL's debug
         * @description-pt-BR       Debug do CURL
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   
         * @return                  void
         */
        public function curlDebug() : void
        {
            echo '<pre>';
            var_dump($this->curlInfo, $this->curlError);
            echo '</pre>';
        }

        /**
         * @description-en-US       Debug informations autentique class and CURL
         * @descritpion-pt-BR       Informações de debug para classe autentique e CURL
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   
         * @return                  array
         */
        public function __debugInfo() : array
        {
            return [
                'API_SETTINGS' => $this->apiInfo,
                'LAYOUT'       => $this->layout->__debugInfo(),
                'CURL_ERROR'   => $this->curlError ?? '',
                'CURL_INFO'    => $this->curlInfo ?? []
            ];
        }

        /**
         * @description-en-US       Transmit requisition of Autentique API
         * @description-pt-BR       Transmite a requisção da API do Autentique
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   
         * @return                  mixed
         */
        public function transmit()
        {
            $pfields = $this->layout->parse();
            $c=curl_init();
            curl_setopt_array($c, [
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $pfields,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer '. $this->token,
                    (is_array($pfields) ? '' : 'Content-Type: application/json')
                ]
            ]);
            $r = curl_exec($c);
            if($this->debug){
                $this->curlError = curl_error($c);
                $this->curlInfo = curl_getinfo($c);
            }
                
            curl_close($c);
            return $r;
        }
    }
?>
