/**
 * 左侧滑动特效
 */
$(document).ready(function(){
	$(".left_a").click(function(){
		$(".xz").hide();
		$(this).parent().find(".xz").show();
	});
});

