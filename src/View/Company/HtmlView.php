<?php
namespace Joomla\Component\Crm\Administrator\View\Company;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    public function display($tpl = null)
    {
        // Получаем данные из модели (Item = результат getItem())
        $this->item = $this->get('Item');

        // Проверка на ошибки модели
        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        // Вызываем рендеринг шаблона (default.php)
        parent::display($tpl);
    }
}
