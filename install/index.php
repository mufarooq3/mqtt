<?php
/**
 * Installs the PHP Login & User Management database
 *
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 *
 * @author       BLiveInHack <bliveinhack@gmail.com>
 * @copyright    Copyright © 2014 icanstudioz.com
 * @license      http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 * @link         http://codecanyon.net/item/8817787
 */
include_once("header.php");

$install = new Install();

class Install {

    private $error;
    private $link;
    private $options = array();
    public static $dbh;

    function __construct() {

        $this->checkInstall($hideError = true);

        if (!empty($_POST)) :

            foreach ($_POST as $key => $value)
                $this->options[$key] = $value;

            $this->validate();

        endif;

        if (!empty($this->error))
            echo $this->error;
    }

    // Run any ol' query passed into this function
    public function query($query, $params = array()) {

        $stmt = self::$dbh->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    // Check for all form fields to be filled out
    private function validate() {


        if (empty($this->options['dbHost']) || empty($this->options['dbUser']) || empty($this->options['dbName']) || empty($this->options['api_key']) || empty($this->options['map_api_key']))
            $this->error = '<div class="alert alert-error">' . _('Fill out all the details please') . '</div>';


        // Check the database connection
        $this->dbLink();
        //$this->installCertificates();
    }

    // See if I can connect to the mysql server
    private function dbLink() {

        if (!empty($this->error))
            return false;

        try {
            self::$dbh = new PDO("mysql:host=" . $this->options['dbHost'] . ";dbname=" . $this->options['dbName'], $this->options['dbUser'], $this->options['dbPass']);
            self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->error = '<div class="alert alert-error">' . _('Database error: ') . $e->getMessage() . '</div>';
        }

        $this->existingTables();
    }

    // Check for an existing install
    private function existingTables() {

        if (empty($this->error)) :

            $this->insertSQL();
            $this->writeFile();
            $this->checkInstall();

        endif;
    }

    // Begin inserting our SQL goodies
       // Begin inserting our SQL goodies
    private function insertSQL() {

        if (empty($this->error)) {

            $this->query("SET NAMES utf8;");

            
            $this->query("CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_active` char(1) NOT NULL DEFAULT '1',
  `api_key` varchar(150) NOT NULL,
  `google_api_key` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3");


            $this->query("insert into admin(username,email,password,is_active,api_key,google_api_key)values('admin','',md5('admin'),1,'" . $this->options['api_key'] . "','".$this->options['map_api_key']."')");

        $this->query("CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(35) NOT NULL,
  `cat_slug` varchar(100) NOT NULL,
  `is_active` char(1) NOT NULL DEFAULT '1',
  `cat_desc` varchar(100) DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;
");


            $this->query("CREATE TABLE IF NOT EXISTS `notifications` (
  `nid` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `message` varchar(512) NOT NULL,
  `link` varchar(100) NOT NULL,
  `emotion` varchar(15) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7203 ;");

            $this->query("CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(35) NOT NULL,
  `gcm_id` varchar(300) NOT NULL,
  `app_type` varchar(20) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` char(1) NOT NULL DEFAULT '1',
  `last_lat` varchar(25) NOT NULL,
  `last_long` varchar(25) NOT NULL,
  `pin_code` varchar(10) DEFAULT NULL,
  `timezone` varchar(50) NOT NULL,
  `device_model` varchar(50) NOT NULL,
  `device_name` varchar(50) NOT NULL,
  `device_memory` varchar(50) NOT NULL,
  `device_os` varchar(100) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `device_api` varchar(50) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26364 ;");


        } else
            $this->error = 'Your tables already exist! I won\'t insert anything.';
    }

    private function writeFile() {

        if ($this->error == '') {

            /** Write config.php if it doesn't exist */
            $fp = @fopen("../config/database.php", "w");
         
            if (!$fp) :
                echo '<div class="alert alert-warning">' . _('Could not create <code>/classes/config.php</code>, please confirm you have permission to create the file.') . '</div>';
                return false;
            endif;
            
            fwrite($fp,"<?php

return [
	'fetch' => PDO::FETCH_CLASS,

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '".$this->options['dbHost']."'),
            'database'  => env('DB_DATABASE', '".$this->options['dbName']."'),
            'username'  => env('DB_USERNAME', '". $this->options['dbUser'] ."'),
            'password'  => env('DB_PASSWORD', '". $this->options['dbPass'] ."'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

    ],
	'migrations' => 'migrations',
	'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],

];
");
            fclose($fp);
        }
    }

    private function checkInstall($hideError = false) {

        if (file_exists('../config/database.php')) :
            ?>
            <div class="row">
                <div class="span6 offset5" style="margin-top: 20%">
                    <div class="alert alert-success"><?php echo ('Hooray ! Installation is all done :)'); ?></div>
                    <div style="clear: both"></div>
                    <p><span class='label label-important'><?php echo ('Important'); ?></span> <?php echo ('Please delete or rename the install folder to prevent intrustion'); ?></p>
                </div>
                <div class="span6 offset5">
                    <h5><?php echo ('What to do now?'); ?></h5>
                    <p><?php echo ('Check out your'); ?> <a href="../"><?php echo ('Dashbord'); ?></a> <?php echo ('page.'); ?></p>
                    <p><?php echo ('Username :- admin <br/> password :- admin'); ?> </p>
                </div>
            </div> <?php
            exit();
        else :
            if (!$hideError)
                $this->error = '<div class="alert alert-error">' . _('Installation is not complete.') . '</div>';
        endif;
    }

}
?>
<div class="row-fluid">
    <div class="span6 offset4">
        <form class="form-horizontal" method="post" action="index.php" enctype='multipart/form-data'>

            <fieldset>
                <legend><?php echo ('Database Info'); ?></legend>
                <div class="control-group">
                    <label class="control-label" for="dbHost"><?php echo ('Host'); ?></label>
                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="dbHost" name="dbHost" value="<?php if (isset($_POST['dbHost'])) echo $_POST['dbHost']; ?>" placeholder="localhost">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dbName"><?php echo ('Database name'); ?></label>
                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="dbName" name="dbName" value="<?php if (isset($_POST['dbName'])) echo $_POST['dbName']; ?>" placeholder="<?php echo ('database_name'); ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dbUser"><?php echo ('Username'); ?></label>
                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="dbUser" name="dbUser" value="<?php if (isset($_POST['dbUser'])) echo $_POST['dbUser']; ?>" placeholder="<?php echo ('db username'); ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dbPass"><?php echo ('Password'); ?></label>
                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="dbPass" name="dbPass" value="<?php if (isset($_POST['dbPass'])) echo $_POST['dbPass']; ?>" placeholder="<?php echo ('db password'); ?>">
                    </div>
                </div>
<!--                <div class="control-group">
                    <label class="control-label" for="dbPass"><?php //echo ('Base Url'); ?></label>
                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="base_url" name="base_url" value="<?php if (isset($_POST['base_url'])) echo $_POST['base_url']; ?>" placeholder="<?php echo ('Base Url(http://www.icanstudioz.com/newsapppro/)'); ?>">
                    </div>
                </div>-->

                <div class="control-group">
                    <label class="control-label" for="dbPass"><?php echo ('Google Api Key'); ?></label>
                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="base_url" name="api_key" value="<?php if (isset($_POST['api_key'])) echo $_POST['api_key']; ?>" placeholder="Google Api Key">
                    </div>
                </div>
				<div class="control-group">
                    <label class="control-label" for="dbPass"><?php echo ('Google Map Api Key'); ?></label>

                    <div class="controls row-fluid">
                        <input type="text" class="input-xlarge span6" id="base_url" name="map_api_key" value="<?php if (isset($_POST['map_api_key'])) echo $_POST['map_api_key']; ?>" placeholder="Google Map Api Key">
                    </div>
					<div class="controls row-fluid">
						<label class="control-label" for="dbPass" style="width:300px"><a href="https://developers.google.com/maps/documentation/javascript/get-api-key">GET GOOGLE MAP-API-KEY</a></label>
					</div>
				 </div>
				
            </fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo ('Install'); ?></button>
            </div>

        </form>

    </div>
</div>

<?php include_once("footer.php"); ?>