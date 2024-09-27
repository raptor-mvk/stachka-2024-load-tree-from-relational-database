<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240926090143 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO \"group\" (id, name, position) VALUES (1, 'General English', 1)");
        $this->addSql("INSERT INTO \"group\" (id, name, position) VALUES (2, 'English for Travel', 3)");
        $this->addSql("INSERT INTO \"group\" (id, name, position) VALUES (3, 'English for Business', 3)");
        $this->addSql("INSERT INTO course (id, name, position, group_id) VALUES (1, 'General English', 1, 1)");
        $this->addSql("INSERT INTO course (id, name, position, group_id) VALUES (2, 'English for Career', 1, 3)");
        $this->addSql("INSERT INTO course (id, name, position, group_id) VALUES (3, 'English for Entrepreneurs', 2, 3)");
        $this->addSql("INSERT INTO course (id, name, position, group_id) VALUES (4, 'European countries', 1, 2)");
        $this->addSql("INSERT INTO course (id, name, position, group_id) VALUES (5, 'Asian countries', 2, 2)");
        for ($level = 1; $level <= 3; $level++) {
            for ($courseId = 1; $courseId <= 5; $courseId++) {
                $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Intro lesson', 1, $level, $courseId, null)");
                if ($courseId <= 3) {
                    $unit1Id = ($level - 1) * 6 + $courseId * 2 - 1;
                    $unit2Id = ($level - 1) * 6 + $courseId * 2;
                    $this->addSql("INSERT INTO unit (id, name, position, level, course_id) VALUES ($unit1Id, 'Unit 1', 1, $level, $courseId)");
                    $this->addSql("INSERT INTO unit (id, name, position, level, course_id) VALUES ($unit2Id, 'Unit 2', 2, $level, $courseId)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 1', 2, $level, $courseId, $unit1Id)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 2', 3, $level, $courseId, $unit1Id)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 3', 4, $level, $courseId, $unit1Id)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 4', 5, $level, $courseId, $unit2Id)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 5', 6, $level, $courseId, $unit2Id)");
                } else {
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 1', 2, $level, $courseId, null)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 2', 3, $level, $courseId, null)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 3', 4, $level, $courseId, null)");
                    $this->addSql("INSERT INTO lesson (name, position, level, course_id, unit_id) VALUES ('Lesson 4', 5, $level, $courseId, null)");
                }
            }
        }
        for ($lessonId = 1, $taskId = 1; $lessonId <= 84; $lessonId++) {
            $taskCount = random_int(3, 6);
            for ($i = 0; $i < $taskCount; $i++, $taskId++) {
                $this->addSql("INSERT INTO task (id, name) VALUES ($taskId, 'Task $i')");
                $this->addSql("INSERT INTO lesson_task (position, lesson_id, task_id) VALUES ($i, $lessonId, $taskId)");
            }
        }
    }

    public function down(Schema $schema): void
    {
    }
}
