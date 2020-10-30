<?php

namespace App\GraphQL\Mutation;

use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use App\Entity\Student;
use App\Entity\Mark;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MarkMutation implements MutationInterface, AliasedInterface
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function newMark(array $input): ?Mark
    {
        // checks that the required fields are correctly filled in
        if (!isset($input['value'])) {
            throw new UserError($this->translator->trans('You must provide a value for %value%'));
        }
        if (!isset($input['subject'])) {
            throw new UserError($this->translator->trans('You must provide a value for %subject%'));
        }
        if (!isset($input['studentId'])) {
            throw new UserError($this->translator->trans('You must provide a value for %studentId%'));
        }

        $student = $this->em->getRepository(Student::class)->find($input['studentId']);

        if (!$student) {
            throw new UserError($this->translator->trans('Student not found'));
        }

        $mark = new Mark();
        $mark
            ->setValue($input['value'])
            ->setSubject($input['subject'])
            ->setStudent($student)
        ;

        // persist the new Mark object
        $this->em->persist($mark);
        $student->addMark($mark);

        // persist the inverse side relation
        $this->em->persist($student);
        $this->em->flush();

        return $mark;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'newMark' => 'newMark'
        ];
    }
}
