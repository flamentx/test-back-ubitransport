<?php

namespace App\GraphQL\Mutation;

use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use App\Entity\Student;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StudentMutation implements MutationInterface, AliasedInterface
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function newStudent(array $input): ?Student
    {
        // checks that the required fields are correctly filled in
        if (!isset($input['firstName'])) {
            throw new UserError($this->translator->trans('You must provide a value for %firstName%'));
        }
        if (!isset($input['lastName'])) {
            throw new UserError($this->translator->trans('You must provide a value for %lastName%'));
        }
        if (!isset($input['birthDate'])) {
            throw new UserError($this->translator->trans('You must provide a value for %birthDate%'));
        }

        $student = new Student();
        $student
            ->setFirstName($input['firstName'])
            ->setLastName($input['lastName'])
            ->setBirthDate($input['birthDate'])
        ;

        // persist the new Student object
        $this->em->persist($student);
        $this->em->flush();

        return $student;
    }

    public function updateStudent(int $studentId, array $input): ?Student
    {
        if (!isset($studentId)) {
            throw new UserError($this->translator->trans('You must provide an id'));
        }

        $student = $this->em->getRepository(Student::class)->find($studentId);

        if (!$student) {
            throw new UserError($this->translator->trans('Student not found'));
        }

        $haveToFlush = false;

        // Update only given fields
        if (isset($input['firstName'])) {
            $student->setFirstName($input['firstName']);
            $haveToFlush = true;
        }
        if (isset($input['lastName'])) {
            $student->setLastName($input['lastName']);
            $haveToFlush = true;
        }
        if (isset($input['birthDate'])) {
            $student->setBirthDate($input['birthDate']);
            $haveToFlush = true;
        }

        // flush only if needed
        if ($haveToFlush) {
            // persist the Student object
            $this->em->persist($student);
            $this->em->flush();
        }

        return $student;
    }

    public function deleteStudent(int $studentId): ?array
    {
        if (!isset($studentId)) {
            throw new UserError($this->translator->trans('You must provide an id'));
        }

        $student = $this->em->getRepository(Student::class)->find($studentId);

        if (!$student) {
            throw new UserError($this->translator->trans('Student not found'));
        }

        $this->em->remove($student);
        $this->em->flush();

        return ['content' => 'ok', 'status' => 'success'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'newStudent' => 'newStudent',
            'updateStudent' => 'updateStudent',
            'deleteStudent' => 'deleteStudent'
        ];
    }
}
