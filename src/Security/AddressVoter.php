<?php

namespace App\Security;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\CodeNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressVoter extends Voter
{

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

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
            $vote?->addReason($this->translator->trans('voter.user.not_login'));
            return false;
        }
        $address = $subject;
        return match ($attribute) {
            self::EDIT => $this->canEdit($address, $user,$vote),
            self::DELETE => $this->canDelete($address, $user,$vote),
            default => throw new CodeNotFoundException($this->translator->trans(CodeNotFoundException::$codeNotFound))
        };
    }

    private function canEdit(Address $address, User $user, ?Vote $vote): bool
    {
        if ($user === $address->getUser()) {
            return true;
        }
        $vote->addReason($this->translator->trans('vote.not_author',['%email%'=>$user->getEmail(),'%id%'=>$address->getId()]));

        return false;
    }
    private function canDelete(Address $address, User $user, ?Vote $vote): bool{
        if ($this->canEdit($address, $user,$vote)) {
            return true;
        }
        return false;
    }
}
