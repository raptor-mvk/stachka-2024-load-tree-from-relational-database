# Репозиторий для доклада "Эффективное получение иерархических данных из реляционной СУБД"

Репозиторий содержит пример реализации алгоритма, описанного в рамках доклада.

Postman-коллекция с примерами запросов содержится в корне репозитория

Важные уточнения:
1. База данных примера содержит достаточно малое количество узлов, что не позволяет продемонстрировать преимущества
   в скорости сборки дерева. Для получения значимой разницы в производительности дерево должно содержать по меньшей
   мере тысячи узлов.
2. Пример направлен на демонстрацию подхода с описанием резолверов для разных типов узлов. В остальных аспектах он
   может содержать не лучшие практики и не является образцом для повторения.

Структура дерева в разрезе сущностей, которая строится в примере, имеет следующий вид:
  - Group (группа курсов)
    - Course (курс)
      - Level (искусственная сущность, требуемая со стороны бизнеса, но отсутствующая как сущность на уровне БД)
        - Unit (юнит, объединяющие несколько уроков)
          - Lesson (урок, лежащий в каком-либо юните)
            - LessonTask (промежуточная служебная сущность, реализующая many-to-many-связь Lesson и Task)
              - Task (задание в уроке)
        - Lesson (урок, который не находится в каком-либо юните, демонстрирует концепцию "пропущенных" уровней)
            - LessonTask
              - Task

Система типов для описания дерева:
  - Root (полностью синтетический узел, необходимый, чтобы получить на выходе дерево, а не лес)
    - Group (безусловно соответствует сущности Group)
      - Course (безусловно соответствует сущности Course)
        - Level (в качестве идентификатора используется суррогатный SurrogateLevelId, демонстрирует концепцию
          "виртуальных" уровней)
          - LessonInLevel (соответствует сущности Lesson, если в ней unit = null, демонстрирует концепцию
            "пропущенного" уровня Unit)
            - TaskInLessonInLevel (соответствует сущности LessonTask, если в родительском Lesson unit = null)
          - Unit (безусловно соответствует сущности Unit)
            - LessonInUit (соответствует сущности Lesson, если в ней unit != null)
              - TaskInLessonInUnit (соответствует сущности LessonTask, если в родительском Lesson unit != null)

Автор: [Михаил Каморин](mailto:m.v.kamorin@gmail.com)
