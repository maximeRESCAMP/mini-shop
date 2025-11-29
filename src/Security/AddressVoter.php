<?php

namespace App\Security;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\CodeNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AddressVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
       if (!in_array($attribute,[self::EDIT, self::DELETE])) {
           return false;
       }
       if (!$subject instanceof Address) {
           return false;
       }
       return true;
    }

    /**
     * @throws CodeNotFoundException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote=null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            $vote?->addReason('L\'utilisateur n\'est pas connecté');
            return false;
        }
        $address = $subject;
        return match ($attribute) {
            self::EDIT => $this->canEdit($address, $user,$vote),
            self::DELETE => $this->canDelete($address, $user,$vote),
            default => throw new CodeNotFoundException(CodeNotFoundException::$codeNotFound)
        };
    }

    private function canEdit(Address $address, User $user, ?Vote $vote): bool
    {
        if ($user === $address->getUser()) {
            return true;
        }
        $vote->addReason(sprintf(
        'Le login de l\'utilisateur (email: %s) n\'est pas l\'auteur du poste (id: %d).',$user->getEmail(),$address->getId()
        ));
        return false;
    }
    private function canDelete(Address $address, User $user, ?Vote $vote): bool{
        if ($this->canEdit($address, $user,$vote)) {
            return true;
        }
        return false;
    }
}
