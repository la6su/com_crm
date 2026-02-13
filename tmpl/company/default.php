<?php
defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;

// Простой CSS для наглядности
?>
<style>
    .crm-container { display: flex; gap: 20px; padding: 20px; font-family: sans-serif; }
    .crm-main { flex: 2; }
    .crm-sidebar { flex: 1; border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
    .crm-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
    .crm-btn { display: inline-block; padding: 10px 20px; margin: 5px 0; text-decoration: none; color: white; border-radius: 4px; }
    .btn-action { background: #007bff; }
    .btn-next { background: #28a745; width: 100%; text-align: center; }
    .btn-disabled { background: #6c757d; cursor: not-allowed; pointer-events: none; opacity: 0.6; }
    .badge { background: #17a2b8; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8em; }
</style>

<div class="crm-container">
    <div class="crm-main">
        <h1><?php echo $this->item->name; ?> <span class="badge"><?php echo $this->item->stage_name; ?> (<?php echo $this->item->stage_code; ?>)</span></h1>
        
        <div class="crm-card">
            <h3>Инструкция</h3>
            <p><?php echo $this->item->script; ?></p>
        </div>

        <div class="crm-card">
            <h3>Действия</h3>
            
            <?php 
            $id = $this->item->id;
            $s = $this->item->stage_code;
            ?>

            <?php if ($s == 'C0'): ?>
                <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=call&id='.$id); ?>" class="crm-btn btn-action">📞 Позвонить</a>
            <?php endif; ?>

            <?php if ($s == 'C1'): ?>
                <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=fill_discovery&id='.$id); ?>" class="crm-btn btn-action">📝 Заполнить Discovery</a>
            <?php endif; ?>

            <?php if ($s == 'C2' || $s == 'W1'): ?>
                 <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=set_demo&id='.$id); ?>" class="crm-btn btn-action">📅 Назначить Демо</a>
            <?php endif; ?>

             <?php if ($s == 'W2'): ?>
                 <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=visit_demo&id='.$id); ?>" class="crm-btn btn-action">▶️ Провести Демо</a>
            <?php endif; ?>
            
            <?php if ($s == 'W3'): ?>
                 <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=send_proposal&id='.$id); ?>" class="crm-btn btn-action">📧 Отправить КП</a>
            <?php endif; ?>

            <?php if ($s == 'H1'): ?>
                 <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=create_invoice&id='.$id); ?>" class="crm-btn btn-action">💲 Выставить счет</a>
            <?php endif; ?>
            
            <?php if ($s == 'H2'): ?>
                 <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=receive_payment&id='.$id); ?>" class="crm-btn btn-action">💰 Оплата получена</a>
            <?php endif; ?>
            
            <?php if ($s == 'A1'): ?>
                 <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=issue_cert&id='.$id); ?>" class="crm-btn btn-action">📜 Выдать сертификат</a>
            <?php endif; ?>

            <hr>

            <?php if ($this->item->next_stage): ?>
                <?php if ($this->item->can_transition): ?>
                    <a href="<?php echo Route::_('index.php?option=com_crm&task=company.doAction&act=next_stage&id='.$id); ?>" class="crm-btn btn-next">
                        Вперед: <?php echo $this->item->next_stage['name']; ?> &rarr;
                    </a>
                <?php else: ?>
                    <a href="#" class="crm-btn btn-next btn-disabled">
                        Вперед: <?php echo $this->item->next_stage['name']; ?> (Заблокировано)
                    </a>
                    <small style="color:red">Выполните действия выше!</small>
                <?php endif; ?>
            <?php else: ?>
                <div style="color: green; font-weight: bold;">Финиш! Клиент активирован.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="crm-sidebar">
        <h3>История</h3>
        <ul>
            <?php foreach ($this->item->history as $log): ?>
                <li>
                    <small><?php echo $log->created; ?></small><br>
                    <b><?php echo $log->comment; ?></b>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
