//$(".page").css("display","none");
$("#page1").css("display","block");

$("a[data-role=displaypage]").click(function () 
	{
	    var anchor = $(this);
      alert(anchor.attr("href"));
	    displaypage=anchor.attr("href");
        $(".page").css("display","none");
        alert(displaypage);
        $("#" + displaypage).css("display","block");
	});

$("a[data-role=lien]").click(function () 
	{
	    var anchor = $(this);
	    lien=anchor.attr("href");
      open(lien,"_self");
	});
