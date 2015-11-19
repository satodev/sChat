<?php
class Tools{
	public static function preg_m($pattern, $subject) 
	{
		preg_match($pattern, $subject, $match, PREG_OFFSET_CAPTURE);
		return $match;
	}
	public static function throwWarningMessage($message)
	{
		echo '<div class="container-fluid"><p class="col-xs-1 col-sm-1 col-md-1 col-lg-1 bg-warning center-block" style="text-align:center">'.strtoupper($message).'</p></div>';
	}
	public static function throwErrorMessage($message)
	{
		echo '<div class="container-fluid"><p class="col-xs-1 col-sm-1 col-md-1 col-lg-1 bg-danger center-block" style="text-align:center">'.strtoupper($message).'</p></div>';
	}
}