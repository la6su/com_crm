<?php
namespace Joomla\Component\Crm\Administrator\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

class CompanyController extends BaseController
{
    public function doAction()
    {
        // Проверка токена безопасности (CSRF)
        // $this->checkToken(); 

        $id = $this->input->getInt('id');
        $act = $this->input->get('act');
        
        $model = $this->getModel('Company', 'Administrator');
        $model->performAction($id, $act);
        
        $this->setRedirect('index.php?option=com_crm&view=company&id='.$id);
    }
}
