<?php

namespace App\Model;

enum NodeTypeEnum: string
{
    case Group = 'group';
    case Course = 'course';
    case Level = 'level';
    case LessonInLevel = 'lessonInLevel';
    case LessonInUnit = 'lessonInUnit';
    case Unit = 'unit';
    case TaskInLessonInLevel = 'taskInLessonInLevel';
    case TaskInLessonInUnit = 'taskInLessonInUnit';
    case Root = 'root';
}