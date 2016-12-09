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



	<!-- Menu starts here. -->
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">Brand</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				<ul class="nav navbar-nav">
					<!-- <li class="active"><a href="index.php">Link <span class="sr-only">(current)</span></a></li> -->
					<li><a href="index.php">Home</a></li>
					<li><a href="javascript:bookmarknew('<?php echo $folderid; ?>')">New Bookmark</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bookmarks <span class="caret"></span></a>
						<ul class="dropdown-menu">
									<?php if ($search_mode) { ?>
									<li><a href="./index.php"><?php echo $settings['root_folder_name']; ?></a></li>
									<?php } ?>
								  <li><a href="javascript:bookmarkedit(checkselected())">Edit Bookmarks</a></li>
								  <li><a href="javascript:bookmarkmove(checkselected())">Move Bookmarks</a></li>
								  <li><a href="javascript:bookmarkdelete(checkselected())">Delete Bookmarks</a></li>
								  <li><a href="./shared.php">Shared Bookmarks</a></li>

						</ul>
					</li>
				</ul>



	      <ul class="nav navbar-nav">
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Folders <span class="caret"></span></a>
	          <ul class="dropdown-menu">
							<li><a href="javascript:foldernew('<?php echo $folderid; ?>')">New Folder</a></li>
							<li><a href="javascript:folderedit('<?php echo $folderid; ?>')">Edit Folder</a></li>
							<li><a href="javascript:foldermove('<?php echo $folderid; ?>')">Move Folder</a></li>
							<li><a href="javascript:folderdelete('<?php echo $folderid; ?>')">Delete Folder</a></li>
							<li><a href="./index.php?expand=&amp;folderid=0">Collapse All</a></li>
							<li role="separator" class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">One more separated link</a></li>
	          </ul>
	        </li>
	      </ul>
				<!-- SEARCH FORM -->
				<form class="navbar-form navbar-left" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="GET">
					<div class="form-group">
						<input type="text" class="form-control"  placeholder="Search" name="search" value="<?php echo $search; ?>"/>
	        </div>
					<input type="submit" class="btn btn-default" value="Go" name="submit"/>
				</form>

	      <!-- <form class="navbar-form navbar-left">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Search">
	        </div>
	        <button type="submit" class="btn btn-default">Submit</button>
	      </form> -->
	      <ul class="nav navbar-nav navbar-right">
	        <!-- <li><a href="#">Link</a></li> -->
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tools <span class="caret"></span></a>
	          <ul class="dropdown-menu">
							<?php if (admin_only ()) { ?>
							<li><a href="./admin.php">Admin</a></li>
							<?php } ?>
							<li><a href="./import.php">Import</a></li>
							<li><a href="./export.php">Export</a></li>
							<li><a href="./index.php?search=[dupe_check_bookmarks]">Find Duplicates</a></li>
							<li><a href="./sidebar.php">View as Sidebar</a></li>
							<li><a href="./settings.php"><?php echo $username; ?>&#039;s Settings</a></li>

	          </ul>
	        </li>
					<li><a href="./index.php?logout=1">Logout</a></li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>





<!-- Wrapper starts here. -->
<div>
	<!-- Main content starts here. -->
	<div>

			<?php if ($search_mode): ?>

			<div>

				<div>
					<a href="./index.php"><i class="fa fa-camera-retro"></i> fa-camera-retro<img src="./images/folder_open.gif" alt=""> My Bookmarks </a>
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
				  <div class="col-md-4">


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

					--><div class="col-md-8">


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
