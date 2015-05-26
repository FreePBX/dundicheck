<?php /* $Id: page.ringgroups.php 5340 2007-12-04 19:10:53Z p_lindheimer $ */
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

$dispnum = 'dundicheck'; //used for switch on config.php
$extdisplay = isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:'';
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'tool';
$dundiconflict = isset($_REQUEST['dundiconflict'])?$_REQUEST['dundiconflict']:'';

$content = '';
if ($extdisplay != "") {
	$heading = sprintf(_("DUNDi Information: %s"),$extdisplay);
	if ($dundiconflict == 'true') {
		$content .= '<div class="well well-warning">';
		$content .= sprintf(_("The number you are trying to use, %s, is currently available from one of the DUNDi routes you have configured on your system. As a result you cannot use this number on this system. Even if the route configuration does not pass this number you will still be blocked from creating it. If this is not an error, then you will have to un-publish this number on your remote DUNDi setup, disable the DUNDi trunk in question, or disable this module to avoid the checks. Otherwise, remove %s from the remote system before creating this one."),$extdisplay,$extdisplay);
		$content .= "</div>";
	}
	$list = dundicheck_lookup_all($extdisplay);
	if (empty($list)) {
		$content .= '<div class="well well-info">';
		$content .= "<b>"._("No matches found")."</b>";
		$content .= '</div>';
	} else {
		foreach ($list as $map => $line) {
			$content .= '<div class="well well-info">';
			$content .= "<h2>".sprintf(_("Results from DUNDi trunk: %s"),$map)."</h2>";
			$output = explode("\n",$line);
			unset($output[0]);
			foreach ($output as $item) {
				$content .= $item."<br />";
			}
			$content .= '</div>';
		}
	}
} else {
	$heading = _("DUNDi Lookup");
}

?>
<div class="container-fluid">
	<h1><?php echo $heading?></h1>
		<?php echo $content?>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border">
						<form name="dundicheck" action="<?php  $_SERVER['PHP_SELF'] ?>" method="post">
							<input type="hidden" name="display" value="<?php echo $dispnum?>">
							<input type="hidden" name="type" value="<?php echo $type?>">
							<!--Lookup Number-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="extdisplay"> <?php echo ($extdisplay == '')?_("Lookup Number"):_("Lookup Another Number") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="extdisplay"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="extdisplay" name="extdisplay" value="<?php echo isset($extdisplay)?htmlspecialchars($extdisplay):''?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="extdisplay-help" class="help-block fpbx-help-block"><?php echo _("Enter the number to query against configured DUNDI sources")?></span>
									</div>
								</div>
							</div>
							<!--END Lookup Number-->
							<input type="submit" class="button" value="<?php echo _("Lookup")?>">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
