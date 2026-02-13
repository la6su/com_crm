<?php
namespace Joomla\Component\Crm\Administrator\Model;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\Component\Crm\Administrator\Service\StageService;

defined('_JEXEC') or die;

class CompanyModel extends AdminModel
{
    // Отключаем стандартные формы Joomla, у нас свой UI
    public function getForm($data = array(), $loadData = true) { return false; }

    public function getItem($pk = null)
    {
        // Если $pk null, берем из URL
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
        if ($pk === 0) $pk = $this->input->getInt('id', 1);

        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__crm_companies'))
            ->where($db->quoteName('id') . ' = ' . (int) $pk);
            
        $item = $db->setQuery($query)->loadObject();

        if ($item) {
            $item->context = json_decode($item->context_data, true) ?? [];
            $item->history = $this->getHistory($item->id);
            
            $service = new StageService();
            $item->script = $service->getScript($item->stage_code);
            $item->next_stage = $service->getNextStage($item->stage_code);
            $item->can_transition = $service->canTransition($item->stage_code, $item->context);
        }
        return $item;
    }

    public function performAction($id, $action)
    {
        $db = $this->getDbo();
        $item = $this->getItem($id);
        $context = $item->context;
        $service = new StageService();

        $msg = '';

        switch ($action) {
            case 'call': $context['has_call'] = 1; $msg = 'Звонок совершен'; break;
            case 'fill_discovery': $context['discovery_filled'] = 1; $msg = 'Discovery заполнено'; break;
            case 'set_demo': $context['demo_date_set'] = 1; $msg = 'Демо назначено'; break;
            case 'visit_demo': $context['demo_visited'] = 1; $msg = 'Демо проведено'; break;
            case 'send_proposal': $context['proposal_sent'] = 1; $msg = 'КП отправлено'; break;
            case 'create_invoice': $context['invoice_created'] = 1; $msg = 'Счет выставлен'; break;
            case 'receive_payment': $context['payment_received'] = 1; $msg = 'Оплата получена'; break;
            case 'issue_cert': $context['certificate_issued'] = 1; $msg = 'Сертификат выдан'; break;
            case 'next_stage':
                if ($service->canTransition($item->stage_code, $context)) {
                    $next = $service->getNextStage($item->stage_code);
                    $this->updateStage($id, $next['code'], $next['name']);
                    $this->logHistory($id, "Переход на стадию " . $next['name']);
                    return;
                }
                break;
        }

        if ($msg) {
            $this->updateContext($id, $context);
            $this->logHistory($id, $msg);
        }
    }

    private function updateContext($id, $data)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__crm_companies'))
            ->set('context_data = ' . $db->quote(json_encode($data)))
            ->where('id = ' . (int)$id);
        $db->setQuery($query)->execute();
    }

    private function updateStage($id, $code, $name)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__crm_companies'))
            ->set('stage_code = ' . $db->quote($code))
            ->set('stage_name = ' . $db->quote($name))
            ->where('id = ' . (int)$id);
        $db->setQuery($query)->execute();
    }

    private function logHistory($id, $msg)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__crm_history'))
            ->columns($db->quoteName(['company_id', 'event_key', 'comment']))
            ->values($id . ', "action", ' . $db->quote($msg));
        $db->setQuery($query)->execute();
    }

    private function getHistory($id)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')->from($db->quoteName('#__crm_history'))
            ->where('company_id = ' . (int)$id)
            ->order('created DESC');
        return $db->setQuery($query)->loadObjectList();
    }
}
