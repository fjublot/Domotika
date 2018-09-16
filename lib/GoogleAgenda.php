<?php
/**
 * Classe de lecture d'un agenda Google
 * @author Shivato Web
 * @version 1.0
 * @link http://www.shivato-web.com/blog/php/tuto-classe-de-parsing-google-agenda-en-php
 * @example :
 * $oAgenda = new GoogleAgenda($sFeed);
 * $aEvents = $oAgenda->getEvents($aOptions);
 * $oAgenda->getTitle();
 * foreach ($aEvents as $oEvent) {
 *      $oEvent->getTitle();
 *      $oEvent->getStartDate();
 *      $oEvent->getEndDate();
 *      $oEvent->getAddress();
 *      $oEvent->getDescription();
 * }
 * $aEventsNext = $oAgenda->getNextEvents();
 * $aEventsPrevious = $oAgenda->getPreviousEvents(); $aEventsPrevious == $aEvents
 *
 * Les urls sont accessibles si on est logu� sur le bon compte de l'agenda ou si l'agenda a �t� rendu public
 */
class GoogleAgenda {
    //variables interne de la classe
    protected $_sFeed;
    protected $_dStartMin;
    protected $_dStartMax;
    protected $_sSortorder;
    protected $_sOrderby;
    protected $_iMaxResults;
    protected $_iStartIndex;
    protected $_sUrlNext;
    protected $_sUrlPrevious;
    protected $_aEvents;
    protected $_sSearch;
    protected $_bSingleEvents;
    protected $_bFutureEvents;
    protected $_sTimezone;
    protected $_bShowDeleted;
    //variables disponible
    protected $_dUpdatedDate;
    protected $_sTitle;
    protected $_sSubtitle;
    protected $_sUrlPublic;
    protected $_sAuthorName;
    protected $_sAuthorEmail;
 
    const MAX_RESULTS_DEFAULT = 5;
 
    /**
     * D�finie l'agenda avec lequel on travail
     * @param string $sFeed url de l'agenda
     * @param bool $bFull permet d'avoir toutes les variables rempli s�par�ment, sinon met adresse, date... dans description (default true)
     * @return void
     * @throws GoogleAgendaException si l'url n'est pas valide
     */
    public function __construct($sFeed, $bFull = true) {
        if ($bFull) {
            $sFeed = mb_strrchr($sFeed, 'basic', true) . 'full';
        }
 
        $sFeedContent = @file_get_contents($sFeed);
        if ($sFeedContent !== false && !empty($sFeedContent)) {
            $this->_sFeed = $sFeed;
        }
        else {
            throw new GoogleAgendaException('L\'url ['.$sFeed.'] n\'est pas valide.');
        }
    }
 
    /**
     * getteur de la date de maj de l'agenda
     * @return date
     */
    public function getUpdatedDate() {
        return $this->_dUpdatedDate;
    }
 
    /**
     * getteur du titre de l'agenda
     * @return string
     */
    public function getTitle() {
        return $this->_sTitle;
    }
 
    /**
     * getteur du sous titre de l'agenda
     * @return string
     */
    public function getSubtitle() {
        return $this->_sSubtitle;
    }
 
    /**
     * getteur de l'url public de l'agenda
     * @return string
     */
    public function getUrlPublic() {
        return $this->_sUrlPublic;
    }
 
    /**
     * getteur du nom de l'auteur de l'agenda
     * @return string
     */
    public function getAuthorName() {
        return $this->_sAuthorName;
    }
 
    /**
     * getteur de l'adresse email de l'auteur de l'agenda
     * @return string
     */
    public function getAuthorEmail() {
        return $this->_sAuthorEmail;
    }
 
    /**
     * Getteur des �v�nements selon les param�tres
     * Options :
     * (date Y-m-d) startmin : date du d�but de la lecture (default : date du jour)
     * (date Y-m-d) startmax : date de la fin de la lecture (ne prend pas les �v�nement de la date) (default : null)
     * (string) sortorder : tri des �v�nements, options disponible : ascending, descending (default : ascending)
     * (string) orderby : ordre des �v�nements, options disponible : starttime, lastmodified (default : starttime)
     * (int) maxresults : nombre d'�v�nements retourn�s (default : self::MAX_RESULTS_DEFAULT)
     * (int) startindex : page de r�sultat de la lecture (default : 1)
     * (string) search : texte recherch� dans les �v�nements (default : null)
     * (string) singleevents : prend les �v�nements r�currents � leur date, sinon toutes les dates suivantes sont dans le premier �v�nement r�current trouv�
     *               (d�conseill�, ne marche pas vraiment bien), options : 'true', 'false' (default : 'true')
     * (string) futureevents : ne prend que les �v�nements � venir ou prend aussi ceux d�j� pass� de la premi�re journ�e, options : 'true', 'false' (default : 'false')
     * (string) timezone : d�fini le fuseau horaire (default : Europe/Paris)
     * (string) showdeleted : prend en compte les �v�nements supprim�s, options : 'true', 'false' (default : 'false')
     * @param array $aOptions (options : startmin, startmax, sortorder, orderby, maxresults, startindex, search, singleevents, futureevents, timezone, showdeleted)
     * @return array tableau d'objets des �v�nements de l'agenda
     * @link options : http://code.google.com/intl/fr/apis/calendar/data/2.0/reference.html#Parameters
     * @link options : http://code.google.com/intl/fr/apis/gdata/docs/2.0/reference.html#Queries
     */
    public function getEvents(array $aOptions = array()) {
        //r�cup�ration des options
        $this->_dStartMin = isset($aOptions['startmin']) ? $aOptions['startmin'] : date('Y-m-d');
        $this->_dStartMax = isset($aOptions['startmax']) ? $aOptions['startmax'] : '';
        $this->_sSortorder = isset($aOptions['sortorder']) ? $aOptions['sortorder'] : 'ascending';
        $this->_sOrderby = isset($aOptions['orderby']) ? $aOptions['orderby'] : 'starttime';
        $this->_iMaxResults = isset($aOptions['maxresults']) ? $aOptions['maxresults'] : self::MAX_RESULTS_DEFAULT;
        $this->_iStartIndex = isset($aOptions['startindex']) ? $aOptions['startindex'] : 1;
        $this->_sSearch = isset($aOptions['search']) ? $aOptions['search'] : '';
        $this->_bSingleEvents = isset($aOptions['singleevents']) ? $aOptions['singleevents'] : 'true';
        $this->_bFutureEvents = isset($aOptions['futureevents']) ? $aOptions['futureevents'] : 'false';
        $this->_sTimezone = isset($aOptions['timezone']) ? $aOptions['timezone'] : 'Europe/Paris';
        $this->_bShowDeleted = isset($aOptions['showdeleted']) ? $aOptions['showdeleted'] : 'false';
 
        //construction de l'url avec les options re�us
        $sUrl = $this->_sFeed . '?' .
        ( !empty($this->_dStartMin) ? 'start-min=' . $this->_dStartMin . '&' : '' ) .
        ( !empty($this->_dStartMax) ? 'start-max=' . $this->_dStartMax . '&' : '' ) .
        ( !empty($this->_sSortorder) ? 'sortorder=' . $this->_sSortorder . '&' : '' ) .
        ( !empty($this->_sOrderby) ? 'orderby=' . $this->_sOrderby . '&' : '' ) .
        ( !empty($this->_iMaxResults) ? 'max-results=' . $this->_iMaxResults . '&' : '' ) .
        ( !empty($this->_iStartIndex) ? 'start-index=' . $this->_iStartIndex . '&' : '' ) .
        ( !empty($this->_sSearch) ? 'q=' . $this->_sSearch . '&' : '' ) .
        ( !empty($this->_bSingleEvents) ? 'singleevents=' . $this->_bSingleEvents . '&' : '' ) .
        ( !empty($this->_bFutureEvents) ? 'futureevents=' . $this->_bFutureEvents . '&' : '' ) .
        ( !empty($this->_sTimezone) ? 'ctz=' . $this->_sTimezone . '&' : '' ) .
        ( !empty($this->_bShowDeleted) ? 'showdeleted=' . $this->_bShowDeleted . '&' : '' );
 
        $this->loadUrl($sUrl);
 
        return $this->_aEvents;
    }
 
    /**
     * Getteur des �v�nements suivants avec les m�mes param�tres
     * @return array tableau d'objets des �v�nements de l'agenda, un tableau vide si l'url n'existe pas
     */
    public function getNextEvents() {
        if (!empty($this->_sUrlNext)) {
            $this->loadUrl($this->_sUrlNext);
            return $this->_aEvents;
        }
        else {
            return array();
        }
    }
 
    /**
     * Getteur des �v�nements pr�c�dents avec les m�mes param�tres
     * Utilisable si la fonction getNextEvents() a �t� utilis�s ou si l'option start-index > 1 a �t� utilis�
     * @return array tableau d'objets des �v�nements de l'agenda, un tableau vide si l'url n'existe pas
     */
    public function getPreviousEvents() {
        if (!empty($this->_sUrlPrevious)) {
            $this->loadUrl($this->_sUrlPrevious);
            return $this->_aEvents;
        }
        else {
            return array();
        }
    }
 
    /**
     * Charge l'url du flux xml de l'agenda et rempli les valeurs de l'instance correspondant � l'agenda
     * @param string $sUrl
     * @return void
     */
    protected function loadUrl($sUrl) {
        $this->_aEvents = array();
 
        //lecture du fichier XML
        $oXml = simplexml_load_file($sUrl);
        if ($oXml !== false) {
            $this->_dUpdatedDate = isset($oXml->updated) ? date('Y-m-d H:i:s', strtotime($oXml->updated)) : '';
            $this->_sTitle = isset($oXml->title) ? (string) $oXml->title : '';
            $this->_sSubtitle = isset($oXml->subtitle) ? (string) $oXml->subtitle : '';
            $this->_sAuthorName = isset($oXml->author) && isset($oXml->author->name) ? (string)$oXml->author->name : '';
            $this->_sAuthorEmail = isset($oXml->author) && isset($oXml->author->email) ? (string)$oXml->author->email : '';
            $this->_sUrlPublic = '';
            $this->_sUrlNext = '';
            $this->_sUrlPrevious = '';
            if (isset($oXml->link)) {
                foreach ($oXml->link as $oLink) {
                    if ($oLink->attributes()->rel == 'alternate') {
                        $this->_sUrlPublic = (string) $oLink->attributes()->href;
                    }
                    else if ($oLink->attributes()->rel == 'next') {
                        $this->_sUrlNext = (string) $oLink->attributes()->href;
                    }
                    else if ($oLink->attributes()->rel == 'previous') {
                        $this->_sUrlPrevious = (string) $oLink->attributes()->href;
                    }
                }
            }
 
            if (isset($oXml->entry)) {
                foreach ($oXml->entry as $oDataEvent) {
                    $this->setEvent($oDataEvent);
                }
            }
        }
 
    }
 
    /**
     * Cr�e un nouvel objet GoogleAgendaEvent et l'affecte au tableau d'�v�nements
     * @param SimpleXMLElement $oData
     * @return void
     */
    protected function setEvent(SimpleXMLElement $oData) {
        $oEvent = new GoogleAgendaEvent();
        $oDataChild = $oData->children('http://schemas.google.com/g/2005');
 
        $oEvent->setTitle(isset($oData->title) ? (string) $oData->title : '');
        $oEvent->setPublishedDate(isset($oData->published) ? date('Y-m-d H:i:s', strtotime($oData->published)) : '');
        $oEvent->setUpdatedDate(isset($oData->updated) ? date('Y-m-d H:i:s', strtotime($oData->updated)) : '');
        $oEvent->setAuthorName(isset($oData->author) && isset($oData->author->name) ? (string) $oData->author->name : '');
        $oEvent->setAuthorEmail(isset($oData->author) && isset($oData->author->email) ? (string) $oData->author->email : '');
        $oEvent->setDescription(isset($oData->content) ? (string) $oData->content : '');
        $oEvent->setAddress(isset($oDataChild->where) ? (string) $oDataChild->where->attributes()->valueString : '');
 
        if (isset($oData->link)) {
            foreach ($oData->link as $oLink) {
                if ($oLink->attributes()->rel == 'alternate') {
                    $oEvent->setUrlDetail((string) $oLink->attributes()->href);
                    break;
                }
            }
        }
 
        if (isset($oDataChild->who)) {
            $aPersons = array();
            foreach ($oDataChild->who as $oWho) {
                $aPersons[] = $this->parsePerson($oWho);
            }
            $oEvent->setPersons($aPersons);
        }
 
        if (isset($oDataChild->originalEvent)) {
            $oEvent->setOriginalDate((string) $oDataChild->originalEvent->when->attributes()->startTime);
        }
 
        if (isset($oDataChild->when)) {
            $oEvent->setStartDate(date('Y-m-d H:i:s', strtotime($oDataChild->when->attributes()->startTime)));
            $oEvent->setEndDate(date('Y-m-d H:i:s', strtotime($oDataChild->when->attributes()->endTime)));
 
            if (isset($oDataChild->when->reminder)) {
                $aReminders = array();
                foreach ($oDataChild->when->reminder as $oReminder) {
                    $oReminderEvent = new stdClass();
                    $oReminderEvent->type = (string) $oReminder->attributes()->method;
                    $oReminderEvent->minutes = (string) $oReminder->attributes()->minutes;
                    $aReminders[] = $oReminderEvent;
                }
                $oEvent->setReminders($aReminders);
            }
        }
 
        if (isset($oDataChild->recurrence)) {
            $oEvent->setRecurs(true);
        }
 
        $this->_aEvents[] = $oEvent;
    }
 
    /**
     * Parse les informations des personnes participantes
     * @param SimpleXMLElement $oPerson
     * @return stdClass
     */
    protected function parsePerson(SimpleXMLElement $oPerson) {
        if ($oPerson->attributes()->rel == 'http://schemas.google.com/g/2005#event.organizer') {
            $sRole = 'Organisateur';
        }
        else {
            $sRole = 'Invit�';
        }
 
        if (isset($oPerson->attendeeStatus)) {
            switch ($oPerson->attendeeStatus->attributes()->value) {
                case 'http://schemas.google.com/g/2005#event.accepted' :
                    $sStatus = 'Pr�sent';
                    break;
                case 'http://schemas.google.com/g/2005#event.invited' :
                    $sStatus = 'Invit�';
                    break;
                case 'http://schemas.google.com/g/2005#event.declined' :
                    $sStatus = 'Absent';
                    break;
                case 'http://schemas.google.com/g/2005#event.tentative' :
                    $sStatus = 'Peut-�tre';
                    break;
                default :
                    $sStatus = 'Pr�sent';
 
            }
        }
        else {
            $sStatus = 'Pr�sent';
        }
 
        $oPersonEvent = new stdClass();
        $oPersonEvent->name = (string) $oPerson->attributes()->valueString;
        $oPersonEvent->email = (string) $oPerson->attributes()->email;
        $oPersonEvent->role = $sRole;
        $oPersonEvent->status = $sStatus;
        return $oPersonEvent;
 
    }
}


/**
 * Classe d'entit� d'�v�nement Google Agenda
 * @author Shivato Web
 * @version 1.0
 *
 */
class GoogleAgendaEvent {
 
    protected $_sTitle;
    protected $_dStartDate;
    protected $_dEndDate;
    protected $_sAddress;
    protected $_sDescription;
    protected $_sAuthorName;
    protected $_sAuthorEmail;
    protected $_dPublishedDate;
    protected $_dUpdatedDate;
    protected $_sUrlDetail;
    protected $_aPersons = array();
    protected $_aReminders = array();
    protected $_dOriginalDate;
    protected $_bRecurs = false;
 
    /**
     * Constructeur
     * @return void
     */
    public function __construct() { }
 
    /**
     * setteur titre
     * @param string $sTitle
     * @return void
     */
    public function setTitle($sTitle) {
        $this->_sTitle = $sTitle;
    }
 
    /**
     * getteur titre
     * @return string
     */
    public function getTitle() {
        return $this->_sTitle;
    }
 
    /**
     * setteur date de d�but
     * @param date $dStartDate
     * @return void
     */
    public function setStartDate($dStartDate) {
        $this->_dStartDate = $dStartDate;
    }
 
    /**
     * getteur date de d�but
     * @return date
     */
    public function getStartDate() {
        return $this->_dStartDate;
    }
 
    /**
     * setteur date de fin
     * @param date $dEndDate
     * @return void
     */
    public function setEndDate($dEndDate) {
        $this->_dEndDate = $dEndDate;
    }
 
    /**
     * getteur date de fin
     * @return date
     */
    public function getEndDate() {
        return $this->_dEndDate;
    }
 
    /**
     * setteur adresse
     * @param string $sAddress
     * @return void
     */
    public function setAddress($sAddress) {
        $this->_sAddress = $sAddress;
    }
 
    /**
     * getteur adresse
     * @return string
     */
    public function getAddress() {
        return $this->_sAddress;
    }
 
    /**
     * setteur description
     * @param string $sDescription
     * @return void
     */
    public function setDescription($sDescription) {
        $this->_sDescription = $sDescription;
    }
 
    /**
     * getteur description
     * @return string
     */
    public function getDescription() {
        return $this->_sDescription;
    }
 
    /**
     * setteur date de publication
     * @param date $dPublishedDate
     * @return void
     */
    public function setPublishedDate($dPublishedDate) {
        $this->_dPublishedDate = $dPublishedDate;
    }
 
    /**
     * getteur date de publication
     * @return date
     */
    public function getPublishedDate() {
        return $this->_dPublishedDate;
    }
 
    /**
     * setteur date de modification
     * @param date $dModifiedDate
     * @return void
     */
    public function setUpdatedDate($dUpdatedDate) {
        $this->_dUpdatedDate = $dUpdatedDate;
    }
 
    /**
     * getteur date de modification
     * @return date
     */
    public function getUpdatedDate() {
        return $this->_dUpdatedDate;
    }
 
    /**
     * setteur url d�tail
     * @param string $sUrlDetail
     * @return void
     */
    public function setUrlDetail($sUrlDetail) {
        $this->_sUrlDetail = $sUrlDetail;
    }
 
    /**
     * getteur url d�tail
     * @return string
     */
    public function getUrlDetail() {
        return $this->_sUrlDetail;
    }
 
    /**
     * setteur du nom de l'auteur de l'�v�nement
     * @param string $sAuthorName
     * @return void
     */
    public function setAuthorName($sAuthorName) {
        $this->_sAuthorName = $sAuthorName;
    }
 
    /**
     * getteur du nom de l'auteur de l'�v�nement
     * @return string
     */
    public function getAuthorName() {
        return $this->_sAuthorName;
    }
 
    /**
     * setteur du mail de l'auteur de l'�v�nement
     * @param string $sAuthorEmail
     * @return void
     */
    public function setAuthorEmail($sAuthorEmail) {
        $this->_sAuthorEmail = $sAuthorEmail;
    }
 
    /**
     * getteur du mail de l'auteur de l'�v�nement
     * @return string
     */
    public function getAuthorEmail() {
        return $this->_sAuthorEmail;
    }
 
    /**
     * setteur des personnes attach� � l'�v�nement
     * @param array $aPersons
     * @return void
     */
    public function setPersons(array $aPersons) {
        $this->_aPersons = $aPersons;
    }
 
    /**
     * getteur des personnes attach� � l'�v�nement
     * retourne un tableau d'objet de type stdClass() : $aPersons[0]->name, $aPersons[0]->email, $aPersons[0]->role, $aPersons[0]->status
     * @return array
     */
    public function getPersons() {
        return $this->_aPersons;
    }
 
    /**
     * setteur des rappels attach� � l'�v�nement
     * @param array $aReminders
     * @return void
     */
    public function setReminders(array $aReminders) {
        $this->_aReminders = $aReminders;
    }
 
    /**
     * getteur des rappels attach� � l'�v�nement
     * retourne un tableau d'objet de type stdClass() : $aReminders[0]->type, $aReminders[0]->minutes
     * @return array
     */
    public function getReminders() {
        return $this->_aReminders;
    }
 
    /**
     * setteur date d'origine
     * @param date $dDate
     * @return void
     */
    public function setOriginalDate($dOriginalDate) {
        $this->_dOriginalDate = $dOriginalDate;
    }
 
    /**
     * getteur date d'origine
     * @return date
     */
    public function getOriginalDate() {
        return $this->_dOriginalDate;
    }
 
    /**
     * setteur �v�nement r�current
     * @param bool $bRecurs
     * @return void
     */
    public function setRecurs($bRecurs) {
        $this->_bRecurs = $bRecurs;
    }
 
    /**
     * getteur �v�nement r�current
     * @return bool
     */
    public function getRecurs() {
        return $this->_bRecurs;
    }
    
    public  function GetEvents( $start = null, $finish = null, $relative_url = '' ) {
    $filter = "";
    if ( isset($start) && isset($finish) )
        $range = "<C:time-range start=\"$start\" end=\"$finish\"/>";
    else
        $range = '';

    $filter = <<<EOFILTER
  <C:filter>
    <C:comp-filter name="VCALENDAR">
      <C:comp-filter name="VEVENT">
        $range
      </C:comp-filter>
    </C:comp-filter>
  </C:filter>
EOFILTER;

    return $this->DoCalendarQuery($filter, $relative_url);
  }
}

class GoogleAgendaException extends Exception {
 
}
?>