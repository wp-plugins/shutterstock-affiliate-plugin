<?php

class Shutterstock_AP_API_Exception extends Exception
{
}

class Shutterstock_AP_API
{
  const url = 'http://api.shutterstock.com';
  const user_agent = 'Shutterstock Affiliate Plugin for Wordpress';

  private $username;
  private $key;   

  public function __construct($username, $key, $cache_time = 86400)
  {
    $this->username = $username;
    $this->key = $key;
    $this->cache_time = $cache_time;
  }
  
  public function get($method, $params, $cache = true)
  {
    $data = false;
    $url = self::url.'/'.$method.'.json?'.build_query($params);
    $hash = md5($url);
        
    if ($cache && $this->cache_time)
      $data = get_transient($hash);
    
    if ($data === false)
    {
      $data = json_decode($this->getData($url), true);
      
      if ($cache && $this->cache_time)
        set_transient($hash, $data, $this->cache_time);
    }
        
    return $data;
  }
    
  protected function getData($url)
  {
    $ch = curl_init();
    curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $this->username.':'.$this->key,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_USERAGENT => self::user_agent
              ));
    $data = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if ($info['http_code'] != 200)
      throw new Shutterstock_AP_API_Exception('Cannot get valid result from server, code: '.$info['http_code']);
        
    return $data;
  }
}