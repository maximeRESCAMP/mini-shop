<?php

namespace App\Security;



use App\Entity\CartItem;
use App\Entity\User;
use App\Exception\CodeNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartItemVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

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
            $vote?->addReason($this->translator->trans('voter.user.not_login'));
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
        $vote->addReason($this->translator->trans('vote.not_author',['%email%'=>$user->getEmail(),'%id%'=>$cartItem->getId()]));

        return false;
    }
    private function canDelete(CartItem $cartItem, User $user, ?Vote $vote): bool{
        if ($this->canEdit($cartItem, $user,$vote)) {
            return true;
        }
        return false;
    }
}
