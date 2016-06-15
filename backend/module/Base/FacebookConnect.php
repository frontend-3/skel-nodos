<?php

namespace Base;


class FacebookConnect
{
    private $client_id = NULL;
    private $client_secret = NULL;
    private $permissions = NULL;
    private $redirect_uri = NULL;

    private $expires = NULL;
    private $access_token = NULL;

    CONST url_graph = 'https://graph.facebook.com';
    CONST url_dialog = 'https://www.facebook.com/dialog/oauth';


    public function set_credentials($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    private function get_data_fb($url, $params){
        $url .= '?' . http_build_query($params);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return file_get_contents($url);
        }else{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $data;
        }
    }

    public function send_request($redirect_uri, $add_params=array())
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $redirect_uri,
        );

        if (count($add_params) > 0){
            array_merge($params, $add_params);
        }

        $url = sprintf(
            '%s?%s', self::url_dialog, http_build_query($params));
        return $url;
    }


    public function get_request($code, $redirect_uri)
    {
        $url = sprintf('%s/oauth/access_token', self::url_graph);
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $redirect_uri,
            'client_secret' => $this->client_secret,
            'code' => $code
        );

        $data = $this->get_data_fb($url, $params);
        if(strpos($data, 'error') !== false){
            return false;
        }

        $data = explode('&', $data);
        $access_token = str_replace('access_token=', '', $data[0]);
       
        $expires = null;
        if (isset($data[1])){
            $expires = str_replace('expires=', '', $data[1]);
        }

        $this->access_token = $access_token;
        $this->expires = $expires;
        return true;
    }

    public function get_user_access_token()
    {
        return $this->access_token;
    }

    public function get_user_expires()
    {
        return $this->expires;
    }

    public function get_user()
    {
        $url = sprintf('%s/me', self::url_graph);
        $params = array('access_token' => $this->access_token);
        $profile = json_decode($this->get_data_fb($url, $params));
        return $profile;
    }
}