<?php

namespace App\Security;


use App\Entity\Order;
use App\Entity\User;
use App\Exception\CodeNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = 'view';
    const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])) {
            return false;
        }
        if (!$subject instanceof Order) {
            return false;
        }
        return true;
    }

    /**
     * @throws CodeNotFoundException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            $vote?->addReason('L\'utilisateur n\'est pas connecté');
            return false;
        }
        $order = $subject;
        return match ($attribute) {
            self::EDIT => $this->canEdit($order, $user, $vote),
            self::DELETE => $this->canDelete($order, $user, $vote),
            default => throw new CodeNotFoundException(CodeNotFoundException::$codeNotFound)
        };
    }

    private function canEdit(Order $order, User $user, ?Vote $vote): bool
    {
        if ($user === $order->getUser()) {
            return true;
        }
        $vote->addReason(sprintf(
            'Le login de l\'utilisateur (email: %s) n\'est pas l\'auteur du poste (id: %d).', $user->getEmail(), $order->getId()
        ));
        return false;
    }

    private function canDelete(Order $order, User $user, ?Vote $vote): bool
    {
        if ($this->canEdit($order, $user, $vote)) {
            return true;
        }
        return false;
    }
}
