<?php
namespace Cerad\Security\Login;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Doctrine\DBAL\Connection;

use Cerad\Component\Jwt\JwtCoder;
use Cerad\Security\PasswordEncoder;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginAction
{
  private $form;
  private $content;
  private $dbConn;
  private $passwordEncoder;
  private $jwtCoder;

  public function __construct(
    LoginContent    $content,
    LoginForm       $form,
    Connection      $dbConn,
    PasswordEncoder $passwordEncoder,
    JwtCoder        $jwtCoder
  )
  {
    $this->form    = $form;
    $this->content = $content;
    $this->dbConn  = $dbConn;
    $this->passwordEncoder = $passwordEncoder;
    $this->jwtCoder = $jwtCoder;
  }
  public function __invoke(Request $request, Response $response)
  {
    $form = $this->form;

    $form->handleRequest($request);

    if ($form->isValid()) {

      $data = $form->getData();

      $username = $data['username'];
      $password = $data['password'];

      $sql = 'SELECT id,username,email,account_name,salt,password,roles FROM users WHERE username = ? OR email = ?';

      $rows = $this->dbConn->executeQuery($sql, [$username, $username])->fetchAll();

      if (count($rows) !== 1) {
        throw new UsernameNotFoundException('User Not Found: ' . $username);
      }
      $user = $rows[0];

      if (!$this->passwordEncoder->isPasswordValid($user['password'], $password, $user['salt'])) {
        throw new BadCredentialsException('Invalid Password: ' . $username);
      }
      $user['roles'] = unserialize($user['roles']);

      $accessToken = $this->jwtCoder->encode([
        'iss'      => 'cerad',
        'id'       => $user['id'],
        'name'     => $user['account_name'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'scopes'   => $user['roles'],
      ]);
      $cookie = new Cookie('access_token', $accessToken);

      /** @var Response $response */
      $response = $response->withAddedHeader('Set-Cookie', $cookie->__toString());
      /** @var Response $response */
      $response = $response->withStatus(302);
      /** @var Response $response */
      $response = $response->withHeader('Location', '/');

      return [$request, $response];
    }
    $response->getBody()->write($this->content->render($form));
    return [$request, $response];
  }
}