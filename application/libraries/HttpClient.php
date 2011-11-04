<?php


class My_HttpClient
{


    public function setUri($uri,$key)
    {
        $this->requestList[$key]['uri'] = $uri;
    }

    public function execute()
    {
        
        $curl = curl_multi_init();

        foreach ($this->requestList as $key => $value) {

            $ch[$key] = curl_init();
            curl_setopt($ch[$key],CURLOPT_URL,$value['uri']);
            curl_setopt($ch[$key],CURLOPT_RETURNTRANSFER,1);

            curl_multi_add_handle($curl,$ch[$key]);
        }

        $active = null;

        do {
            $mrc = curl_multi_exec($curl, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($curl) != -1) {
                do {
                    $mrc = curl_multi_exec($curl, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach ($this->requestList as $key => $uri) {

            $this->responseList[$key] = curl_multi_getcontent($ch[$key]);
        }
    
    }

    public function get($key)
    {
        return $this->responseList[$key];
    }
    
            
        
        


}

    
