<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class RankingDTO implements JsonSerializable
{
    private int $ranking;
    private string $hunter;
    private int $bears;

    public function setRanking(int $ranking): RankingDTO
    {
        $this->ranking = $ranking;
        return $this;
    }

    public function setHunter(string $hunter): RankingDTO
    {
        $this->hunter = $hunter;
        return $this;
    }

    public function setBears(int $bears): RankingDTO
    {
        $this->bears = $bears;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'ranking' => $this->ranking,
            'name' => $this->hunter,
            'bears' => $this->bears,
        ];
    }
}