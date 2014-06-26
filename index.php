<html>
<head>
<title>Aerial ROI demo</title>
<meta http-equiv=Content-Type content=text/html; charset=utf-8>
<meta name="keywords" content="">
<META NAME="generator" CONTENT="">
<meta name="author" content="SovGVD 2009">
</head>


<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0>
<table border=0 cellpadding=0 cellspacing=0 width=100% height=100%><tr><td align=center valign=middle>
<table border=0 cellpadding=0 cellspacing=0 >
<tr>
	<td height=480px width=640px id=pic_show nowrap align=right valign=bottom style="color:white; font-size:5px;">&nbsp;</td>
</tr>
</table>
use mouse for rotate
</td></tr></table>

<script type="text/javascript">
	var pic_name="pics/<?php print basename($_GET['p'])?>";
		var tmp_pic_name=pic_name.split("_");
	var pic_show_td=document.getElementById("pic_show");
	var pic_count=tmp_pic_name[1];		// кол-во спрайтов в картинке
		var pic_cur_count=0;
		var pic_step=360/pic_count;
	var pic_x=tmp_pic_name[2];
	var pic_y=tmp_pic_name[3];
	//var pic_show_delay=10;		// в милисекудах
	//var pic_show_delay_timer;

	var IE = document.all?true:false
	if (!IE) document.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE | Event.MOUSEUP)
	document.onmousedown = MouseDown;
	document.onmousemove = MouseMove;
	document.onmouseup = MouseUp;


	var MouseButtion='up';
	var mouse_ob;
	var mouseX_s=0, mouseY_s=0,mouseX=0, mouseY=0;
	var pre_delta_ang=0, delta_ang=0;
	function MouseDown (e) {
		if (IE) {
			mouseX_s = event.clientX + document.body.scrollLeft;
			mouseY_s = event.clientY + document.body.scrollTop;
		} else {
			mouseX_s=e.pageX;
			mouseY_s=e.pageY;
		}
		mouse_ob=IE?window.event.srcElement.id:e.target.id;
		MouseButtion="up";
			MouseButtion='down';
			pre_delta_ang=delta_ang;
	}
	
	function MouseMove (e) {
		if (IE) {
			mouseX = event.clientX + document.body.scrollLeft;
			mouseY = event.clientY + document.body.scrollTop;
		} else {
			mouseX=e.pageX;
			mouseY=e.pageY;
		}
		if (MouseButtion=='down') {
			delta_ang=pre_delta_ang-(mouseX-mouseX_s)/1.5;

			//pic_show.innerHTML=pic_cur_count;
		}
	}
	function MouseUp () {
		if (MouseButtion=='down') {
			mouse_ob=null;
			MouseButtion='up';
			mouseX_s=0;
			mouseY_s=0;
			mouseX=0;
			mouseY=0;
		}
		mouse_ob=null;
		MouseButtion='up';
		mouseX_s=0;
		mouseY_s=0;
		mouseX=0;
		mouseY=0;
	}


function pic_rotate () {
	tmp_ang=parseInt(delta_ang);

	while (tmp_ang>359) {
		tmp_ang=tmp_ang-360;
	}
	while (tmp_ang<0) {
		tmp_ang=tmp_ang+360;
	}

	pic_cur_count=parseInt(tmp_ang/pic_step);

	pic_show.style.backgroundPosition=-(pic_cur_count*pic_x)+"px 0px";

	//pic_show.innerHTML="ang="+tmp_ang+" pic_id="+pic_cur_count;

	setTimeout("pic_rotate ()",100);
}


/*
function pic_show_go () {
	if (pic_show_delay_timer) {
		clearTimeout(pic_show_delay_timer);
	}

	pic_show.style.backgroundPosition=-(pic_cur_count*pic_x)+"px 0px";
	pic_show.innerHTML=pic_cur_count;

	pic_cur_count++;
	if (pic_cur_count>=pic_count) pic_cur_count=0;
	pic_show_delay_timer=setTimeout("pic_show_go()",pic_show_delay);
}
*/

function pic_show_start () {
	pic_show.style.backgroundImage="url("+pic_name+")";
	pic_show.style.backgroundRepeat="no-repeat";
	pic_show.style.backgroundPosition="0px 0px";
	pic_rotate ();
}


pic_show_start ();
//pic_rotate ();
</script>
</body>
</html>
