<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 28/01/2018
 * Time: 23:02
 */

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * Class UserVoter
 * @package App\Security
 */
class UserVoter extends Voter
{
    const USER_VIEW = 'user-view';

    private $decisionManager;

    /**
     * UserVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
     * @param EntityManagerInterface         $manager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::USER_VIEW))) {
            return false;
        }

        if ($subject instanceof User) {
            return true;
        }

        return false;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array(User::ROLE_SUPER_ADMIN))) {
            return true;
        }

        switch ($attribute) {
            case self::USER_VIEW:
                return $this->userView($token);
            default:
                return true;
        }
    }

    /**
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function logsView(TokenInterface $token)
    {
        return $this->decisionManager->decide($token, array(User::ROLE_ADMIN));
    }

    /**
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function userView(TokenInterface $token)
    {
        return $this->decisionManager->decide($token, array(User::ROLE_ADMIN));
    }
}
