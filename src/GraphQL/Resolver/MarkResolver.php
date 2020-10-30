<?php

namespace App\GraphQL\Resolver;

use App\Entity\Mark;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MarkResolver implements ResolverInterface, AliasedInterface
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function getAverageClassMarks(): ?array
    {
        $average = $this->em->getRepository(Mark::class)->getAverageClassMarks();

        return ['content' => $average, 'status' => 'success'];
    }

    public static function getAliases(): array
    {
        return [
            'getAverageClassMarks' => 'getAverageClassMarks',
        ];
    }
}
