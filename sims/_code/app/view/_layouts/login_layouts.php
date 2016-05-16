<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>管理系统</title>
	<style type="text/css">
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
		background-color: #1D3647;
	}
	</style>
	<link href="../css/style.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
	<!--[if IE 6]>
		<script type="text/javascript" src="<?=$_BASE_DIR?>js/DD_belatedPNG.js"></script>
	<![endif]-->
	<script>
		if(navigator.userAgent.indexOf('MSIE 6')>-1){DD_belatedPNG.fix('.OrgStatus');}
        function resizewnd() {
            var b = document.documentElement;
    		$('#login_main_table').height(b.clientHeight);
		}
		
		$(window).resize(resizewnd);
		window.onload=function(){
			resizewnd();
		}
	</script>
</head>

<body>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="login_main_table">
		<tr>
			<td height="42" valign="top">
				<table width="100%" height="42"	border="0px solid #ff0000" cellpadding="0" cellspacing="0" class="login_top_bg">
					<tr>
						<td width="1%" height="21">&nbsp;</td>
						<td height="126">&nbsp;</td>
						<td width="17%">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0px solid #ff0000" cellpadding="0" cellspacing="0" class="login_bg">
					<tr>
						<td width="49%" align="right">
							<table width="91%" border="0px solid #ff0000" cellpadding="0" cellspacing="0" class="login_bg2">
								<tr>
									<td valign="top">
										<table width="89%" border="0px solid #ff0000" cellpadding="0" cellspacing="0">
											<tr><td height="69">&nbsp;</td></tr>
											<tr>
												<td height="80" align="right" valign="top" style="padding-right:50px;">
													<img class="OrgStatus" src="../img/logo.png" width="279" height="68">
												</td>
											</tr>
											<tr>
												<td height="198" align="right" valign="top" style="padding-right:50px;">
													<table width="100%" border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td width="55%">&nbsp;</td>
															<td height="25" colspan="2" class="left_txt">
																&nbsp;															</td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td height="25" colspan="2" class="left_txt">
																&nbsp;
															</td>
														</tr>
														<tr>
															<td height="50" colspan="3" style="text-align:right;padding-right:23px">
                                                                <img src="../img/icon-demo1.png" width="16" height="16"/>
																<a href="<?=url('index')?>" target="_blank" class="left_txt3"> 返回首页</a> &nbsp;
															    <img src="../img/icon-demo.gif" width="16" height="16"/>
																<a href="#" target="_blank" class="left_txt3"> 使用说明</a> &nbsp;
															    <img src="../img/icon-login-seaver.gif" width="16" height="16"/>
																<a href="#" class="left_txt3"> 在线客服</a>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
						<td width="1%">&nbsp;</td>
						<td width="50%" valign="bottom"><table width="100%" height="59" border="0px solid #ff0000" align="center" cellpadding="0" cellspacing="0">
								<tr><td colspan=2 style="height:100px;">&nbsp;</td></tr>
								<tr>
									<td width="4%">&nbsp;</td>
									<td width="96%" height="38"><span class="login_txt_bt">&nbsp;</span></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td height="21">

										<table cellSpacing="0" cellPadding="0" width="100%" border="0px solid #156325" id="table211" height="200">

										
							<tr>
												<td colspan="2" align="middle">
													<?php $this->_block('contents'); ?><?php $this->_endblock(); ?>
												</td>
											</tr>
											<tr>
												<td width="433" height="140" align="right" valign="bottom">
													<!--<img src="../img/login-wel.gif" width="242" height="138">-->
												</td>
												<td width="57" align="right" valign="bottom">&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="140">
				<table width="100%" border="0px solid #156325" cellspacing="0" cellpadding="0" class="login-buttom-bg" >
					<tr>
						<td align="center" height="140"><span class="login-buttom-txt">版权所有:浙江吉博教育科技有限公司&nbsp;&nbsp;&nbsp;&nbsp;
                增值电信业务经营许可证编号:<a href="http://www.zjnep.com/icp.jpg" target="_blank" style="color:#fff;">浙B2-20050301</a></span></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>
