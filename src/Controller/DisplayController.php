<?php
namespace Joomla\Component\Crm\Administrator\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

class DisplayController extends BaseController
{
    protected $default_view = 'company';
    
    public function display($cachable = false, $urlparams = array())
    {
        // Принудительно ставим view=company и id=1 если не задано (для теста)
        $this->input->set('view', $this->input->get('view', 'company'));
        if (!$this->input->get('id')) {
            $this->input->set('id', 1);
        }
        return parent::display($cachable, $urlparams);
    }
}
