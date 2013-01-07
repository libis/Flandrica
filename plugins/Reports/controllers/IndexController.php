<?php
/**
 * @package Reports
 * @subpackage Controllers
 * @copyright Copyright (c) 2009 Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
 
/**
 * Index controller
 *
 * @package Reports
 * @subpackage Controllers
 */
class Reports_IndexController extends Omeka_Controller_Action
{
    /**
     * Sets the model class for the Reports controller.
     */
    public function init()
    {
        $this->_modelClass = 'ReportsReport';
    }
    
    /**
     * Displays the browse page for all reports.
     */
    public function browseAction()
    {
        if(!is_writable(REPORTS_SAVE_DIRECTORY)) {
            $this->flash('Warning: The directory '.REPORTS_SAVE_DIRECTORY.
                         ' must be writable by the server for reports to be'.
                         ' generated.', Omeka_Controller_Flash::ALERT);
        }
        
        if(ini_get('allow_url_fopen') != 1) {
            $this->flash('Warning: The PHP directive "allow_url_fopen" is set'.
                         ' to false.  You will be unable to generate QR Code'.
                         ' reports.', Omeka_Controller_Flash::ALERT);
        }
        
        $reports = $this->getTable('ReportsReport')->findAllReports();
        foreach($reports as $report) {
            $id = $report->id;
            $creator = $report->creator;
            
            $userName = $this->getTable('Entity')->find($creator)->getName();
            $query = unserialize($report->query);
            $params = reports_convertSearchFilters($query);
            $count = $this->getTable('Item')->count($params);
            
            $reportsDisplay[] = array(
                'reportObject' => $report,
                'userName' => $userName,
                'count' => $count);
        }
        $this->view->reports = $reportsDisplay;
        $this->view->formats = reports_getOutputFormats();
    }
    
    public function addAction()
    {
        $varName = strtolower($this->_modelClass);
        $class = $this->_modelClass;
        
        $record = new $class();
        
        try {
            if ($record->saveForm($_POST)) {
                $this->redirect->gotoRoute(array('id'     => "$record->id",
                                                 'action' => 'query'),
                                           'reports-id-action');
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        } catch (Exception $e) {
            $this->flash($e->getMessage());
        }

        $this->view->assign(array($varName=>$record));
    }
    
    /**
     * Edits the filter for a report.
     */
    public function queryAction()
    {
        $report = $this->findById();
        
        if(isset($_GET['search'])) {
            $report->query = serialize($_GET);
            $report->save();
            $this->redirect->goto('index');
        } 
        else {
            $queryArray = unserialize($report->query);
            // Some parts of the advanced search check $_GET, others check
            // $_REQUEST, so we set both to be able to edit a previous query.
            $_GET = $queryArray;
            $_REQUEST = $queryArray;
            $this->view->reportsreport = $report;
        }
        
    }
    
    /**
     * Shows details and generated files for a report.
     */
    public function showAction()
    {
        $report = $this->findById();
        
        $reportFiles = $report->getFiles();
        
        $formats = reports_getOutputFormats();
        
        $this->view->formats = $formats;
        
        $this->view->reportsreport = $report;
        $this->view->reportFiles = $reportFiles;
    }
    
    /**
     * Spawns a background process to generate a new report file.
     */
    public function generateAction()
    {
        $report = $this->findById();
        
        if (!is_writable(REPORTS_SAVE_DIRECTORY)) {
            // Disallow generation if the save directory is not writable
            $this->flash('The directory '.REPORTS_SAVE_DIRECTORY.
                         ' must be writable by the server for reports to be'.
                         ' generated.',
                         Omeka_Controller_Flash::GENERAL_ERROR);
                         
        } else if ($this->_checkPhpPath()) {
            $reportFile = new ReportsFile();
            $reportFile->report_id = $report->id;
            $reportFile->type = $_GET['format'];
            $reportFile->status = ReportsFile::STATUS_STARTING;
        
            // Send the base URL to the background process for QR Code
            // This should be abstracted out to work more generally for
            // all generators.
            if($reportFile->type == 'PdfQrCode') {
                $reportFile->options = serialize(array('baseUrl' => WEB_ROOT));
            }
        
            $reportFile->save();
            
            $command = escapeshellcmd(get_option('reports_php_path')).' '.$this->_getBootstrapFilePath()." -r $reportFile->id";
            $reportFile->pid = $this->_fork($command);
            $reportFile->save();
        }
        $this->redirect->gotoRoute(array('id'     => "$report->id",
                                         'action' => 'show'),
                                   'reports-id-action');
    }
    
    /**
     * Checks if the configured PHP-CLI path points to a valid PHP binary.
     * Flash an appropriate error if the path is invalid.
     */
    private function _checkPhpPath()
    {
        // Try to execute PHP and check for appropriate version
        $command = escapeshellcmd(get_option('reports_php_path')).' -v';
        $output = array();
        exec($command, $output, $returnCode);
        
        $error = 'The configured PHP path ('.get_option('reports_php_path').')';
        if ($returnCode != 0) {
            $this->flashError($error.' is invalid.');
            return false;
        }
        
        // Attempt to parse the output from 'php -v' (the first line only)
        preg_match('/(^\\w+) ([\\d\\.]+)/', $output[0], $matches);
        $cliName    = $matches[1];
        $cliVersion = $matches[2];
        $phpVersion = phpversion();
        
        if ($cliName != 'PHP'  || !$cliVersion) {
            $this->flashError($error.' does not point to a PHP-CLI binary.');
            return false;
        } else if (version_compare($cliVersion, '5.2', '<')) {
            $this->flashError($error.' points to a PHP-CLI binary with an'
                            . " invalid version ($cliVersion).");
            return false;
        } else if ($cliVersion != $phpVersion) {
            $this->flash($error.' points to a PHP-CLI binary with a different' 
                       . " version number ($version) than the one Omeka is"
                       . " running on ($phpVersion).");
        }
        return true;
    }
    
    
    /**
     * Returns the path to the background bootstrap script.
     *
     * @return string Path to bootstrap
     */
    private function _getBootstrapFilePath()
    {
        return REPORTS_PLUGIN_DIRECTORY
             . DIRECTORY_SEPARATOR 
             . 'bootstrap.php';
    }
    
    /**
     * Launch a background process, returning control to the foreground.
     * 
     * @link http://www.php.net/manual/en/ref.exec.php#70135
     * @return int The background process' PID
     */
    private function _fork($command) {
        return exec("$command > /dev/null 2>&1 & echo $!");
    }
}