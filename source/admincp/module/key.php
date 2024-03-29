<?php
if(!defined('IN_ROOT')){exit('Access denied');}
Administrator(3);
$action=SafeRequest("action","get");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo IN_CHARSET; ?>" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<title>密钥管理</title>
<link href="static/admincp/css/main.css" rel="stylesheet" type="text/css" />
<link href="static/pack/asynctips/asynctips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="static/pack/asynctips/jquery.min.js"></script>
<script type="text/javascript" src="static/pack/asynctips/asyncbox.v1.4.5.js"></script>
<script type="text/javascript" src="static/pack/layer/jquery.js"></script>
<script type="text/javascript" src="static/pack/layer/lib.js"></script>
<script type="text/javascript">
var pop = {
	up: function(scrolling, text, url, width, height, top) {
		layer.open({
			type: 2,
			maxmin: true,
			title: text,
			content: [url, scrolling],
			area: [width, height],
			offset: top,
			shade: false
		});
	}
}
function make_key(_tid){
        if($('#_num').val() == "" || $('#_num').val() == 0){
            asyncbox.tips("条数不能为空，请填写！", "wait", 1000);
            $('#_num').focus();
        }else{
            pop.up('no', '生成密钥', '?iframe=make&tid=' + _tid + '&num=' + $('#_num').val(), '500px', '400px', '40px');
        }
}
</script>
</head>
<body>
<?php
switch($action){
	case 'year':
		$state = SafeRequest("state","get");
		if(is_numeric($state)){
		        $sql = "select * from ".tname('key')." where in_tid=3 and in_state=$state order by in_id desc";
		}else{
		        $sql = "select * from ".tname('key')." where in_tid=3 order by in_id desc";
		}
		main($sql,20);
		break;
	case 'quarter':
		$state = SafeRequest("state","get");
		if(is_numeric($state)){
		        $sql = "select * from ".tname('key')." where in_tid=2 and in_state=$state order by in_id desc";
		}else{
		        $sql = "select * from ".tname('key')." where in_tid=2 order by in_id desc";
		}
		main($sql,20);
		break;
	default:
		$state = SafeRequest("state","get");
		if(is_numeric($state)){
		        $sql = "select * from ".tname('key')." where in_tid=1 and in_state=$state order by in_id desc";
		}else{
		        $sql = "select * from ".tname('key')." where in_tid=1 order by in_id desc";
		}
		main($sql,20);
		break;
	}
?>
</body>
</html>
<?php
function main($sql,$size){
	global $db,$action;
	$Arr=getpagerow($sql,$size);
	$result=$Arr[1];
	$count=$Arr[2];
?>
<div class="container">
<script type="text/javascript">parent.document.title = 'EarCMS Board 管理中心 - 应用 - 密钥管理';if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='应用&nbsp;&raquo;&nbsp;密钥管理';</script>
<div class="floattop"><div class="itemtitle"><ul class="tab1">
<?php if(empty($action)){echo "<li class=\"current\">";}else{echo "<li>";} ?><a href="?iframe=key"><span>包月密钥</span></a></li>
<?php if($action == 'quarter'){echo "<li class=\"current\">";}else{echo "<li>";} ?><a href="?iframe=key&action=quarter"><span>包季密钥</span></a></li>
<?php if($action == 'year'){echo "<li class=\"current\">";}else{echo "<li>";} ?><a href="?iframe=key&action=year"><span>包年密钥</span></a></li>
</ul></div></div><div class="floattopempty"></div>
<table class="tb tb2">
<tr><td>
<select onchange="location.href=this.options[this.selectedIndex].value;">
<?php if($action == 'year'){ ?>
<option value="?iframe=key&action=year">不限状态</option>
<option value="?iframe=key&action=year&state=0"<?php if(isset($_GET['state']) && $_GET['state'] == 0){echo " selected";} ?>>未使用</option>
<option value="?iframe=key&action=year&state=1"<?php if(isset($_GET['state']) && $_GET['state'] == 1){echo " selected";} ?>>已使用</option>
<?php }elseif($action == 'quarter'){ ?>
<option value="?iframe=key&action=quarter">不限状态</option>
<option value="?iframe=key&action=quarter&state=0"<?php if(isset($_GET['state']) && $_GET['state'] == 0){echo " selected";} ?>>未使用</option>
<option value="?iframe=key&action=quarter&state=1"<?php if(isset($_GET['state']) && $_GET['state'] == 1){echo " selected";} ?>>已使用</option>
<?php }else{ ?>
<option value="?iframe=key">不限状态</option>
<option value="?iframe=key&state=0"<?php if(isset($_GET['state']) && $_GET['state'] == 0){echo " selected";} ?>>未使用</option>
<option value="?iframe=key&state=1"<?php if(isset($_GET['state']) && $_GET['state'] == 1){echo " selected";} ?>>已使用</option>
<?php } ?>
</select>
条数：<input class="txt" type="text" id="_num" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
<input type="button" value="批量生成" class="btn" onclick="make_key(<?php echo $action ? $action == 'year' ? 3 : 2 : 1; ?>)">
</td></tr>
</table>
<table class="tb tb2">
<tr class="header">
<th>编号</th>
<th>密钥代码</th>
<th>密钥类型</th>
<th>密钥状态</th>
<th>生成时间</th>
</tr>
<?php if($count == 0){ ?>
<tr><td colspan="2" class="td27">没有密钥</td></tr>
<?php
}else{
while($row = $db->fetch_array($result)){
?>
<tr class="hover">
<td><?php echo $row['in_id']; ?></td>
<td><?php echo $row['in_code']; ?></td>
<td><?php echo $row['in_tid'] > 1 ? $row['in_tid'] > 2 ? '包年密钥' : '包季密钥' : '包月密钥'; ?></td>
<td><?php echo $row['in_state'] ? '<em class="lightnum">已使用</em>' : '未使用'; ?></td>
<td><?php if(date('Y-m-d', $row['in_time']) == date('Y-m-d')){echo '<em class="lightnum">'.date('Y-m-d H:i:s', $row['in_time']).'</em>';}else{echo date('Y-m-d', $row['in_time']);} ?></td>
</tr>
<?php
}
}
?>
</table>
<table class="tb tb2">
<?php echo $Arr[0]; ?>
</table>
</div>
<?php } ?>