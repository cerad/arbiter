<?php
namespace Cerad\Security\Login;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Doctrine\DBAL\Connection;

use Cerad\Security\PasswordEncoder;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginAction
{
  private $form;
  private $content;
  private $dbConn;
  private $passwordEncoder;

  public function __construct(
    LoginContent    $content,
    LoginForm       $form,
    Connection      $dbConn,
    PasswordEncoder $passwordEncoder
  )
  {
    $this->form    = $form;
    $this->content = $content;
    $this->dbConn  = $dbConn;
    $this->passwordEncoder = $passwordEncoder;
  }
  public function __invoke(Request $request, Response $response)
  {
    $form = $this->form;

    $form->handleRequest($request);

    if ($form->isValid()) {

      $data = $form->getData();

      $username = $data['username'];
      $password = $data['password'];

      $sql = 'SELECT id,username,email,salt,password,roles FROM users WHERE username = ? OR email = ?';

      $rows = $this->dbConn->executeQuery($sql,[$username,$username])->fetchAll();

      if (count($rows) !== 1) {
        throw new UsernameNotFoundException('User Not Found: ' . $username);
      }
      $user = $rows[0];

      if (!$this->passwordEncoder->isPasswordValid($user['password'],$password,$user['salt'])) {
        throw new BadCredentialsException('Invalid Password: ' . $username);
      }
      $user['roles'] = unserialize($user['roles']);

      print_r($user); die();


      $response->getBody()->write($reporter->getContents());

      $outFilename = 'Availability-' . date('Ymd-Hi') . '.' . $reporter->getFileExtension();

      $headers = [
        'Content-Type'        => $reporter->getContentType(),
        'Content-Disposition' => sprintf('attachment; filename="%s"',$outFilename),
      ];
      foreach($headers as $name => $value) {
        $response = $response->withHeader($name,$value);
      }
      $response = $response->withStatus(201);

      return [$request,$response];
    }
    $response->getBody()->write($this->content->render($form));
    return [$request,$response];  }
}