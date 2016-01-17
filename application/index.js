$("#newdir").click(function(){
	var newdir = $("input[name=newdir]").val();
	$.post("",{newdir: newdir},function(msg){
		if(msg==1) $("label").show();
		if(msg==2) location.reload();
	});
});

$("input[name=newdir]").focus(function(){
	$("label").hide();
});

function delDir(id){
	$.post("",{del_id: id},function(msg){
		if(msg==1) location.reload();
	});
}

function sub(){
	var obj = new XMLHttpRequest();
	obj.upload.onprogress = function(evt){
		var per = Math.floor(evt.loaded/evt.total*100)+"%";
		$('#progress').append(per);
		if(per=='100%') location.reload();
	}
	var fm = document.getElementById('file').files[0];
	var fd = new FormData();
	fd.append('file',fm);
	obj.open('post','');
	obj.send(fd);
}