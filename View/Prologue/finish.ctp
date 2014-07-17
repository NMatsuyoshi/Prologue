<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>アイリア ailia</title>
<?php
	echo $this->Html->css('prologue');
	echo $this->Html->css('osu');

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
</head>

<body id="osu">
	<center>
	<table class="mainlist" cellspacing="0" border="0" >
		<tr><td class="top"><p style="text-align:center">☆カードGET☆</td></tr>	
		<tr>
			<td class="result" style="text-align:center;"></td ></tr>
		<tr><td class="under"><p style=" text-align:center; font-size:24px; font-weight:bold"><a href="/prologue/lot">&nbsp;&nbsp;もう1回チャレンジ&nbsp;&nbsp;</a></p></td></tr>
	</table>
	</center>

</body>
</html>
