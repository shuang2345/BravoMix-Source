$(document).ready(function() {
    var generate_code = function(){
        var vNum = Math.random();
        vNum = Math.round(vNum*10);
        $("#auth_code").attr("src", "/vcode/getCode/"+ vNum);    
    }
    $("#auth_code").click(function(){
	   generate_code();
	})

    $("#regen_code").click(function(){
	   generate_code();
	})
});