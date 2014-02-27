<?php

namespace App\Includes;

use App\Includes\Statics;
use Scabbia\Extensions\Http\Http;
use Scabbia\Extensions\I18n\I18n;
use Scabbia\Extensions\Mvc\Controller;
use Scabbia\Extensions\Database\Database;
use Scabbia\Framework;
use Scabbia\Request;

/**
 * @ignore
 */
class SurveyController extends Controller
{
    /**
     * @ignore
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadDatasource('dbconn');
        $this->dbconn->errorHandling = Database::ERROR_EXCEPTION;
        $this->prerender->add(array(&$this, 'defaultPrerender'));

        if (isset($_GET['lang']) && strlen($_GET['lang']) > 0) {
            $tLanguage = $_GET['lang'];
            I18n::setLanguage($tLanguage);

            if (I18n::$language !== null) {
                Http::sendCookie('lang', I18n::$language['key']);
            }
        } else {
            $tLanguage = Request::cookie('lang', 'en');
            I18n::setLanguage($tLanguage);
        }        
    }

    /**
     * @ignore
     */
    public function defaultPrerender()
    {
        Statics::templateBindings();
    }
}
