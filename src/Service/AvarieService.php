<?php

namespace App\Service;

// Autres use nécessaires...

use App\Entity\Avarie;

class AvarieService
{
    /**
     * @param array<string, mixed> $filters
     * @return array<int, Avarie>
     */
    public function getPaginatedAvaries(int $page, array $filters = []): array
    {
        // Implémentation
        return [];
    }

    /** @return array<string, mixed> */
    public function getAvarieStats(): array
    {
        // Implémentation
        return [
            'total' => 0,
            'resolved' => 0,
            'pending' => 0
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createAvarie(array $data): Avarie
    {
        $avarie = new Avarie();
        // Implémentation
        return $avarie;
    }
} 