<?php

include './cfg.php';
$themeDir = "$neko_www/assets/theme";
$tmpPath = "$neko_www/lib/selected_config.txt";
$arrFiles = array();
$arrFiles = glob("$themeDir/*.css");

for($x=0;$x<count($arrFiles);$x++) $arrFiles[$x] = substr($arrFiles[$x], strlen($themeDir)+1);

if(isset($_POST['themechange'])){
    $dt = $_POST['themechange'];
    shell_exec("echo $dt > $neko_www/lib/theme.txt");
    $neko_theme = $dt;
}
if(isset($_POST['fw'])){
    $dt = $_POST['fw'];
    if ($dt == 'enable') shell_exec("uci set neko.cfg.new_interface='1' && uci commit neko");
    if ($dt == 'disable') shell_exec("uci set neko.cfg.new_interface='0' && uci commit neko");
}
$fwstatus=shell_exec("uci get neko.cfg.new_interface");
?>
<?php
function getSingboxVersion() {
    $singBoxPath = '/usr/bin/sing-box'; 
    $command = "$singBoxPath version 2>&1";
    exec($command, $output, $returnVar);
    
    if ($returnVar === 0) {
        foreach ($output as $line) {
            if (strpos($line, 'version') !== false) {
                $parts = explode(' ', $line);
                return end($parts);
            }
        }
    }
    
    return 'Unknown version';
}

$singBoxVersion = getSingboxVersion();
?>
<!doctype html>
<html lang="en" data-bs-theme="<?php echo substr($neko_theme,0,-4) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings - Neko</title>
    <link rel="icon" href="./assets/img/nekobox.png">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/theme/<?php echo $neko_theme ?>" rel="stylesheet">
    <link href="./assets/css/custom.css" rel="stylesheet">
    <script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/js/feather.min.js"></script>
    <script type="text/javascript" src="./assets/js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="./assets/js/neko.js"></script>
  </head>
  <body>

<div class="container-sm container-bg text-center callout border border-3 rounded-4 col-11">
    <div class="row">
        <a href="./" class="col btn btn-lg">🏠 Home</a>
        <a href="./dashboard.php" class="col btn btn-lg">📊 Panel</a>
        <a href="./configs.php" class="col btn btn-lg">⚙️ Configs</a>
        <a href="/nekobox/mon.php" class="col btn btn-lg d-flex align-items-center justify-content-center"></i>📦 Document</a> 
        <a href="#" class="col btn btn-lg">🛠️ Settings</a>
        <h2 class="text-center p-2 mb-3">Theme Settings</h2>
        <form action="settings.php" method="post">
            <div class="container text-center justify-content-md-center">
                <div class="row justify-content-md-center">
                    <div class="col mb-3 justify-content-md-center">
                        <select class="form-select" name="themechange" aria-label="themex">
                            <option selected>Change Theme (<?php echo $neko_theme ?>)</option>
                            <?php foreach ($arrFiles as $file) echo "<option value=\"".$file.'">'.$file."</option>" ?>
                        </select>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col justify-content-md-center mb-3">
                            <input class="btn btn-info" type="submit" value="🖫 Change Theme">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <h2 class="text-center p-2 mb-3">Software Information</h2>
        <table class="table table-borderless mb-3">
            <tbody>
                <tr>
                    <td colspan="2">
                        <h3 class="text-center mb-3">Auto Reload Firewall</h3>
                        <form action="settings.php" method="post">
                            <div class="btn-group d-flex justify-content-center">
                                <button type="submit" name="fw" value="enable" class="btn btn<?php if($fwstatus==1) echo "-outline" ?>-success <?php if($fwstatus==1) echo "disabled" ?>">Enable</button>
                                <button type="submit" name="fw" value="disable" class="btn btn<?php if($fwstatus==0) echo "-outline" ?>-danger <?php if($fwstatus==0) echo "disabled" ?>">Disable</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <h3>Client Version</h3>
                                    <div class="form-control text-center" style="font-family: monospace; text-align: center;">
                                        <span id="cliver"></span>&nbsp;<span id="NewCliver"> </span>
                                    </div>
                                    <div class="text-center mt-2">
                                        <button class="btn btn-cyan" id="checkCliverButton">🔍 Detect</button>
                                        <button class="btn btn-info" id="updateButton" title="Update to Latest Version">🔄 Update</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <h3>Sing-box Core Version</h3>
                                    <div class="form-control text-center">
                                        <div id="singBoxCorever">
                                            <?php echo htmlspecialchars($singBoxVersion); ?>&nbsp;<span id="NewSingbox"></span>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <button class="btn btn-cyan" id="checkSingboxButton">🔍 Detect</button>
                                        <button class="btn btn-pink" id="updateSingboxButton" title="Update Singbox Core">🔄 Update</button>
                                        <button class="btn btn-info" id="updatePuernyaButton" title="Switch to Puernya Core">🔄 Switch</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <h3>Mihomo Core Version</h3>
                                    <div class="form-control text-center">
                                        <span id="corever"></span>&nbsp;<span id="NewMihomo"> </span>
                                    </div>
                                    <div class="text-center mt-2">
                                        <button class="btn btn-cyan" id="checkMihomoButton">🔍 Detect</button> 
                                        <button class="btn btn-success" id="updateCoreButton" title="Update Mihomo Core">🔄 Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="logOutput" class="mt-3"></div>

        <style>
            .table-container {
                overflow-x: auto;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table td {
                padding: 10px;
                word-wrap: break-word;
            }

            .form-control {
                width: 100%;
            }

            .btn {
                white-space: nowrap;
                flex: 1;
            }

            @media (max-width: 767px) {
                .table td {
                    display: block;
                    width: 100%;
                }

                .form-control {
                    display: flex;
                    flex-direction: column;
                }

                .btn-group {
                    flex-direction: column;
                }
            }

            #updateButton:hover {
                background-color: #20B2AA;
            }

            #updateSingboxButton:hover {
                background-color: #FF69B4;
            }

            #updateCoreButton:hover {
                background-color: #90EE90;
            }

            #updatePuernyaButton:hover {
                background-color: #87CEFA;
            }
        </style>

<script>
    document.getElementById('updateButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_script.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = 'Starting to download updates...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\nUpdate completed!';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText;
                    location.reload(); 
                }, 3000);
            } else {
                document.getElementById('logOutput').innerHTML += '\nError occurred：' + xhr.statusText;
            }
        };

        xhr.send();
    });

    document.getElementById('updateSingboxButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'singbox.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = 'Starting to download core update...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\nCore update completed!';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText;
                    location.reload(); 
                }, 3000);
            } else {
                document.getElementById('logOutput').innerHTML += '\\nError occurred: ' + xhr.statusText;
            }
        };

        xhr.send();
    });

    document.getElementById('updatePuernyaButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'puernya.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = 'Starting to download core update...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\nCore update completed!';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText;
                    location.reload(); 
                }, 3000);
            } else {
                document.getElementById('logOutput').innerHTML += '\\nError occurred: ' + xhr.statusText;
            }
        };

        xhr.send();
    });

    document.getElementById('updateCoreButton').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'core.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        document.getElementById('logOutput').innerHTML = 'Starting to download core update...';

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('logOutput').innerHTML += '\nCore update completed!';
                document.getElementById('logOutput').innerHTML += '\n' + xhr.responseText;
                    location.reload(); 
                }, 3000);
            } else {
                document.getElementById('logOutput').innerHTML += '\\nError occurred: ' + xhr.statusText;
            }
        };

        xhr.send();
    });
</script>

<script>
    document.getElementById('checkCliverButton').addEventListener('click', function() {
        document.getElementById('NewCliver').innerHTML = 'Checking for a new version...';
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'update_script.php?check_version=true', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('NewCliver').innerHTML = xhr.responseText;
            } else {
                document.getElementById('NewCliver').innerHTML = 'Version check failed, please try again later.';
            }
        };
        xhr.onerror = function() {
            document.getElementById('NewCliver').innerHTML = 'Network error, please try again later';
        };
        xhr.send();
    });
</script>

<script>
    document.getElementById('checkMihomoButton').addEventListener('click', function() {
        document.getElementById('NewMihomo').innerHTML = 'Checking for a new version...';
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'core.php?check_version=true', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('NewMihomo').innerHTML = xhr.responseText;
            } else {
                document.getElementById('NewMihomo').innerHTML = 'Version check failed, please try again later.';
            }
        };
        xhr.onerror = function() {
            document.getElementById('NewMihomo').innerHTML = 'Network error, please try again later';
        };
        xhr.send();
    });
</script>

<script>
    document.getElementById('checkSingboxButton').addEventListener('click', function() {
        document.getElementById('NewSingbox').innerHTML = 'Checking for a new version...';
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'singbox.php?check_version=true', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('NewSingbox').innerHTML = xhr.responseText;
            } else {
                document.getElementById('NewSingbox').innerHTML = 'Version check failed, please try again later.';
            }
        };
        xhr.onerror = function() {
            document.getElementById('NewSingbox').innerHTML = 'Network error, please try again later';
        };
        xhr.send();
    });
</script>

<script>
function compareVersions(v1, v2) {
    const v1parts = v1.split(/[-.]/).filter(x => x !== 'alpha' && x !== 'beta');
    const v2parts = v2.split(/[-.]/).filter(x => x !== 'alpha' && x !== 'beta');
    
    for (let i = 0; i < Math.max(v1parts.length, v2parts.length); ++i) {
        const v1part = parseInt(v1parts[i]) || 0;
        const v2part = parseInt(v2parts[i]) || 0;
        
        if (v1part > v2part) return 1;
        if (v1part < v2part) return -1;
    }
    
    return 0;
}

function checkSingboxVersion() {
    var currentVersion = '<?php echo getSingboxVersion(); ?>';
    var minVersion = '1.10.0';
    
    if (compareVersions(currentVersion, minVersion) >= 0) {
        return;
    }

    var modalHtml = `
        <div class="modal fade" id="versionWarningModal" tabindex="-1" aria-labelledby="versionWarningModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="versionWarningModalLabel">Version Warning</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Your Sing-box version (${currentVersion}) is lower than the recommended minimum version (v1.10.0).</p>
                        <p>Please consider upgrading to a higher version for optimal performance.</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    var modal = new bootstrap.Modal(document.getElementById('versionWarningModal'));
    modal.show();
    
    setTimeout(function() {
        modal.hide();
    }, 5000);
}

document.addEventListener('DOMContentLoaded', checkSingboxVersion);
</script>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NekoClash</title>
    <link rel="stylesheet" href="/www/nekobox/assets/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .feature-box {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #000000;
            border-radius: 8px;
        }
        .feature-box h6 {
            margin-bottom: 15px;
        }
        .table-container {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #000000;
            border-radius: 8px;
        }
        .table {
            table-layout: fixed;
            width: 100%;
        }
        .table td, .table th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .table thead th {
            background-color: transparent;
            color: #000000;
        }
        .btn-outline-secondary {
            border-color: transparent;
            color: #000000;
        }
        .btn-outline-secondary:hover {
            background-color: transparent;
            color: #000000;
        }
        .footer {
            padding: 15px 0;
            background-color: transparent;
            color: #000000;
        }
        .footer p {
            margin: 0;
        }
        .link-box {
            border: 1px solid #000000;
            border-radius: 8px;
            padding: 10px;
            display: block;
            text-align: center;
            width: 100%;
            box-sizing: border-box; 
            transition: background-color 0.3s ease; 
        }
        .link-box a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">About NekoBox</h2>
        <div class="feature-box text-center">
            <h5>NekoBox</h5>
            <p>NekoBox is a thoughtfully designed Sing-box proxy tool, created specifically for home users, aimed at providing a simple yet powerful proxy solution. Built on PHP and BASH technologies, NekoBox simplifies complex proxy configurations into an intuitive experience, allowing every user to easily enjoy an efficient and secure network environment.</p>
        </div>

        <h5 class="text-center mb-4">Core Features</h5>
        <div class="row">
            <div class="col-md-4 mb-4 d-flex">
                <div class="feature-box text-center flex-fill">
                    <h6>Simplified Configuration</h6>
                    <p>With a user-friendly interface and smart configuration features, easily set up and manage Sing-box proxies.。</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 d-flex">
                <div class="feature-box text-center flex-fill">
                    <h6>Optimized Performance</h6>
                    <p>Ensures optimal proxy performance and stability through efficient scripts and automation.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 d-flex">
                <div class="feature-box text-center flex-fill">
                    <h6>Seamless Experience</h6>
                    <p>Designed for home users, balancing ease of use and functionality, ensuring every family member can conveniently use the proxy service.</p>
                </div>
            </div>
        </div>

        <h5 class="text-center mb-4">Tool Information</h5>
        <div class="d-flex justify-content-center">
            <div class="table-container">
                <table class="table table-borderless mb-5">
                    <tbody>
                        <tr class="text-center">
                            <td>SagerNet</td>
                            <td>MetaCubeX</td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <div class="link-box">
                                    <a href="https://github.com/SagerNet/sing-box" target="_blank">Sing-box</a>
                                </div>
                            </td>
                            <td>
                                <div class="link-box">
                                    <a href="https://github.com/MetaCubeX/mihomo" target="_blank">Mihomo</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h5 class="text-center mb-4">External Links</h5>
        <div class="table-container">
            <table class="table table-borderless mb-5">
                <tbody>
                    <tr class="text-center">
                        <td>Github</td>
                        <td>Github</td>
                    </tr>
                    <tr class="text-center">
                        <td>
                            <div class="link-box">
                                <a href="https://github.com/Thaolga/openwrt-nekobox/issues" target="_blank">Issues</a>
                            </div>
                        </td>
                        <td>
                            <div class="link-box">
                                <a href="https://github.com/Thaolga/openwrt-nekobox" target="_blank">Thaolga</a>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>Telegram</td>
                        <td>MetaCubeX</td>
                    </tr>
                    <tr class="text-center">
                        <td>
                            <div class="link-box">
                                <a href="https://t.me/+J55MUupktxFmMDgx" target="_blank">Telegram</a>
                            </div>
                        </td>
                        <td>
                            <div class="link-box">
                                <a href="https://github.com/MetaCubeX" target="_blank">METACUBEX</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <footer class="text-center">
            <p><?php echo $footer ?></p>
        </footer>
    </div>
    <script src="/www/nekobox/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>