<?php

namespace App\Security;


use App\Entity\Order;
use App\Entity\User;
use App\Exception\CodeNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = 'view';
    const DELETE = 'delete';
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }
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
            $vote?->addReason($this->translator->trans('voter.user.not_login'));
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
        $vote->addReason($this->translator->trans('vote.not_author',['%email%'=>$user->getEmail(),'%id%'=>$order->getId()]));

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
