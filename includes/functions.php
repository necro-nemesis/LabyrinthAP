<?php
/* Functions for Networking */

function mask2cidr($mask)
{
    $long = ip2long($mask);
    $base = ip2long('255.255.255.255');
    return 32-log(($long ^ $base)+1, 2);
}

/* Functions to write ini files */

function write_php_ini($array, $file)
{
    $res = array();
    foreach ($array as $key => $val) {
        if (is_array($val)) {
            $res[] = "[$key]";
            foreach ($val as $skey => $sval) {
                $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
            }
        } else {
            $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
        }
    }
    if (safefilerewrite($file, implode("\r\n", $res))) {
        return true;
    } else {
        return false;
    }
}

function safefilerewrite($fileName, $dataToSave)
{
    if ($fp = fopen($fileName, 'w')) {
        $startTime = microtime(true);
        do {
            $canWrite = flock($fp, LOCK_EX);
            // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
            if (!$canWrite) {
                usleep(round(rand(0, 100)*1000));
            }
        } while ((!$canWrite)and((microtime(true)-$startTime) < 5));

        //file was locked so now we can store information
        if ($canWrite) {
            fwrite($fp, $dataToSave);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
        return true;
    } else {
        return false;
    }
}



/**
*
* Add CSRF Token to form
*
*/
function CSRFToken()
{
    ?>
<input id="csrf_token" type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES);
    ; ?>" />
<?php
}

/**
*
* Validate CSRF Token
*
*/
function CSRFValidate()
{
    if (hash_equals($_POST['csrf_token'], $_SESSION['csrf_token'])) {
        return true;
    } else {
        error_log('CSRF violation');
        return false;
    }
}

/**
* Test whether array is associative
*/
function isAssoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

/**
*
* Display a selector field for a form. Arguments are:
*   $name:     Field name
*   $options:  Array of options
*   $selected: Selected option (optional)
*       If $options is an associative array this should be the key
*
*/
function SelectorOptions($name, $options, $selected = null, $id = null)
{
    echo '<select class="form-control" name="'.htmlspecialchars($name, ENT_QUOTES).'"';
    if (isset($id)) {
        echo ' id="' . htmlspecialchars($id, ENT_QUOTES) .'"';
    }

    echo '>' , PHP_EOL;
    foreach ($options as $opt => $label) {
        $select = '';
        $key = isAssoc($options) ? $opt : $label;
        if ($key == $selected) {
            $select = ' selected="selected"';
        }

        echo '<option value="'.htmlspecialchars($key, ENT_QUOTES).'"'.$select.'>'.
            htmlspecialchars($label, ENT_QUOTES).'</option>' , PHP_EOL;
    }

    echo '</select>' , PHP_EOL;
}

/**
*
* @param string $input
* @param string $string
* @param int $offset
* @param string $separator
* @return $string
*/
function GetDistString($input, $string, $offset, $separator)
{
    $string = substr($input, strpos($input, $string)+$offset, strpos(substr($input, strpos($input, $string)+$offset), $separator));
    return $string;
}

/**
*
* @param array $arrConfig
* @return $config
*/
function ParseConfig($arrConfig)
{
    $config = array();
    foreach ($arrConfig as $line) {
        $line = trim($line);
        if ($line != "" && $line[0] != "#") {
            $arrLine = explode("=", $line);
            $config[$arrLine[0]] = (count($arrLine) > 1 ? $arrLine[1] : true);
        }
    }
    return $config;
}

/**
*
* @param string $freq
* @return $channel
*/
function ConvertToChannel($freq)
{
    if ($freq >= 2412 && $freq <= 2484) {
        $channel = ($freq - 2407)/5;
    } elseif ($freq >= 4915 && $freq <= 4980) {
        $channel = ($freq - 4910)/5 + 182;
    } elseif ($freq >= 5035 && $freq <= 5865) {
        $channel = ($freq - 5030)/5 + 6;
    } else {
        $channel = -1;
    }
    if ($channel >= 1 && $channel <= 196) {
        return $channel;
    } else {
        return 'Invalid Channel';
    }
}

/**
* Converts WPA security string to readable format
* @param string $security
* @return string
*/
function ConvertToSecurity($security)
{
    $options = array();
    preg_match_all('/\[([^\]]+)\]/s', $security, $matches);
    foreach ($matches[1] as $match) {
        if (preg_match('/^(WPA\d?)/', $match, $protocol_match)) {
            $protocol = $protocol_match[1];
            $matchArr = explode('-', $match);
            if (count($matchArr) > 2) {
                $options[] = htmlspecialchars($protocol . ' ('. $matchArr[2] .')', ENT_QUOTES);
            } else {
                $options[] = htmlspecialchars($protocol, ENT_QUOTES);
            }
        }
    }

    if (count($options) === 0) {
        // This could also be WEP but wpa_supplicant doesn't have a way to determine
        // this.
        // And you shouldn't be using WEP these days anyway.
        return 'Open';
    } else {
        return implode('<br />', $options);
    }
}

/**
*
*
*/
/*LOKINET FUNCTIONS ADDED HERE*/

function DisplayLokinetConfig()
{
    exec('pidof lokinet | wc -l', $lokinetstatus);
    $rulestate = exec("ip rule show default | grep lokinet | awk {'print $5'}", $output);
    $lokiversion = exec("dpkg -s lokinet | grep '^Version:'", $output);
        ?>
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-primary">
              <div class="panel-heading"><i class="fa fa-eye-slash fa-fw"></i> Configure Lokinet</div>
        <!-- /.panel-heading -->
                <div class="panel-body">
        <!-- Nav tabs -->
                  <ul class="nav nav-tabs">
                <li class="active"><a href="#basic" data-toggle="tab">Exit Node Settings</a>
                </li>
                <li><a href="#daemon" data-toggle="tab">Daemon Settings</a>
                </li>
                </ul>
        <!-- Tab panes -->
                  <div class="tab-content">
                <p><?php echo $status; ?></p>
                <p><?php echo "Current Lokinet $lokiversion"; ?></p>
                    <div class="tab-pane fade in active" id="basic">
                <form role="form" action="?page=save_hostapd_conf" method="POST">
	              <h5>Enter Exit Node Data to activate:</h5>
                <label for="exitaddress">Exit Address:</label>
                <input type="text" class="form-control" placeholder="enter exit address here" id="exitaddress" name="exitaddress">
                <label for="exitkey">Exit Key: (optional)</label>
                <input type="text" class="form-control" placeholder="enter exit key here" id="exitkey" name="exitkey">
                <br/>
                <?php
    GLOBAL $exitstatus;
    if ($exitstatus == FALSE) {
      echo '<input type="submit" class="btn btn-success" name="StartExit" value="Start Exit" />' , PHP_EOL;
    } else {
      echo '<input type="submit" class="btn btn-danger" name="StopExit" value="Stop Exit" />' , PHP_EOL;
    }
    if ($lokinetstatus[0] == 0) {
      echo '<input type="submit" class="btn btn-success" name="StartDaemon" value="Start Daemon" />' , PHP_EOL;
    } else {
      echo '<input type="submit" class="btn btn-danger" name="StopDaemon" value="Stop Daemon" />' , PHP_EOL;
    } ?><h5><?php echo _("Your development support is greatly appreciated | Loki Address:"); ?></h5>
    <h5><pre><?php echo _("LA8VDcoJgiv2bSiVqyaT6hJ67LXbnQGpf9Uk3zh9ikUKPJUWeYbgsd9gxQ5ptM2hQNSsCaRETQ3GM9FLDe7BGqcm4ve69bh"); ?></pre></h5>
                  </div>
                    <div class="tab-pane fade" id="daemon">
                    <h4>Lokient Daemon</h4>
                      <div class="row">
                        <div class="col-lg-12">
                 <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#instruct">Instructions</button>
                          <div id="instruct" class="collapse">The 3 buttons below must be armed (red) to connect to Lokinet. If there isn't a current lokinet.ini file found on the system the "Generate.ini" button will be green. The .ini file must be generated prior to connecting to Lokinet by pressing the button which will automatically write the required .ini file. Similarly the absense of a valid bootstrap will be indicated by a green "Bootstrap" button. Applying a bootstrap by pressing the apply button without submitting a valid URL in the textbox area will apply the original default bootstrap in place of one being provided. Stopping the daemon also exits Lokinet. To summarize, if necessary generate the .ini and bootstrap Lokinet then you are able to connect to Lokinet by starting the daemon and letting the network establish itself.
                          </div>
                          <div class="row">
                            <div class="form-group col-lg-12">
                  <h5>Enter a valid bootstrap url below and apply to overwrite the current bootstrap:</h5>
                  <label for="lokinetbootstrap">Bootstrap url:</label>
                  <input type="url" class="form-control" placeholder="https://seed.lokinet.org/lokinet.signed" id="lokinetbootstrap" name="lokinetbootstrap">
                  <br/>
<?php
    $filename = '/var/lib/lokinet/lokinet.ini';
    if ($lokinetstatus[0] == 0) {
        echo '<input type="submit" class="btn btn-success" name="StartDaemon" value="Start Daemon" />' , PHP_EOL;
    } else {
        echo '<input type="submit" class="btn btn-danger" name="StopDaemon" value="Stop Daemon" />' , PHP_EOL;
    }

    if (file_exists($filename)) {
        echo '<input type="submit" class="btn btn-danger" name="ReGenerateLokinet" value="Regenerate .ini" />' , PHP_EOL;
    } else {
        echo '<input type="submit" class="btn btn-success" name="GenerateLokinet" value="Generate .ini" />' , PHP_EOL;
    } ?>

                  <input type="submit" class="btn btn-danger" name="ApplyLokinetSettings" value="Re-Bootstrap" />
                  <h5><?php echo _("Your development support is greatly appreciated | Loki Address:"); ?></h5>
                  <h5><pre><?php echo _("LA8VDcoJgiv2bSiVqyaT6hJ67LXbnQGpf9Uk3zh9ikUKPJUWeYbgsd9gxQ5ptM2hQNSsCaRETQ3GM9FLDe7BGqcm4ve69bh"); ?></pre></h5>
                </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- /.tab-content -->
        </div><!-- /.panel-body -->
      <div class="panel-footer">Contact Loki user groups on Session to obtain Exit Access</div>
    </div><!-- /.panel-primary -->
  </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
      <?php
}

/**
*
*
*/
function ActivateLokinetConfig()
{
        /* Lokinet script commands start HERE
        ////
        //// LOKINET
        ////
        //*/

    //START
    if (isset($_POST['StartDaemon'])) {
        exec('sudo /var/lib/lokinet/lokilaunch.sh start');

    //STOP
    } elseif (isset($_POST['StopDaemon'])) {
        exec('sudo /var/lib/lokinet/lokilaunch.sh stop');

    //START EXIT
    } elseif (isset($_POST['StartExit'])) {
        $exit = $_POST['exitaddress'];
        $token = $_POST['exitkey'];
        $exit=str_replace("'", "", $exit);
        $token=str_replace("'", "", $token);
        $output = shell_exec("sudo /var/lib/lokinet/lokilaunch.sh exitup '".$exit."''" .$token."'");
        echo "<pre><strong>$output</strong></pre>";
        GLOBAL $exitstatus;
        $exitstatus = TRUE;

    //STOP EXIT
    } elseif (isset($_POST['StopExit'])) {
        exec ('sudo /var/lib/lokinet/lokilaunch.sh exitdown');
        GLOBAL $exitstatus;
        $exitstatus = FALSE;

    //GENERATE LOKINET.INI
    } elseif (isset($_POST['GenerateLokinet'])) {
        ?>
    <div class="alert alert-success">
    Generating Lokinet Configuration
    </div>
    <?php
    $output = shell_exec('sudo /var/lib/lokinet/lokilaunch.sh gen');
        echo "<pre><strong>$output</strong></pre>";

    //REGENERATE LOKINET.INI
    } elseif (isset($_POST['ReGenerateLokinet'])) {
        ?>
    <div class="alert alert-success">
    Regenerating Lokinet Configuration
    </div>
    <?php
    $output = shell_exec('sudo /var/lib/lokinet/lokilaunch.sh gen');
        echo "<pre><strong>$output</strong></pre>";

    //APPLY LOKINET-BOOTSTRAP
    } elseif (isset($_POST['ApplyLokinetSettings'])) {
    $bootstrap = $_POST['lokinetbootstrap'];
        $bootstrap=str_replace("'", "", $bootstrap);
        $output = shell_exec('sudo /var/lib/lokinet/lokilaunch.sh bootstrap '.$bootstrap.'');
        $output = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $output);
        echo "<pre><strong>$output</strong></pre>";
    }

    DisplayLokinetConfig();
}
?>
