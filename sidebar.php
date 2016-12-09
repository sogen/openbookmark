<?php
define ("ABSOLUTE_PATH", dirname (__FILE__) . "/");
require_once (ABSOLUTE_PATH . "lib/webstart.php");
require_once (ABSOLUTE_PATH . "config/config.php");
require_once (ABSOLUTE_PATH . "lib/mysql.php");
$mysql = new mysql;
require_once (ABSOLUTE_PATH . "lib/auth.php");
$auth = new Auth;
require_once (ABSOLUTE_PATH . "lib/lib.php");
require_once (ABSOLUTE_PATH . "lib/login.php");

class sidebar {
        function sidebar () {
                # collect the folder data
                require_once (ABSOLUTE_PATH . "folders.php");
                $this->tree = new folder;
                $this->tree->folders[0] = array ('id' => 0, 'childof' => null, 'name' => $GLOBALS['settings']['root_folder_name']);

                global $username, $mysql;

                $this->counter = 0;

                # collect the bookmark data
                $query = sprintf ("SELECT title, url, description, childof, id, favicon
                        FROM bookmark
                        WHERE user='%s'
                        AND deleted!='1' ORDER BY title",
                        $mysql->escape ($username));

                if ($mysql->query ($query)) {
                        while ($row = mysql_fetch_assoc ($mysql->result)) {
                                if (!isset ($this->bookmarks[$row['childof']])) {
                                        $this->bookmarks[$row['childof']] = array ();
                                }
                                array_push ($this->bookmarks[$row['childof']], $row);
                        }
                }
                else {
                        message ($mysql->error);
                }
        }

        function make_tree ($folderid) {
                if (isset ($this->tree->children[$folderid])) {
                        $this->counter++;
                        foreach ($this->tree->children[$folderid] as $value) {
                                $this->print_folder ($value);
                                $this->make_tree ($value);
                                $this->print_folder_close ($value);
                        }
                        $this->counter--;
                }
                $this->print_bookmarks ($folderid);
        }

        function print_folder ($folderid) {
                echo str_repeat ("    ", $this->counter) . '<li class="closed"><img src="./jquery/images/folder.gif" alt=""> ' . $this->tree->folders[$folderid]['name'] . "\n";
                if (isset ($this->tree->children[$folderid]) || isset ($this->bookmarks[$folderid])) {
                        echo str_repeat ("    ", $this->counter + 1) . "<ul>\n";
                }
        }

        function print_folder_close ($folderid) {
                if (isset ($this->tree->children[$folderid]) || isset ($this->bookmarks[$folderid])) {
                        echo str_repeat ("    ", $this->counter + 1) . "</ul>\n";
                }
                echo str_repeat ("    ", $this->counter) . "</li>\n";
        }

        function print_bookmarks ($folderid) {
                $spacer = str_repeat ("    ", $this->counter);
                if (isset ($this->bookmarks[$folderid])) {
                        foreach ($this->bookmarks[$folderid] as $value) {
                                if ($value['favicon'] && is_file ($value['favicon'])) {
                                        $icon = '<img src="' . $value['favicon'] . '" width="16" height="16" border="0" alt="">';
                                }
                                else {
                                        $icon = '<img src="./jquery/images/file.gif" alt="">';
                                }
                                echo $spacer . '    <li><a href="' . $value['url'] . '" target="_blank">' . $icon . " " . $value['title'] . "</a></li>\n";
                        }
                }
        }
}

?>

<!DOCTYPE HTML>
<html>
<head>

        <title>OpenBookmark</title>
        <link rel="stylesheet" type="text/css" href="./style2.css">
        <!-- <script src="./jquery/jquery.js" type="text/javascript"></script> -->
        <script type="text/javascript" src="./js/jquery-1.11.1.min.js"></script>
        <script src="./jquery/jquery.treeview.js" type="text/javascript"></script>
        <script type="text/javascript">
        $(document).ready(function(){
                $("#browser").Treeview();
        });
        </script>
      <style type="text/css">
      </style>
		<?php // echo ($settings["theme"]!="") ? '<link rel="stylesheet" type="text/css" href="./style'.$settings["theme"].'.css" />' : ""; ?>
        </head>



        <body id="sidebarBody">

<p><a href="./">Back to OpenBookmark</a></p>

<?php

logged_in_only ();

$sidebar = new sidebar;

echo '<ul id="browser" class="dir">' . "\n";
$sidebar->make_tree (0);
echo "</ul>\n";

require_once (ABSOLUTE_PATH . "footer.php");
?>
