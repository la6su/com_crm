<?php
namespace Joomla\Component\Crm\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die;

class CompanyTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        // Связываем класс с таблицей '#__crm_companies' и ключом 'id'
        parent::__construct('#__crm_companies', 'id', $db);
    }
}
