<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>网站安装程序</title>
<link type="text/css" rel="stylesheet" href="../Public/Home/Css/install.css" />
</head>
<body>
<?php 
if( empty($_POST['sub']) ){
	die('请按顺序来');
}
?>
<div id="header">
	<span class="logo"></span>
	<h1>AndyCMS 安装</h1>
</div>
<div id="content_box">
	<div id="content_pad">
		<div id="stepbar">
			<h2>安装步骤</h2>
			<div class="step ">1 : 安装前检查</div>
			<div class="step" ><span style="color: #F48B06;">2 : 许可协议</span></div>
			<div class="step">3 : 数据库设定</div>
			<div class="step">4 : 网站设置</div>
			<div class="step">5 : 完成</div>	
			<div class="box"></div>				
		</div>
	
		<div id="rightpad">
			<div id="step">
				<h2>授权许可</h2>
				<div class="wrap_a">
					<form action="database.php" method="post">
						<input type="submit" value="下一步" name="sub" class="wrap_sub"/>
					</form>
				</div>
			</div>
			
			
			<div id="right_content">
				<div class="content_2">
					<pre id="best-answer-content" class="reply-text mb10">一、软件使用协议
  本协议是Andy关于“AndyCMS”软件产品（以下简称“本软件产品”）的法律协议。一旦安装、复制或以其他方式使用本软件产品，即表示同意接受协议各项条件的约束。如果用户
不同意协议的条件，请不要使用本软件产品。</pre><pre id="best-answer-content" class="reply-text mb10"><br></pre><pre id="best-answer-content" class="reply-text mb10">二、软件产品保护条款
  1）本软件产品之著作权及其它知识产权等相关权利或利益（包括但不限于现已取得或未来可取得之著作权、专利权、商标权、
营业秘密等）皆为Andy所有。本软件产品受中华人民共和国版权法及国际版权条约和其他知识产权法及条约的保护
。用户仅获得本软件产品的非排他性使用权。</pre><pre id="best-answer-content" class="reply-text mb10"><br></pre><pre id="best-answer-content" class="reply-text mb10"> 2）用户不得：删除本软件及其他副本上一切关于版权的信息；对本软件进行反向工程，如反汇编、反编译等；&nbsp;</pre><pre id="best-answer-content" class="reply-text mb10"><br></pre><pre id="best-answer-content" class="reply-text mb10"> 3）本软件产品以现状方式提供，Andy不保证本软件产品能够或不能够完全满足用户需求，在用户手册、帮助文件、使用说明书等软件文档中的介绍性内容仅供用户参考，不得理解为对用户所做的任何承诺。</pre><pre id="best-answer-content" class="reply-text mb10">Andy保留对软件版本进行升级，对功能、内容、结构、界面、运行方式等进行修改或自动更新的权利。
&nbsp;</pre><pre id="best-answer-content" class="reply-text mb10"> 4）为了更好地服务于用户，或为了向用户提供具有个性的信息内容的需要，本软件产品可能会收集、传播某些信息，</pre><pre id="best-answer-content" class="reply-text mb10">但Andy承诺不向未经授权的第三方提供此类信息，以保护用户隐私。
&nbsp;</pre><pre id="best-answer-content" class="reply-text mb10"> 5）使用本软件产品由用户自己承担风险，在适用法律允许的最大范围内，Andy在任何情况下不就因使用或不
能使用本软件产品所发生的特殊的、意外的、非直接或间接的损失承担赔偿责任。即使已事先被告知该损害发生的可能性。 
&nbsp;</pre><pre id="best-answer-content" class="reply-text mb10"> 6）Andy定义的信息内容包括：文字、软件、声音；本公司为用户提供的商业信息，所有这些内容受版权、商标权、和其它知识产权和所有权法律的保护。</pre><pre id="best-answer-content" class="reply-text mb10">所以，用户只能在本公司授权下才能使用这些内容，而不能擅自复制、修改、编撰这些内容、或创造与内容有关的衍生产品。</pre><pre id="best-answer-content" class="reply-text mb10"><br></pre><pre id="best-answer-content" class="reply-text mb10"> 7）如果您未遵守本协议的任何一项条款，Andy有权立即终止本协议，并保留通过法律手段追究责任。</pre><pre id="best-answer-content" class="reply-text mb10">

三、Andy具有对以上各项条款内容的最终解释权和修改权。如用户对Andy的解释或修改有异议，应当立即停止使用本软件产品。用户继续使用本软件产品的行为将被视为对Andy的解释或修改的接受。</pre><pre id="best-answer-content" class="reply-text mb10">

四、因本协议所发生的纠纷，双方同意按照中华人民共和国法律，由AndyCMS所在地的有管辖权的法院管辖。


                  <span class="Apple-tab-span" style="white-space:pre">					</span><h3> Andy</h3></pre>
				</div>
			</div>
		</div>
		
	</div>
</div>
</body>
</html>