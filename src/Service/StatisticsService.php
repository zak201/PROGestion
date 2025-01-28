<?php

namespace App\Service;

use App\Repository\VehiculeRepository;
use App\Repository\LotRepository;
use App\Repository\AvarieRepository;
use App\Repository\CamionRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;

/**
 * Service de gestion des statistiques globales
 */
class StatisticsService
{
    public function __construct(
        private readonly VehiculeRepository $vehiculeRepository,
        private readonly LotRepository $lotRepository,
        private readonly AvarieRepository $avarieRepository,
        private readonly CamionRepository $camionRepository,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Récupère toutes les statistiques globales
     */
    public function getGlobalStatistics(): array
    {
        try {
            return [
                'vehicules' => $this->getVehiculeStats(),
                'lots' => $this->getLotStats(),
                'avaries' => $this->getAvarieStats(),
                'camions' => $this->getCamionStats()
            ];
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des statistiques globales', [
                'error' => $e->getMessage()
            ]);
            return $this->getEmptyStats();
        }
    }

    private function getVehiculeStats(): array
    {
        return [
            'total' => $this->vehiculeRepository->count([]),
            'by_status' => $this->vehiculeRepository->countByStatus(),
            'recent' => $this->vehiculeRepository->findRecentVehicules(5)
        ];
    }

    private function getLotStats(): array
    {
        return [
            'total' => $this->lotRepository->count([]),
            'active' => $this->lotRepository->countActive(),
            'recent' => $this->lotRepository->findRecentLots(5)
        ];
    }

    private function getAvarieStats(): array
    {
        return [
            'total' => $this->avarieRepository->count([]),
            'non_resolved' => $this->avarieRepository->countNonResolved(),
            'recent' => $this->avarieRepository->findRecentAvaries(5)
        ];
    }

    private function getCamionStats(): array
    {
        return [
            'total' => $this->camionRepository->count([]),
            'available' => $this->camionRepository->countAvailable(),
            'in_mission' => $this->camionRepository->countByStatus('en_mission')
        ];
    }

    /**
     * Génère un fichier Excel avec les statistiques
     */
    public function generateExport(): string
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Configuration de la feuille
            $sheet->setTitle('Statistiques');
            
            // En-têtes
            $sheet->setCellValue('A1', 'Statistiques Globales');
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A3', 'Catégorie');
            $sheet->setCellValue('B3', 'Total');
            $sheet->setCellValue('C3', 'Actifs');
            $sheet->setCellValue('D3', 'En attente');

            // Style des en-têtes
            $sheet->getStyle('A1:D3')->getFont()->setBold(true);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

            // Données
            $stats = $this->getGlobalStatistics();
            $this->fillExportData($sheet, $stats);

            // Ajustement automatique des colonnes
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Sauvegarde
            $writer = new Xlsx($spreadsheet);
            $filename = sprintf('export-stats-%s.xlsx', date('Y-m-d-H-i-s'));
            $filepath = sys_get_temp_dir() . '/' . $filename;
            $writer->save($filepath);

            $this->logger->info('Export des statistiques généré', [
                'filename' => $filename
            ]);

            return $filepath;
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la génération de l\'export', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function fillExportData($sheet, array $stats): void
    {
        $row = 4;
        foreach ($stats as $category => $data) {
            $sheet->setCellValue('A' . $row, ucfirst($category));
            $sheet->setCellValue('B' . $row, $data['total'] ?? 0);
            $sheet->setCellValue('C' . $row, $data['active'] ?? $data['available'] ?? 0);
            $sheet->setCellValue('D' . $row, $data['non_resolved'] ?? $data['in_mission'] ?? 0);
            $row++;
        }
    }

    private function getEmptyStats(): array
    {
        return [
            'vehicules' => ['total' => 0, 'by_status' => [], 'recent' => []],
            'lots' => ['total' => 0, 'active' => 0, 'recent' => []],
            'avaries' => ['total' => 0, 'non_resolved' => 0, 'recent' => []],
            'camions' => ['total' => 0, 'available' => 0, 'in_mission' => 0]
        ];
    }
} 