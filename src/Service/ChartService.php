<?php

namespace App\Service;

use App\Repository\VehiculeRepository;
use App\Repository\LotRepository;
use App\Repository\AvarieRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Service pour la génération des graphiques du tableau de bord
 */
class ChartService
{
    public function __construct(
        #[Autowire(service: VehiculeRepository::class)]
        private readonly VehiculeRepository $vehiculeRepository,
        #[Autowire(service: LotRepository::class)]
        private readonly LotRepository $lotRepository,
        #[Autowire(service: AvarieRepository::class)]
        private readonly AvarieRepository $avarieRepository
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function generateDashboardCharts(): array
    {
        try {
            return [
                'vehicules' => $this->generateVehiculeChart(),
                'lots' => $this->generateLotChart(),
                'avaries' => $this->generateAvarieChart()
            ];
        } catch (\Exception $e) {
            // Log l'erreur et retourne des données par défaut
            return [
                'vehicules' => $this->getEmptyChart('pie'),
                'lots' => $this->getEmptyChart('line'),
                'avaries' => $this->getEmptyChart('bar')
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function generateVehiculeChart(): array
    {
        $data = $this->vehiculeRepository->countByStatus();
        
        return [
            'type' => 'pie',
            'data' => [
                'labels' => array_column($data, 'statut'),
                'datasets' => [[
                    'data' => array_column($data, 'count'),
                    'backgroundColor' => $this->getChartColors(count($data))
                ]]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'right',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Répartition des véhicules'
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function generateLotChart(): array
    {
        $data = $this->lotRepository->getMonthlyStats();
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        
        return [
            'type' => 'line',
            'data' => [
                'labels' => array_map(fn($item) => $months[$item['month'] - 1], $data),
                'datasets' => [[
                    'label' => 'Lots créés',
                    'data' => array_column($data, 'count'),
                    'borderColor' => '#4CAF50',
                    'tension' => 0.1,
                    'fill' => false
                ]]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Évolution mensuelle des lots'
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'stepSize' => 1
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function generateAvarieChart(): array
    {
        $data = $this->avarieRepository->getWeeklyStats();
        
        return [
            'type' => 'bar',
            'data' => [
                'labels' => array_map(fn($item) => 'Semaine ' . $item['week'], $data),
                'datasets' => [[
                    'label' => 'Avaries signalées',
                    'data' => array_column($data, 'count'),
                    'backgroundColor' => '#FFC107'
                ]]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Avaries par semaine'
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'stepSize' => 1
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array<int, string>
     */
    private function getChartColors(int $count): array
    {
        $baseColors = [
            '#4CAF50', // Vert
            '#2196F3', // Bleu
            '#FFC107', // Jaune
            '#F44336', // Rouge
            '#9C27B0', // Violet
            '#FF9800', // Orange
        ];

        return array_map(
            fn($i) => $baseColors[$i % count($baseColors)],
            range(0, $count - 1)
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getEmptyChart(string $type): array
    {
        return [
            'type' => $type,
            'data' => [
                'labels' => [],
                'datasets' => [[
                    'data' => [],
                    'backgroundColor' => []
                ]]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Données non disponibles'
                    ]
                ]
            ]
        ];
    }
} 