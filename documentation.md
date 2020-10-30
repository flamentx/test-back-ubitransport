# Api documentation

Ce document présente les différentes fonctions de l'api et leurs utilisation.
L'api est disponible a l'url suivante : https://127.0.0.1:8000/graphiql

La partie de gauche permet de saisir les requêtes au format GraphQL, une documentation interne est automatiquement générée avec les différents types d'objets et méthodes ainsi que leurs propriétés.

Des exemples de requêtes GraphQL sont listées ci-dessous a titre d'exemple.

## Ajouter un élève

      mutation createStudent {
      newStudent(input: {
        firstName: "Ariana"
        lastName: "Grande"
        birthDate: "1992-11-28"
      }) {
        id
        firstName
        lastName
        birthDate
      }
    }

## Modifier les informations d'un élève (nom, prénom, date de naissance)

    mutation updateStudent {
      updateStudent(
        studentId: 2,
        input: {
         firstName: "Britney"
         lastName: "Spears"
         birthDate: "1986-07-11"
      }) {
        id
        firstName
        lastName
        birthDate
      }
    }

## Supprimer un élève

    mutation deleteStudent {
      deleteStudent(studentId: 3) {
        content
        status
      }
    }

## Ajouter une note à un élève

    mutation createMark {
      newMark(input: {
        value: 14.5
        subject: "Géo"
        studentId: 2
      }) {
        id
        value
        subject
        student {
          id
          firstName
          lastName
        }
      }
    }

## Récupérer la moyenne de toutes les notes d'un élève

    query getStudentAverageMarks {
      GetAverageStudentMarks(studentId: 1) {
        content
        status
      }
    }

## Récupérer la moyenne générale de la classe (moyenne de toutes les notes données)

    query getClassAverageMarks {
      GetAverageClassMarks {
        content
        status
      }
    }
