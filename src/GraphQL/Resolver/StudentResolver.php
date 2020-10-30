<?php

namespace App\GraphQL\Resolver;

use App\Entity\Student;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StudentResolver implements ResolverInterface, AliasedInterface
{
    private $em;
    private $requestStack;
    private $translator;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function getAverageStudentMarks(int $studentId): ?array
    {
        $student = $this->em->getRepository(Student::class)->find($studentId);

        if (!$student) {
            throw new UserError($this->translator->trans('Student not found'));
        }

        return ['content' => $student->getAverageMarks(), 'status' => 'success'];
    }

    public static function getAliases(): array
    {
        return [
            'getAverageStudentMarks' => 'getAverageStudentMarks'
        ];
    }
}
