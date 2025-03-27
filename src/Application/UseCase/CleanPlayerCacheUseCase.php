<?php

namespace App\Application\UseCase;

use App\Domain\Enum\GenderEnum;
use App\Domain\Service\PlayerNameCacheManagerInterface;

class CleanPlayerCacheUseCase
{
    public function __construct(private PlayerNameCacheManagerInterface $cacheManager) {}

    public function execute(GenderEnum $gender): void
    {
        $this->cacheManager->clearNames($gender->value);
    }
}