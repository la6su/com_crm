<?php
// Эмулируем среду Joomla, чтобы скрипт не умирал на проверке defined('_JEXEC')
define('_JEXEC', 1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключаем наш сервис вручную
require_once __DIR__ . '/../src/Service/StageService.php';

// ВАЖНО: Используем правильный Namespace, который сейчас в файле сервиса
// Если вы меняли его на 'Administrator', то используйте этот use:
use Joomla\Component\Crm\Administrator\Service\StageService;
// Если вдруг в сервисе остался просто 'Service', поменяйте use соответственно.

class CrmTestRunner
{
    private $passed = 0;
    private $failed = 0;

    public function run()
    {
        echo "🚀 Запуск тестов CRM Logic...\n";
        echo "---------------------------------------------------\n";

        $this->testInitialState();
        $this->testTransitionLogic();
        $this->testBugFixInvoice();

        echo "---------------------------------------------------\n";
        if ($this->failed === 0) {
            echo "✅ ВСЕ ТЕСТЫ ПРОЙДЕНЫ! ({$this->passed})\n";
        } else {
            echo "❌ ЕСТЬ ОШИБКИ: {$this->failed}\n";
        }
    }

    private function assert($condition, $message)
    {
        if ($condition) {
            echo " [PASS] $message\n";
            $this->passed++;
        } else {
            echo " [FAIL] $message\n";
            $this->failed++;
        }
    }

    private function testInitialState()
    {
        $service = new StageService();
        $next = $service->getNextStage('C0');
        $this->assert($next['code'] === 'C1', "C0 (Ice) должна вести в C1");
    }

    private function testTransitionLogic()
    {
        $service = new StageService();
        
        // C1 -> C2 без условий
        $can = $service->canTransition('C1', []); 
        $this->assert($can === false, "Нельзя перейти из Touched без Discovery");

        // C1 -> C2 с условием
        $can = $service->canTransition('C1', ['discovery_filled' => 1]);
        $this->assert($can === true, "Можно перейти из Touched с Discovery");
    }

    private function testBugFixInvoice()
    {
        $service = new StageService();
        // W3 -> H1 (Нужен счет)
        $context = ['demo_visited' => 1]; 
        $can = $service->canTransition('W3', $context);
        
        $this->assert($can === false, "FIX: Система не пускает в Committed без Счета");
    }
}

// Запуск
$runner = new CrmTestRunner();
$runner->run();
