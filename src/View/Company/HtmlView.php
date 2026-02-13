<?php
namespace Joomla\Component\Crm\Administrator\View\Company;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    protected $item;

    public function display($tpl = null)
    {
        $this->item = $this->get('Item');
        
        if (!$this->item) {
            echo "Компания не найдена (ID=1). Проверьте БД.";
            return;
        }

        parent::display($tpl);
    }
}
