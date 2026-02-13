<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
// Подключаем сервис, чтобы использовать логику подсказок
use Joomla\Component\Crm\Administrator\Service\StageService;

// Получаем недостающее требование для текущей компании
$service = new StageService();
$missingReq = $service->getMissingRequirement($this->item->stage_code, $this->item->context);

// Словарь подсказок
$hints = [
    'has_call_log'     => 'Зафиксируйте результат звонка',
    'discovery_filled' => 'Заполните форму Discovery',
    'demo_date_set'    => 'Назначьте дату демонстрации',
    'demo_confirmed'   => 'Получите подтверждение встречи',
    'demo_visited'     => 'Дождитесь перехода клиента по ссылке Демо',
    'invoice_created'  => 'Сформируйте и отправьте счет',
    'payment_received' => 'Дождитесь поступления оплаты',
    'certificate_issued' => 'Выдайте удостоверение клиенту',
];

$hintText = $hints[$missingReq] ?? 'Выполните обязательное действие';
?>

<style>
    .crm-container { display: flex; gap: 20px; padding: 20px; font-family: sans-serif; }
    .crm-main { flex: 2; }
    .crm-sidebar { flex: 1; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6; }
    .crm-card { border: 1px solid #e0e0e0; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .crm-btn { display: inline-block; padding: 10px 20px; margin: 5px 0; text-decoration: none; color: white; border-radius: 6px; font-weight: 500; transition: all 0.2s; }
    .crm-btn:hover { opacity: 0.9; color: white; text-decoration: none; }
    .btn-action { background: #0d6efd; border: 1px solid #0d6efd; }
    .btn-next { background: #198754; width: 100%; text-align: center; font-size: 1.1em; padding: 15px; }
    .btn-disabled { background: #e9ecef; color: #adb5bd; border: 1px solid #dee2e6; cursor: not-allowed; pointer-events: none; }
    .badge { background: #17a2b8; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8em; vertical-align: middle; }
    .hint-text { color: #6c757d; font-size: 0.9em; margin-top: 10px; text-align: center; }
</style>

<div class="crm-container">
    <div class="crm-main">
        <h1><?php echo $this->item->name; ?> <span class="badge"><?php echo $this->item->stage_name; ?></span></h1>
        
        <div class="crm-card">
            <h4 class="text-muted">Инструкция менеджера</h4>
            <p class="lead"><?php echo $this->item->script; ?></p>
        </div>

        <div class="crm-card">
            <h4>Действия на этапе</h4>
            
            <?php 
            $id = $this->item->id;
            $s = $this->item->stage_code;
            ?>

            <div class="d-grid gap-2">
                <?php if ($s == 'C0'): ?>
                    <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=call&id='.$id); ?>" class="crm-btn btn-action">📞 Зафиксировать разговор с ЛПР</a>
                <?php endif; ?>

                <?php if ($s == 'C1'): ?>
                    <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=fill_discovery&id='.$id); ?>" class="crm-btn btn-action">📝 Заполнить Discovery форму</a>
                <?php endif; ?>

                <?php if ($s == 'C2'): ?>
                     <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=set_demo_date&id='.$id); ?>" class="crm-btn btn-action">📅 Назначить дату Демо</a>
                <?php endif; ?>

                <?php if ($s == 'W1'): ?>
                     <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=confirm_demo&id='.$id); ?>" class="crm-btn btn-action">👍 Подтвердить встречу</a>
                <?php endif; ?>

                 <?php if ($s == 'W2'): ?>
                     <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=visit_demo&id='.$id); ?>" class="crm-btn btn-action">🔗 (Тест) Клиент открыл ссылку</a>
                <?php endif; ?>
                
                <?php if ($s == 'W3'): ?>
                     <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=create_invoice&id='.$id); ?>" class="crm-btn btn-action">🧾 Выставить счет</a>
                <?php endif; ?>

                <?php if ($s == 'H1'): ?>
                     <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=receive_payment&id='.$id); ?>" class="crm-btn btn-action">💰 Подтвердить получение оплаты</a>
                <?php endif; ?>
                
                <?php if ($s == 'H2'): ?>
                     <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=issue_cert&id='.$id); ?>" class="crm-btn btn-action">📜 Выдать удостоверение</a>
                <?php endif; ?>
            </div>

            <hr style="margin: 30px 0;">

            <!-- БЛОК ПЕРЕХОДА -->
            <?php if ($this->item->next_stage): ?>
                <?php if ($this->item->can_transition): ?>
                    <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=next_stage&id='.$id); ?>" class="crm-btn btn-next">
                        Перейти на этап: <b><?php echo $this->item->next_stage['name']; ?></b> &rarr;
                    </a>
                <?php else: ?>
                    <a href="#" class="crm-btn btn-next btn-disabled">
                         🔒 Следующий этап: <?php echo $this->item->next_stage['name']; ?>
                    </a>
                    <div class="hint-text">
                        Чтобы разблокировать переход, необходимо: <br>
                        <b><?php echo $hintText; ?></b>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-success text-center">
                    <h3>🎉 Сделка успешно закрыта!</h3>
                    Клиент активирован.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="crm-sidebar">
        <h4>История событий</h4>
        <ul style="padding-left: 20px;">
            <?php foreach ($this->item->history as $log): ?>
                <li style="margin-bottom: 10px;">
                    <small class="text-muted"><?php echo date('d.m H:i', strtotime($log->created)); ?></small><br>
                    <?php echo $log->comment; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
