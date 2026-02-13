<?php
namespace Joomla\Component\Crm\Administrator\Service;

defined('_JEXEC') or die;

class StageService
{
    private const STAGES = [
        'C0' => ['name' => 'Ice',          'next' => 'C1', 'req' => 'has_call_log'],
        'C1' => ['name' => 'Touched',      'next' => 'C2', 'req' => 'discovery_filled'],
        'C2' => ['name' => 'Aware',        'next' => 'W1', 'req' => 'demo_date_set'],
        'W1' => ['name' => 'Interested',   'next' => 'W2', 'req' => 'demo_confirmed'],
        'W2' => ['name' => 'demo_planned', 'next' => 'W3', 'req' => 'demo_visited'],
        'W3' => ['name' => 'Demo_done',    'next' => 'H1', 'req' => 'invoice_created'],
        'H1' => ['name' => 'Committed',    'next' => 'H2', 'req' => 'payment_received'],
        'H2' => ['name' => 'Customer',     'next' => 'A1', 'req' => 'certificate_issued'],
        'A1' => ['name' => 'Activated',    'next' => null, 'req' => null],
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
        $req = self::STAGES[$currentCode]['req'];
        if ($req === null) return false;
        return !empty($contextData[$req]);
    }
    
    // Безопасная версия без $this
    public function getMissingRequirement(string $currentCode, array $contextData): ?string
    {
        if (!isset(self::STAGES[$currentCode])) return null;
        $req = self::STAGES[$currentCode]['req'];
        
        // Проверяем условие напрямую
        if ($req !== null && empty($contextData[$req])) {
            return $req;
        }
        return null;
    }

    public function getScript(string $stageCode): string
    {
        $scripts = [
            'C0' => 'Скрипт: "Добрый день! Соедините с ЛПР..." (Цель: Разговор)',
            'C1' => 'Скрипт: "Какие у вас боли?" (Цель: Заполнить Discovery)',
            'C2' => 'Скрипт: "Давайте посмотрим демо?" (Цель: Назначить дату)',
            'W1' => 'Скрипт: "Подтверждаем встречу?" (Цель: Перевести в статус Планируется)',
            'W2' => 'Ожидание: Клиент должен кликнуть по ссылке на Демо.',
            'W3' => 'Скрипт: "Как вам демо? Высылаю счет." (Цель: Счет)',
            'H1' => 'Ожидание: Ждем оплату счета.',
            'H2' => 'Действие: Выписать удостоверение.',
            'A1' => 'Поздравляем! Клиент в работе.',
        ];
        return $scripts[$stageCode] ?? 'Нет инструкций';
    }
}
