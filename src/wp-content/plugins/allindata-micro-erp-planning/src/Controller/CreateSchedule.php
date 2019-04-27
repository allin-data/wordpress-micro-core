<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Planning\Model\Schedule;
use AllInData\MicroErp\Planning\Model\Validator\Schedule as ScheduleValidator;
use AllInData\MicroErp\Planning\Model\Resource\Schedule as ScheduleResource;
use DateTime;

/**
 * Class CreateSchedule
 * @package AllInData\MicroErp\Planning\Controller
 */
class CreateSchedule extends AbstractController
{
    const ACTION_SLUG = 'micro_erp_planning_create_schedule';

    /**
     * @var ScheduleValidator
     */
    private $scheduleValidator;
    /**
     * @var ScheduleResource
     */
    private $scheduleResource;

    /**
     * CreateSchedule constructor.
     * @param ScheduleValidator $scheduleValidator
     * @param ScheduleResource $scheduleResource
     */
    public function __construct(ScheduleValidator $scheduleValidator, ScheduleResource $scheduleResource)
    {
        $this->scheduleValidator = $scheduleValidator;
        $this->scheduleResource = $scheduleResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $scheduleData = $this->getParamAsArray('schedule', []);
        $scheduleData['resources'] = $this->mapResourceValues($scheduleData['resources'] ?? [], []);

        /** @var Schedule $schedule */
        $schedule = $this->scheduleResource->getModelFactory()->create($scheduleData);

        $startDate = new DateTime($schedule->getStart());
        $endDate = new DateTime($schedule->getEnd());
        $schedule
            ->setIsReadOnly($schedule->getIsReadOnly() === 'true' ? true : false)
            ->setIsAllday($schedule->getIsAllday() === 'true' ? true : false)
            ->setIsFocused($schedule->getIsFocused() === 'true' ? true : false)
            ->setIsPending($schedule->getIsPending() === 'true' ? true : false)
            ->setIsVisible($schedule->getIsVisible() === 'true' ? true : false)
            ->setStart($startDate->format('Y-m-d H:i:s'))
            ->setEnd($endDate->format('Y-m-d H:i:s'))
            ->setPostTitle($schedule->getTitle())
            ->setPostContent($schedule->getBody())
            ->setPostContentFiltered($schedule->getBody())
            ->setPostStatus($schedule->getIsPending() ? 'private' : 'publish');
        if (!$this->scheduleValidator->validate($schedule)->isValid()) {
            $this->throwErrorMessage(implode(',', $this->scheduleValidator->getErrors()));
        }

        $this->scheduleResource->save($schedule);
        return $schedule->getId();
    }

    /**
     * @param int[] $resourceValueSet
     * @param array $defaultValue
     * @return array
     */
    private function mapResourceValues(array $resourceValueSet, array $defaultValue = []): array
    {
        if (empty($resourceValueSet)) {
            return $defaultValue;
        }

        $idSet = [];
        foreach ($resourceValueSet as $resourceValue) {
            if (is_numeric($resourceValue)) {
                $idSet[] = [
                    'id' => (int)$resourceValue
                ];
            } else {
                $idSet[] = (array)$resourceValue;
            }
        }
        return $idSet;
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredCapabilitySet(): array
    {
        return [\AllInData\MicroErp\Planning\Model\Capability\CreateSchedule::CAPABILITY];
    }
}