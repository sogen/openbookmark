<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<?php
require_once ("./header.php");
logged_in_only ();

$search = set_get_string_var ('search');
if ($search != '') {
	$search_mode = true;
}
else {
	$search_mode = false;
}

$order = set_get_order ();

?>

	<?php if (!$search_mode): ?>



	<script>
	<!--
	var selected_folder_id = 0;

	$(document).ready(function() {
		setup collapsing menus
		$(".mnu").click(function(){
			var options = {};
			$("#"+$(this).attr("target")).toggle('blind',options,300);
		});

		setupFolderIntercepts();
		setupBookmarkIntercepts();

	});

	function setupFolderIntercepts()
	{
		$(".folders").removeClass("loading-anim");
		$(".bookmarks").removeClass("loading-anim");

		$(".flink").click(function(){
			var url = $(this).attr('href');
			var folderurl=url.replace('index.php','async_folders.php');
			var bookmarkurl=url.replace('index.php','async_bookmarks.php');

			selected_folder_id = $(this).attr("folderid");

			$(".folders").addClass("loading-anim");
			$(".bookmarks").addClass("loading-anim");

			$(".folders").load(folderurl, setupFolderIntercepts);
			$(".bookmarks").load(bookmarkurl, setupBookmarkIntercepts);

			return false;
		});
	}

	function setupBookmarkIntercepts()
	{
		$(".bookmarks").removeClass("loading-anim");

		$(".blink").click(function(){
			var url = $(this).attr('href');
			var bookmarkurl=url.replace('index.php','async_bookmarks.php');

			$(".bookmarks").addClass("loading-anim");
			$(".bookmarks").load(bookmarkurl, setupBookmarkIntercepts);

			return false;
		});
	}
	-->
	</script>
	<?php endif; ?>


	<?php // if (is_mobile_browser() && !$search_mode): ?>
	<?php // endif; ?>




<!-- Menu -->
		<?php
		require_once ("./menu.php");
		logged_in_only ();
		?>




<!-- Wrapper starts here. -->
<div>
	<!-- Main content starts here. -->
	<div>

			<?php if ($search_mode): ?>

			<div>

				<div>
					<a href="./index.php"><i class="fa fa-folder-open"></i>My Bookmarks </a>
				</div>

					<?php

	          require_once ('./lib/BooleanSearch.php');

	          $searchfields = array ('url', 'title', 'description');

			  if ($search=='[dupe_check_bookmarks]')
				$query = "SELECT a.title,a.url,a.description,UNIX_TIMESTAMP(a.date) as timestamp,a.childof,a.id,a.favicon,a.public,
							f.name,f.id as fid, f.public as fpublic from bookmark a
							inner join bookmark b on a.url=b.url and a.id<>b.id
							LEFT JOIN folder f ON a.childof=f.id
							order by a.url";
			  else
				$query = assemble_query ($search, $searchfields);

	          if ($mysql->query ($query)) {
	                  $bookmarks = array ();
	                  while ($row = mysql_fetch_assoc ($mysql->result)) {
	                          array_push ($bookmarks, $row);
	                  }
	                  if (count ($bookmarks) > 0) {
	                          require_once (ABSOLUTE_PATH . "bookmarks.php");
	                          list_bookmarks ($bookmarks,
	                                  true,
	                                  true,
	                                  $settings['show_bookmark_icon'],
	                                  true,
	                                  $settings['show_bookmark_description'],
	                                  $settings['show_column_date'],
	                                  $settings['show_column_edit'],
	                                  $settings['show_column_move'],
	                                  $settings['show_column_delete'],
	                                  $settings['show_public'],
	                                  false);
	                  }
	                  else {
	                          echo '<div> No Bookmarks found matching <b>' . $search . '</b>.</div>';
	                  }
	          }
	          else {
	                  message ($mysql->error);
	          }

					?>

			</div>


<?php else: ?>



<!--
########  #######  ##       ########  ######## ########   ######
##       ##     ## ##       ##     ## ##       ##     ## ##    ##
##       ##     ## ##       ##     ## ##       ##     ## ##
######   ##     ## ##       ##     ## ######   ########   ######
##       ##     ## ##       ##     ## ##       ##   ##         ##
##       ##     ## ##       ##     ## ##       ##    ##  ##    ##
##        #######  ######## ########  ######## ##     ##  ######

-->


	<div class="row">
		<div class="col-md-3">


						<!-- Folders starts here. -->
						<h2 target="folders">Folders</h2>


						<?php
						require_once (ABSOLUTE_PATH . "folders.php");
						$tree = new folder;
						$tree->make_tree (0);
						$tree->print_tree ();
						?>

						<!-- Folders ends here. -->



					</div><!-- figlet: Banner3

########   #######   #######  ##    ## ##     ##    ###    ########  ##    ##  ######
##     ## ##     ## ##     ## ##   ##  ###   ###   ## ##   ##     ## ##   ##  ##    ##
##     ## ##     ## ##     ## ##  ##   #### ####  ##   ##  ##     ## ##  ##   ##
########  ##     ## ##     ## #####    ## ### ## ##     ## ########  #####     ######
##     ## ##     ## ##     ## ##  ##   ##     ## ######### ##   ##   ##  ##         ##
##     ## ##     ## ##     ## ##   ##  ##     ## ##     ## ##    ##  ##   ##  ##    ##
########   #######   #######  ##    ## ##     ## ##     ## ##     ## ##    ##  ######

					--><div class="col-md-9">

							<!-- Bookmarks starts here. -->
							<?php

							require_once (ABSOLUTE_PATH . "bookmarks.php");
							$query = sprintf ("SELECT title, url, description, UNIX_TIMESTAMP(date) AS timestamp, id, favicon, public
								FROM bookmark
								WHERE user='%s'
								AND childof='%d'
								AND deleted!='1'
								ORDER BY $order[1]",
								$mysql->escape ($username),
								$mysql->escape ($folderid));

							if ($mysql->query ($query)) {
								$bookmarks = array ();
								while ($row = mysql_fetch_assoc ($mysql->result)) {
									array_push ($bookmarks, $row);
								}
								list_bookmarks ($bookmarks,
									true,
									false,
									$settings['show_bookmark_icon'],
									true,
									$settings['show_bookmark_description'],
									$settings['show_column_date'],
									$settings['show_column_edit'],
									$settings['show_column_move'],
									$settings['show_column_delete'],
									$settings['show_public'],
									true);
							}
							else {
								message ($mysql->error);
							}
							?>

							<!-- Bookmarks ends here. -->


									<?php endif; ?>


					</div>
				</div>


	<!-- Main content ends here. -->
	</div>
<!-- Wrapper ends here. -->
</div>

<?php
// print_footer ();
// require_once (ABSOLUTE_PATH . "footer.php");
?>
