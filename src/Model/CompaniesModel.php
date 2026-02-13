<?php
namespace Joomla\Component\Crm\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class CompaniesModel extends ListModel
{
    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
              ->from($db->quoteName('#__crm_companies'))
              ->order('created DESC');

        return $query;
    }
}
