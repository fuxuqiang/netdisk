var d = new Date();
var thisYear = d.getFullYear();
var thisMonth = d.getMonth()+1;
var thisDay = d.getDate();

$(function(){
	for(var i=100;i>=0;i--){
		var y = thisYear-i;
		if(i==20) $("#year").append("<option selected>"+y+"</option>");
		else $("#year").append("<option>"+y+"</option>");
	}
	for(var m=1;m<=12;m++){
		$("#month").append("<option>"+m+"</option>");
	}
	for(var d=1;d<=31;d++){
		$("#day").append("<option>"+d+"</option>");
	}
});

function setDate(){
	var year = $("#year").val();
	var month = parseInt($("#month").val());
	var months = $("#month").children().length;
	var days = $("#day").children().length;

	if(year==thisYear){
		$("#month option:gt("+(thisMonth-1)+")").remove();
	}
	if(months<12){
		for(var i=months+1;i<=12;i++){
			$("#month").append("<option>"+i+"</option>");
		}
	}

	if(year==thisYear && month>=thisMonth){
		newDays = thisDay;
	}else if($.inArray(month,[4,6,9,11])!=-1){
		newDays = 30;
	}else if(month==2){
		if(year%4==0 && year%100!=0 || year%400==0) newDays=29;
		else newDays=28;
	}else newDays=31;
	if(newDays<days){
		$("#day option:gt("+(newDays-1)+")").remove();
	}else{
		for(var i=days+1;i<=newDays;i++){
			$("#day").append("<option>"+i+"</option>");
		}
	}
}

var password = /^\w{6,16}$/;
var email = /^\w+@\w+(\.\w{2,3}){1,2}$/;

$("input[name=name]").change(function(){
	$.post("?a=checkName", {name: $(this).val()}, function(msg){
		if(msg==1){
			$("#namepass").show();
			$("#namepass").next().hide();
		}else{
			$("#namepass").hide();
			$("#namepass").next().show();
		}
	});
});

$("input[name=pwd]").change(function(){
	if($(this).val()){
		if(password.test($(this).val())){
			$("#pwdpass").show();
			$("#pwdpass").next().hide();
		}else{
			$("#pwdpass").hide();
			$("#pwdpass").next().show();
		}
	}else{
		$("#pwdpass").hide();
		$("#pwdpass").next().hide();
	}	
});

$("input[name=pwd],#pwd1").blur(function(){
	if($("input[name=pwd]").val() && $("#pwd1").val()){
		if($("#pwd1").val()==$("input[name=pwd]").val()){
			$("#pwd1pass").show();
			$("#pwd1pass").next().hide();
		}else if($("#pwd1").val()){
			$("#pwd1pass").hide();
			$("#pwd1pass").next().show();
		}
	}else{
		$("#pwd1pass").hide();
		$("#pwd1pass").next().hide();
	}
});

$("input[name=email]").change(function(){
	if(email.test($(this).val())){
		$("#emailpass").show();
		$("#emailpass").next().hide();
	}else{
		$("#emailpass").hide();
		$("#emailpass").next().show();
	}
});

$("#captcha").change(function(){
	$.post("?a=verify", {captcha: $(this).val()}, function(msg){
		if(msg==1){
			$("#captchapass").show();
			$("#captchapass").next().hide();
		}else{
			$("#captchapass").next().show();
		}
	});
});
$("#captcha").on("input",function(){
	if($("#captcha").val().length==4){
		$.post("?a=verify", {captcha: $(this).val()}, function(msg){
			if(msg==1){
				$("#captchapass").show();
				$("#captchapass").next().hide();
				$("#captcha").attr("disabled","disabled");
				$("img").removeAttr("onclick");
			}else{
				$("#captchapass").next().show();
			}
		});
	}
});

$("form").submit(function(){
	if($("#namepass").next().attr("style")=="display: inline;" || $("#pwdpass").next().attr("style")=="display: inline;" || $("#pwd1pass").next().attr("style")=="display: inline;" || $("#emailpass").next().attr("style")=="display: inline;" || $("#captchapass").next().attr("style")=="display: inline;") return false;
	$("input:hidden").val($("#year").val()+','+$("#month").val()+','+$("#day").val());
});