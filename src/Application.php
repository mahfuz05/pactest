<?php

namespace App;

use App\Converter\ImageConverter;
use App\Converter\PDFToImageConverter;
use App\Uploader\AWSUploaderAdapter;
use App\Uploader\DropboxUploaderAdapter;
use App\Uploader\FileUploader;
use App\Uploader\FTPFileAdapter;
use DropboxStub\DropboxClient;
use Exception;
use FTPStub\FTPUploader;

use PDFStub\Client as PDFStubClient;
use S3Stub\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Dotenv\Dotenv;

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
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__ . '/../.env');
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
    $response->setCharset('UTF-8');
    $responseData = [];
    if (in_array($path, ['', '/']) && $request->getMethod() === 'POST') {
      $file = $request->files->get('file');

      if (!($file  && in_array($file->getClientMimeType(), ['application/pdf', 'application/x-pdf', 'application/octet-stream']) && $file->getClientOriginalExtension() === 'pdf')) {
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
      }


      $data = $request->request->all();

      if (!isset($data['upload'])) {
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
      }



      try {
        $dropbox = new DropboxClient($_ENV['dropbox_access_key'], $_ENV['dropbox_secret_token'], $_ENV['dropbox_container']);
        $ftp = new FTPUploader();
        $s3 = new Client($_ENV['s3_access_key_id'], $_ENV['s3_secret_access_key']);

        $fileUploader = new FileUploader([
          new DropboxUploaderAdapter($dropbox),
          new FTPFileAdapter($ftp),
          new AWSUploaderAdapter($s3)
        ]);

        $pdfFile = new \SplFileInfo($file);



        $pdfClient = new PDFStubClient($_ENV['converter_app_id'], $_ENV['converter_access_token']);
        $pdfConverter = new PDFToImageConverter($pdfClient);

        $image = new ImageConverter([$pdfConverter]);
        $responseData['url'] = $fileUploader->uploadFile($data['upload'],  $pdfFile);

        foreach ($data['formats'] as $format) {
          $url =  $image->convertFile($pdfFile, $format);
          $newPdfFile = new \SplFileInfo($url);
          $responseData['formats'][$format] = $fileUploader->uploadFile($data['upload'],  $newPdfFile);
        }


        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent(json_encode($responseData));
      } catch (Exception $e) {
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
      }


      $response->headers->set('Content-Type', 'application/json');

      return $response;

      // sets a HTTP response header

    }
    $response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
    return $response;
  }
}
