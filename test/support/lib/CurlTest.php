<?php

class CurlTest {
  public $path;
  public $headers;
  public $method;
  public $params = array();
  public $files = array();

  private $url ;

  private $response_header ;
  private $response_body ;
  private $response_code ;

  private $result ;

  function __construct( $options=array() ) {
    $this->path    = @$options['path'];
    $this->headers = @$options['headers'];
    $this->params  = @$options['params'];
    $this->files   = @$options['files'];
    $this->method  = @$options['method'];

  }

  function run() {
    if ( $this->result !== null ) return $this->result ;

    $ch = curl_init();

    $this->url = "http://" . $GLOBALS['TEST_URL_BASE'] . $this->path;

    if ( count($this->files) > 0 ) {
      $this->method = 'POST' ;

      foreach($this->files as $key => $file) {
        $this->params["upload_$key"] = curl_file_create($file);
      }
    }

    if ($this->method == 'POST') {
      curl_setopt($ch, CURLOPT_POST, 1);
    }

    if ($this->method == 'POST' && empty($this->files)) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
    } else if (! empty($this->files)) {
      // If files are present pass an array in order to have cURL
      // use a multipart form-data.
      if ( $this->params ) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
      }
    }

    if ($this->method == 'GET' and $this->params) {
      $this->url .= "?" . http_build_query( $this->params );
    }

    $this->setHeaders($ch) ;

    curl_setopt($ch, CURLOPT_URL, $this->url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $response = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $this->response_header = substr($response, 0, $header_size);
    $this->response_body   = substr($response, $header_size);
    $this->response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
      $this->result = false;

      echo "URL $this->url \n" ;
      echo "METHOD $this->method \n" ;
      echo "CURL_ERROR " . curl_error($ch) . "\n";

    } else {
      $this->result = true;
    }

    curl_close($ch);
    return $this->result ;
  }

  public function getResponse() {
    if ( $this->run() ) {
      return array(
        'header' => $this->response_header,
        'body' => $this->response_body,
        'code' => $this->response_code
      );
    }
  }

  private function setHeaders($ch) {
    if (! empty($this->headers) ) {
      curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->headers );
    }
  }
}
