<?php
namespace Joomla\Component\Crm\Administrator\Service;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class StageService
{
    private const STAGES = [
        'C0' => ['name' => 'Ice',          'next' => 'C1', 'req' => []],
        'C1' => ['name' => 'Touched',      'next' => 'C2', 'req' => ['has_call']],
        'C2' => ['name' => 'Aware',        'next' => 'W1', 'req' => ['discovery_filled']],
        'W1' => ['name' => 'Interested',   'next' => 'W2', 'req' => ['demo_date_set']],
        'W2' => ['name' => 'demo_planned', 'next' => 'W3', 'req' => ['demo_visited']],
        'W3' => ['name' => 'Demo_done',    'next' => 'H1', 'req' => ['proposal_sent']],
        'H1' => ['name' => 'Committed',    'next' => 'H2', 'req' => ['invoice_created']],
        'H2' => ['name' => 'Customer',     'next' => 'A1', 'req' => ['payment_received']],
        'A1' => ['name' => 'Activated',    'next' => null, 'req' => ['certificate_issued']],
    ];

    public function getNextStage(string $currentCode): ?array
    {
        if (!isset(self::STAGES[$currentCode])) return null;
        $nextCode = self::STAGES[$currentCode]['next'];
        return $nextCode ? ['code' => $nextCode, 'name' => self::STAGES[$nextCode]['name']] : null;
    }

    public function canTransition(string $currentCode, array $contextData): bool
    {
        if (!isset(self::STAGES[$currentCode])) return false;
        $requirements = self::STAGES[$currentCode]['req'];
        foreach ($requirements as $req) {
            if (empty($contextData[$req])) return false;
        }
        return true;
    }

    public function getScript(string $stageCode): string
    {
        $scripts = [
            'C0' => 'Звоните ЛПР. Цель: назначить Discovery.',
            'C1' => 'Заполните форму Discovery. Узнайте боли клиента.',
            'C2' => 'Договоритесь о дате Демо.',
            'W1' => 'Подготовьтесь к Демо.',
            'W2' => 'Проведите Демо. Ссылка должна быть открыта.',
            'W3' => 'Отправьте коммерческое предложение.',
            'H1' => 'Выставьте счет.',
            'H2' => 'Проверьте оплату.',
            'A1' => 'Клиент активирован.',
        ];
        return $scripts[$stageCode] ?? 'Нет инструкций';
    }
}
