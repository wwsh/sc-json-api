<?php

namespace App\Service;

use App\Repository\ShoutDataRepository;
use App\Repository\SongDataRepository;

class GCService
{
    public function __construct(
        private SongDataRepository $songDataRepository,
        private ShoutDataRepository $shoutDataRepository
    ) {}

    public function execute(): void
    {
        // remove rows older than 6 months
        $moment = new \Datetime('-1 month');

        $this->songDataRepository->deleteOlder($moment);
        $this->shoutDataRepository->deleteOlder($moment);
    }
}
