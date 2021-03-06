<?php
session_start();
if (!isset($_SESSION["terminalid"]) || !isset($_SESSION["gamepin"]) || !isset($_SESSION["sessionactive"]) || $_SESSION["sessionactive"] != "1") {
    header("Location: terminalenter.php");
    exit();
}
if (isset($_GET["deactivate"]) && $_GET["deactivate"] == "1b") {
    //to fully deactivate the terminal, type ?deactivate=1b behind the adress of this web page and press enter
    //get terminaldata
    $terminaljson = file_get_contents($_SESSION["terminaldir"]);
    $terminaldata = json_decode($terminaljson,true);
    //register the terminal as deactivated
    $terminaldata["activated"] = false;
    //write the modified data to the terminal file
    $myfile = fopen($_SESSION["terminaldir"], "r+");
    $data_json = json_encode($terminaldata);
    fwrite($myfile, $data_json);
    fclose($myfile);
    //unset session and destroy
    session_unset(); 
    session_destroy();
    //return to the terminalenter page
    header("Location: terminalenter.php");
    exit();
}
//get terminaldata
$terminaljson = file_get_contents($_SESSION["terminaldir"]);
$terminaldata = json_decode($terminaljson,true);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Terminal
        </title>
        <script src="terminal.js"></script>
        <link rel="stylesheet" href="terminal.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    </head>
    <body onLoad="setupData('<?php echo $_SESSION["terminaldir"] . "','" . $_SESSION["gamepin"]; ?>'); refreshpage();">
        <div class="holder-v" onclick="unlockTerminal();">
            <div class="holder-h">
                <div class="holder" id="contentHolder">
                    <p class="idletext">Loading...</p>
                </div>
            </div>
        </div>
    </body>
</html>