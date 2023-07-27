<?php

namespace AlbuquerqueLucas\UserTaskManager\Utils;

use DateTime;
use DateTimeZone;

class DateTimeManager
{
    public static function getDateTime()
    {
        $now = new DateTime('now');
        $now->setTimezone(new DateTimeZone('America/Sao_Paulo'));
        $dateTime = $now->format('Y-m-d H:i:s');
        return $dateTime;
    }
}