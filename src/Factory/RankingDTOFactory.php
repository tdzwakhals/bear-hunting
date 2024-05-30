<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\RankingDTO;

class RankingDTOFactory
{
    private function make(array $data, int $index): RankingDTO
    {
        return (new RankingDTO())
            ->setRanking($index + 1)
            ->setHunter($data['hunter'])
            ->setBears($data['bears']);
    }

    /**
     * @return RankingDTO[]
     */
    public function makeFromArray(array $dataArray): array
    {
        return array_map(
            fn(array $data, int $index) => $this->make($data, $index),
            $dataArray,
            array_keys($dataArray)
        );
    }

}