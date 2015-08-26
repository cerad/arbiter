<?php
namespace Cerad\Component\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Doctrine\DBAL\Connection;

class UserProvider implements UserProviderInterface
{
  protected $dbConn;

  protected $userInterface = 'Cerad\Bundle\UserBundle\Model\UserInterface';

  public function __construct(Connection $dbConn)
  {
    $this->dbConn = $dbConn;
  }

  public function loadUserByUsername($username)
  {
    $sql = 'SELECT id,username,email,salt,password FROM users WHERE username = ? OR email = ?';
    $rows = $this->dbConn->executeQuery($sql,[$username,$username])->fetchAll();
    if (count($rows) !== 1) {
      throw new UsernameNotFoundException('User Not Found: ' . $username);
    }
    return new User($rows[0]);

    $row = $rows[0];
    return $row;

        //die($username);
        // The basic way
        $user1 = $this->userManager->findUserByUsernameOrEmail($username);
        if ($user1) return $user1;
        
        // Check for social network identifiers
        
        // See if a fed person exists
        $event = new FindPersonEvent($username);
        
        $this->dispatcher->dispatch(FindPersonEvent::FindByFedKeyEventName,$event);
        
        $person = $event->getPerson();
        if ($person)
        {
            $user = $this->userManager->findUserByPersonGuid($person->getGuid());
            if ($user) return $user;
        }
        
        // Bail
        throw new UsernameNotFoundException('User Not Found: ' . $username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!($user instanceOf $this->userInterface))
        {
            throw new UnsupportedUserException();
        }
        return $this->userManager->findUser($user->getId());
    }
    public function supportsClass($class)
    {
        return ($class instanceOf $this->userInterface) ? true: false;
    }
    
}
?>
