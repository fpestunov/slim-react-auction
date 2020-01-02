<?php

namespace Framework\Http;

class Request
{
  
  public function getQueryParams(): array
  {
    return $_GET;
  }

  // здесь все запросы и POST, PUT, DELETE
  public function getParsedBody()
  {
    // если никаких данных не прилитело, то вернем null
    return $_POST ?: null;
  }

  // возвращает сырые данные в виде строк (JSON, XML)  
  public function getBody()
  {
    return file_get_contents('php://input');
  }
  
}