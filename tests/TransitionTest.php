<?php
use PHPUnit\Framework\TestCase;
use Joomla\Component\Crm\Administrator\Service\StageService;

class TransitionTest extends TestCase
{
    // Тест 1: Проверка базового пути (Happy Path)
    public function testHappyPathIceToTouched()
    {
        $service = new StageService();
        // Пытаемся перейти с C0, имея 'has_call_log'
        $can = $service->canTransition('C0', ['has_call_log' => 1]);
        $this->assertTrue($can, 'Должно разрешать переход C0->C1 при наличии звонка');
    }

    // Тест 2: Тот самый "Красный тест" (Bug reproduction)
    // Мы хотим убедиться, что нельзя перейти в Committed (H1) без invoice_created
    public function testCannotSkipInvoiceCreation()
    {
        $service = new StageService();
        
        // Ситуация: Мы на W3 (Demo Done). Следующая стадия H1.
        // У нас НЕТ invoice_created в контексте.
        $context = ['demo_visited' => 1]; // У нас есть старые данные, но нет новых
        
        $can = $service->canTransition('W3', $context);
        
        $this->assertFalse($can, 'Система не должна пускать в H1 без выставленного счета');
    }
}
