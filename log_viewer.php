<?php
if(isset($_GET['mode']) && $_GET['mode'] == 'update'){
	$logs = array(
		'log_name1' => 'log_path1',
		'log_name2' => 'log_path2',
	);
	$lines = 50;

	$return = array();
	foreach($logs as $alias => $path){
		$return[$alias] = array();
		if(file_exists($path)){
			$tmp_cont = file_get_contents($path);
			$cont = explode("\n", $tmp_cont);
			$result = '';
			$tmp_start = count($cont) - $lines;
			$start = $start<0 ? 0 : $tmp_start;
			for($i=$start;$i<count($cont);$i++){
				$result .= $cont[$i].'<br>';
			}
			$return[$alias] = $result;
		}
	}
	echo json_encode($return);
}else{
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>log-wo-mitai-na...</title>
<style>
div{
	height: 100%;
    display: inline-block;
	color: #eeeeee;
}
#log_name{
	width:11%;
	background: #555555;
}
#log_body{
	width:85%;
	background: #333333;
	padding: 10px;
}
</style>
<script type="text/javascript">
	var req = new XMLHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.status == 200) {
				var data = eval('(' + req.responseText + ')');
				updateScreen(data);
			}
		}
	}

	function getNewerLog(){
		req.open('GET', 'index.php?mode=update', true);
		req.send(null);
	}

	getNewerLog();
//	setTimeout(getNewerLog, 20*1000);

	var aliases = [];
	function updateScreen(logs){
		var aliasHtml = '';
		var bodyHtml = '';
		var first = '';
		Object.keys(logs).forEach(function (alias){
			if(first === ''){
				first = alias;
			}
			aliases.push(alias);
			aliasHtml += '<button onclick="dispAlias(\''+alias+'\')">'+alias+'</button><br>';
			bodyHtml += '<div id="'+alias+'">'+logs[alias]+'</div>';
		});
		document.getElementById('log_name').innerHTML = aliasHtml;
		document.getElementById('log_body').innerHTML = bodyHtml;
		dispAlias(first);
	}
	function dispAlias(target_alias){
		for (const alias of aliases) {
			document.getElementById(alias).style = 'display:none';
		}
		document.getElementById(target_alias).style = 'display:inline-block';
	}
</script>
	</head>
	<body>
		<div id="log_name">&nbsp;</div>
		<div id="log_body">&nbsp;</div>
		<!-- <div id="stop"><button onclick="alert('stopping')">stop</button></div> -->
	</body>
</html>
<?php
}
