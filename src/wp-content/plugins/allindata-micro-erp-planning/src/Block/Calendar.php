<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;

/**
 * Class Calendar
 * @package AllInData\MicroErp\Planning\Block\Admin
 */
class Calendar extends AbstractBlock
{
    /**
     * @TODO implementation
     * @return array
     */
    public function getSchedules()
    {
        return [
            [
                'id' => 1,
                'calendarId' => 1,
                'title' => 'Demo Schedule #01',
                'category' => 'time',
                'dueDateClass' => '',
                'start' => '2019-04-18T22:30:00+09:00',
                'end' => '2019-04-19T02:30:00+09:00',
                'isReadOnly' => false
            ],
            [
                'id' => 2,
                'calendarId' => 1,
                'title' => 'Demo Schedule #02',
                'category' => 'time',
                'dueDateClass' => '',
                'start' => '2019-04-18T17:30:00+09:00',
                'end' => '2019-04-19T17:31:00+09:00',
                'isReadOnly' => true
            ]
        ];
    }
}