<?php

namespace App\Security;



use App\Entity\CartItem;
use App\Entity\User;
use App\Exception\CodeNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CartItemVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
       if (!in_array($attribute,[self::EDIT, self::DELETE])) {
           return false;
       }
       if (!$subject instanceof CartItem) {
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
        $cartItem = $subject;
        return match ($attribute) {
            self::EDIT => $this->canEdit($cartItem, $user,$vote),
            self::DELETE => $this->canDelete($cartItem, $user,$vote),
            default => throw new CodeNotFoundException(CodeNotFoundException::$codeNotFound)
        };
    }

    private function canEdit(CartItem $cartItem, User $user, ?Vote $vote): bool
    {
        if ($user === $cartItem->getUser()) {
            return true;
        }
        $vote->addReason(sprintf(
        'Le login de l\'utilisateur (email: %s) n\'est pas l\'auteur du poste (id: %d).',$user->getEmail(),$cartItem->getId()
        ));
        return false;
    }
    private function canDelete(CartItem $cartItem, User $user, ?Vote $vote): bool{
        if ($this->canEdit($cartItem, $user,$vote)) {
            return true;
        }
        return false;
    }
}
