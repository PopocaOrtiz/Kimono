<?php

class Kimono
{
    protected $apiId;
    protected $apiKey;
    protected $kimonoUrl = "https://www.kimonolabs.com/api/";
    protected $lastResult;

    public function __construct($apiId,$apiKey){
        $this->apiKey = $apiKey;
        $this->apiId  = $apiId;
    }

    /**
     * Genera la url de la api de kimono con el apiId y el apiKey
     * @return string url de la api
     */
    private function getUrl(){
        return  sprintf(
                "https://www.kimonolabs.com/api/%s?apikey=%s",
                $this->apiId,
                $this->apiKey
        );
    }

    /**
     * Genera una url para acceder a la api usando los parametros enviados
     * Se usa el indice para seleccionar el parametro
     *     [2=>valor]   =  &kimpath2=valor
     *     [id=>valor   =  &id=valor
     *     
     * @param  array $params Parametros para acceder a la api
     * @return string        url
     */
    private function getParamsUrl($params=array()){
    
        $url = $this->getUrl();
        
        foreach ($params as $key => $value) {
            if(is_numeric($key))
                $url .= '&kimpath'.$key."=".$value;
            else
                $url .= '&'.$key."=".$value;
        }
    
        return $url;
    }

    /**
     * Hace un llamado a la api usando los parametros enviados
     * 
     * @param  array $params Parametros
     * @return array         Resultadp
     */
    public function get($params = array()) {
    
        $url      = $this->getParamsUrl($params);
        $response = @file_get_contents($url);

        $this->lastResult = $response;

        if ($response === false) {
            throw new \Exception('No se pudo hacer la llamada a la api');
        }

        $data = json_decode($response, true);

        return $data['lastrunstatus'] == 'success' 
            ? $data['results'] 
            : array('error' => 'no data');
    }

    public function getLastResult(){
        return $this->lastResult;
    }
}
