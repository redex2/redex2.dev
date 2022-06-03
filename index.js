function loadXMLDoc(url)
{
	xmlhttp=null;
	if (window.XMLHttpRequest)
	{// code for Firefox, Opera, IE7, etc.
		xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (xmlhttp!=null)
	{
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		xmlhttp.onreadystatechange=state_Change;
	}
	else
	{
		location.replace(url);
	}
}	
	
function state_Change()
{
	if (xmlhttp.readyState==4)
	{// 4 = "loaded"
		if (xmlhttp.status==200)
		{// 200 = "OK"
			document.getElementById('search-content').innerHTML=xmlhttp.responseText;
		}
	}
}

function content_load(data)
{
	if(data.target.value!="")
	{
		document.getElementById("content").style.display="none";
		document.getElementById("search-result").style.display="block";
		window.history.pushState(" ", "src", "?search="+data.target.value);
		loadXMLDoc("https://redex2.dev/search_engine.php"+window.location.search);
	}
	else
	{
		document.getElementById("content").style.display="block";
		document.getElementById("search-result").style.display="none";
		window.history.pushState(" ", "src", "?");
	}
}
let input = document.getElementById("search");
input.addEventListener("input", content_load);

