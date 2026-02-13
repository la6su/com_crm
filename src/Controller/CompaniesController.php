<?php
namespace Joomla\Component\Crm\Administrator\Controller;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Factory; // <--- Добавили Фабрику

defined('_JEXEC') or die;

class CompaniesController extends AdminController
{
    /**
     * Метод для быстрого создания компании (Fast Add)
     */
    public function add()
    {
        // В Joomla 4/5/6 базу данных получаем так:
        $db = Factory::getContainer()->get('DatabaseDriver');
        
        // Создаем "черновик" компании на стадии Ice
        $newCompany = new \stdClass();
        $newCompany->name = 'Новый Клиент ' . date('H:i');
        $newCompany->stage_code = 'C0';
        $newCompany->stage_name = 'Ice';
        $newCompany->context_data = '{}';
        $newCompany->published = 1;
        $newCompany->created = date('Y-m-d H:i:s');

        // Вставляем запись
        $db->insertObject('#__crm_companies', $newCompany, 'id');
        $newId = $db->insertid();

        // Записываем в лог создание
        $log = new \stdClass();
        $log->company_id = $newId;
        $log->event_key = 'create';
        $log->comment = 'Компания создана вручную';
        $log->created = date('Y-m-d H:i:s');
        $db->insertObject('#__crm_history', $log);

        // Перенаправляем сразу в карточку новой компании
        $this->setRedirect(
            'index.php?option=com_crm&view=company&id=' . $newId, 
            'Компания создана! Начинайте работу.'
        );
    }
}
