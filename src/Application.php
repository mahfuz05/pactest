<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * You should implement this class however you want.
 * 
 * The only requirement is existence of public function `handleRequest()`
 * as this is what is tested. The constructor's signature must not be changed.
 */
class Application
{

  /**
   * By default the constructor takes a single argument which is a config array.
   * You can handle it however you want.
   * 
   * @param array $config Application config.
   */
  public function __construct(array $config)
  {
    
  }

  /**
   * This method should handle a Request that comes pre-filled with various data.
   *
   * You should implement it however you want and it should return a Response
   * that passes all tests found in ConverterTest.
   * 
   * @param  Request $request The request.
   *
   * @return Response
   */
  public function handleRequest(Request $request): Response
  {
    $path = $request->getPathInfo(); // the URI path being requested
    $response = new Response();
    if (in_array($path, ['', '/']) && $request->getMethod() === 'POST' ) {
      $file = $request->files->get('file');
      if(!($file  && in_array($file->getClientMimeType(), ['application/pdf', 'application/x-pdf']))) {
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
      }
      
      $data = $request->request->all();
      


      var_dump($data, $file->getClientMimeType(), new \SplFileInfo($file));

      //$response->setContent('<html><body><h1>Hello world!</h1></body></html>');
      $response->setStatusCode(Response::HTTP_OK);
      $response->setContent(json_encode([
        'data' => 123,
        ]));
        $response->headers->set('Content-Type', 'application/json');
       
        return $response;
      
      // sets a HTTP response header
      
    }
    $response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
    return $response;
    
   
  }
}
