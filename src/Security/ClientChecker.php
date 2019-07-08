<?php
namespace App\Security;
 
use App\Entity\Clients as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
 
class ClientChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
 
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
 
        // user account is expired, the user may be notified
        if (!$user->getIsActive()) {
            throw new \Exception("ce membre n'a pas encore activé son compte");
        }
    }
}