<?php
defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;
?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1>Список Компаний CRM</h1>
        </div>
        <div class="col text-end">
            <!-- Кнопка добавления вызывает метод add() в CompaniesController -->
            <a href="<?php echo Route::_('index.php?option=com_crm&task=companies.add'); ?>" class="btn btn-success">
                + Добавить Компанию
            </a>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th>Название</th>
                <th width="10%">Стадия</th>
                <th width="15%">Дата создания</th>
                <th width="10%">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($this->items)) : ?>
                <?php foreach ($this->items as $item) : ?>
                    <tr>
                        <td><?php echo $item->id; ?></td>
                        <td>
                            <a href="<?php echo Route::_('index.php?option=com_crm&view=company&id=' . $item->id); ?>" style="font-weight:bold; text-decoration:none;">
                                <?php echo $item->name; ?>
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $item->stage_name; ?></span>
                            <small class="text-muted">(<?php echo $item->stage_code; ?>)</small>
                        </td>
                        <td><?php echo $item->created; ?></td>
                        <td>
                            <a href="<?php echo Route::_('index.php?option=com_crm&view=company&id=' . $item->id); ?>" class="btn btn-sm btn-primary">
                                Открыть
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">Нет компаний. Нажмите "Добавить".</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
