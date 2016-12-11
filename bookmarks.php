<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<?php
if (basename ($_SERVER['SCRIPT_NAME']) == basename (__FILE__)) {
	die ("no direct access allowed");
}

function list_bookmarks ($bookmarks, $show_checkbox, $show_folder, $show_icon, $show_link, $show_desc, $show_date, $show_edit, $show_move, $show_delete, $show_share, $show_header, $user = false, $scriptname="") {
	global $folderid,
		$expand,
		$settings,
		$column_width_folder,
		$bookmark_image,
		$edit_image,
		$move_image,
		$delete_image,
		$folder_opened,
		$folder_opened_public,
		$date_formats,
		$order;


	if ($scriptname == "")
		$scriptname = $_SERVER['SCRIPT_NAME'];

	# print the bookmark header if enabled.
	# Yes, it's ugly PHP code, but beautiful HTML code.
	if ($show_header) {
		if ($order[0] == 'titleasc') {
			$sort_t = 'titledesc';
			$img_t = '<i class="fa fa-caret-up"></i>';
		}
		else if ($order[0] == 'titledesc') {
			$sort_t = 'titleasc';
			$img_t = '<i class="fa fa-caret-down"></i>';
		}
		else {
			$sort_t = 'titleasc';
			$img_t = '<i class="fa fa-caret-down"></i>';
		}

		if ($order[0] == 'dateasc') {
			$sort_d = 'datedesc';
			$img_d = '<i class="fa fa-caret-up"></i>';
		}
		else if ($order[0] == 'datedesc') {
			$sort_d = 'dateasc';
			$img_d = '<i class="fa fa-caret-down"></i>';
		}
		else {
			$sort_d = 'dateasc';
			$img_d = '<i class="fa fa-caret-down"></i>';
		}

		echo '<div class="container-fluid"><div class="row">' . "\n";
		if ($show_folder) {
			echo "\t" . '<div>&nbsp;</div>' . "\n";
		}
		if ($show_checkbox) {
			echo "\t\t" . '<td>' . "\n";
			echo "\t\t\t" . '<div class="checkbox">
    <label><input type="checkbox" name="CheckAll" onClick="selectthem(\'checkall\', this.checked)"> Check All  </label>
  </div>' . "\n";
			echo "\t\t" . '</td>' . "\n";
		}
		if ($show_date) {
			$query_data = array (
				'folderid' => $folderid,
				'expand' => implode (",", $expand),
				'order' => $sort_d,
			);
			if ($user) {
				$query_data['user'] = $user;
			}
			$query_string = assemble_query_string ($query_data);
			echo "\t\t" . '<div class="col-xs-6 col-sm-4">' . "\n";
			echo "\t\t\t" . '' . "\n";
			echo "\t\t\t\t" . '<a href="' . $scriptname . '?' . $query_string . '" class="">Date ' . $img_d . '</a></div>' . "\n";
			echo "\t\t\t" . '' . "\n";

			if ($show_edit) {
				echo "\t\t\t" . '<i class="fa fa-pencil-square-o invisible"></i>' . "\n";
			}
			if ($show_move) {
				echo "\t\t\t" . '<i class="fa fa-arrows invisible"></i>' . "\n";
			}
			if ($show_delete) {
				echo "\t\t\t" . '<i class="fa fa-times invisible"></i>' . "\n";
			}
			// echo "\t\t" . '</div>' . "\n";
		}
		echo "\t\t" . '<td>' . "\n";
		if ($show_icon) {
			echo "\t\t\t" . '<i class="fa fa-bookmark invisible"></i>' . "\n";
		}
		$query_data ['order'] = $sort_t;
		$query_string = assemble_query_string ($query_data);
		echo "\t\t\t" . '<div class="col-xs-6 col-sm-4"><a href="' . $scriptname . '?' . $query_string . '" class="blink">Title ' . $img_t . '</a>' . "\n";
		// echo '<a id="openAll" style="font-weight:800;float:right; margin-right: 60px;" href="javascript:openAll();">[Open All]</a>';
		echo "\t\t" . '</td>' . "\n";
		echo "\t" . '</div>' . "\n\n";
	}


	if ($show_folder) {
		require_once (ABSOLUTE_PATH . "folders.php");
		$tree = new folder;
	}



// start of the table
	echo '<form name="bookmarks" action="" class="form"><table class="table">' . "\n";



// Start of ForEach
	foreach ($bookmarks as $value) {
		echo '<tr>' . "\n";


						# the share, date and edit/move/delete icon section
						echo "\t" . '<td>' . "\n";


		# the folders, only needed when searching for bookmarks
		if ($show_folder) {
			if ($value['fid'] == null) {
				$value['name'] = $settings['root_folder_name'];
				$value['fid'] = 0;
			}
			if ($value['fpublic']) {
				$folder_image = $folder_opened_public;
			}
			else {
				$folder_image = $folder_opened;
			}
			$expand = $tree->get_path_to_root ($value['fid']);
			//(($column_width_folder == 0) ? "auto" : $column_width_folder)
			echo "\t" . '<div class="form-group">';
			echo '<a class="" href="./index.php?expand=' . implode (",", $expand) . '&folderid='. $value['fid'] .'#' . $value['fid'] . '">';
			echo $folder_image . " " . $value['name'] . "</a>";
			echo "</div>\n";
		}




// echo "</div>\n\n";




		# the checkbox and favicon section
		echo "\t" . '<td>' . "\n";
		# the checkbox
	//	if ($show_checkbox){
			echo "\t\t" . '<label><input type="checkbox" name="' . $value['id'] . '"></label>' . "\n";
	//	}
		echo "\n\t</td>\n";




		# the favicon
		echo "\t" . '<td class="">' . "\n";
		echo "\t\t";
		if ($show_icon){
			if ($value['favicon'] && is_file ($value['favicon'])) {
				echo '<img src="' . $value['favicon'] . '" width="16" height="16" alt="">' . "\n";
			}
			else {
				echo $bookmark_image . "\n";
			}
		}

		# the link
		if ($settings['open_new_window']) {
			$target = ' target="_blank"';
		}
		else {
			$target = null;
		}

//		if ($show_link){
			$link = '<a class="bookmark_href" href="' . $value['url'] . '" title="' . $value['url'] . '"' . $target . '>' . $value['title'] . "</a>";
//		}
//		else {
//			$link = $value['title'];
//		}
		echo "\t\t$link\n";



				# the description and if not empty
				if ($show_desc && $value['description'] != "") {
					if ($show_folder) {
						$css_extension = '';
					}
					else {
						$css_extension = "";
					}
					echo "\t" . '<p' . $css_extension . '><small>' . $value['description'] . "</small></p>\n";
				}





echo "\t</td>\n";











// SHARE STATUS
// these should be named "Warning" for Public ones, and -blank- for Private
				if ($show_share) {
					$share = $value['public'] ? 'PUBLIC!' : 'Private';
					echo "\t\t" . '<td class="' . $share . '">' . $share . "</td>\n";
				}



// DATE
				if ($show_date) {
					echo "\t\t" . '<td>';
					echo date ($date_formats[$settings['date_format']], $value['timestamp']);
					echo "\t</td>\n";
				}




// ######## ########  #### ########  ######
// ##       ##     ##  ##     ##    ##    ##
// ##       ##     ##  ##     ##    ##
// ######   ##     ##  ##     ##     ######
// ##       ##     ##  ##     ##          ##
// ##       ##     ##  ##     ##    ##    ##
// ######## ########  ####    ##     ######



echo '<td>' . "\n";
								# the edit column
								if ($show_edit) {
									echo "\t\t" . '<a href="javascript:bookmarkedit(\'' . $value['id'] . '\')">';
									echo sprintf ($edit_image, "Edit");
									echo "</a>\n";
								}

								# the move column
								if ($show_move) {
									echo "\t\t" . '<a class="bookmark-move" href="javascript:bookmarkmove(\'' . $value['id'] . '\', \'' . 'expand=' . implode (",", $expand) . '&amp;folderid=' . $folderid . '\')">';
									echo sprintf ($move_image, "Move");
									echo "</a>\n";
								}

								# the delete column
								if ($show_delete) {
									echo "\t\t" . '<a href="javascript:bookmarkdelete(\'' . $value['id'] . '\')">';
									echo sprintf ($delete_image, "Delete");
									echo "</a>\n";
								}


echo "\t</td>\n";






// end of the ForEach
echo "\t</tr>\n";



	}

		echo "\t</table>\n";

	echo "</form>\n";
}

?>
