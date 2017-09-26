var 
apiUrl = '//www.bassemrabia.com/dd68bf97c522179d38337a47626e8e47/src/eCrawler.php',
pagination = function(){
	jQuery('table tbody tr').each(function(k,v){
		var page = (k<10)?0:k.toString()[0];
		jQuery(this).addClass('pg'+page);
	});

	jQuery('.pagination').remove();
	jQuery('table').after('<div class="pagination"></div>');
	for(var i=0;i<parseInt(jQuery('table tbody tr:last').attr('class').split(' ')[1].replace(/[^0-9]/g,''));i++){
		jQuery('.pagination').append('<div>'+(i+1)+'</div>');
	}
	jQuery('.pagination div:first').addClass('active');
	jQuery('table tbody tr:not(.pg'+(parseInt(jQuery('.pagination div.active').text()) - 1)+'').hide();

	jQuery('.pagination div').click(function(){
		jQuery('.pagination div').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('table tbody tr:not(.pg'+(parseInt(jQuery('.pagination div.active').text()) - 1)+'').hide();
		jQuery('table tbody tr.pg'+(parseInt(jQuery('.pagination div.active').text()) - 1)).show();
	});
},
getHostName = function(url){
	var hostname;
	if(url.indexOf("://") > -1){
		hostname = url.split('/')[2];
	}else{
		hostname = url.split('/')[0];
	}
	hostname = hostname.split(':')[0];
	hostname = hostname.split('?')[0];
	return hostname.replace('www.','');
},
uniqueUrls = function(pageURL, arr){
	var a = [];
	jQuery.each(arr, function(k,v){
		if((jQuery.inArray(v, a) == -1) && v !== pageURL){a.push(v);}
	});
	return a.slice(0, 50);
},
uniqueEmails = function(arr){
	var a = [];
	jQuery.each(arr, function(k,v){
		if((jQuery.inArray(v, a) == -1)){a.push(v);}
	});
	return a.slice(0, 50);
},
validateEmail = function(email){
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/; 
	return regex.test(email);
}
crawler = function(pageURL, r){
	var arr =
	r.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi),
	dt = new Date().getTime();	
	jQuery('table tbody').append('<tr class="starred'+dt+'"><td>'+pageURL+'</td><td></td></tr>');
	jQuery.each(arr, function(k,v){
		jQuery('table tbody tr.starred'+dt+' td:last').append('<span>'+v+'</span><br>');
	});
},
getEmails = function(){
	jQuery.ajax({
		url: apiUrl+'?action=getEmails',
		data: 'hostName='+getHostName(jQuery('#pageUrl').val()),
		success:function(r){
			r = JSON.parse(r);
			if(r.code == 200){
				r = JSON.parse(r.emails);
				var dt = new Date().getTime();
				jQuery('table tbody').html('');
				jQuery('.ajax').html('<div class="alert alert-success" role="alert"><span>'+r.length+'</span> email(s) found</div>');
				jQuery.each(r, function(k,v){
					jQuery('table tbody').append('<tr class="starred'+dt+'"><td>'+v.url+'</td><td></td></tr>');
					jQuery.each(v.email, function(k,v){
						jQuery('table tbody tr:last td:last').append('<span>'+v+'</span><br>');
					});
				});
				pagination();
			}else{
				jQuery('.ajax').html('<div class="alert alert-success" role="alert"><span>'+r.emails+'</span></div>');
				pagination();
			}
		},
		error:function(){console.log(r);},
		timeout: 1000*30
	});
},
eCrawler = function(){
	jQuery('.pagination').remove();
	var pageURL = jQuery('#pageUrl').val(), hostName = getHostName(pageURL);
	jQuery('table tbody').html('');
	jQuery('.ajax').html('<div class="alert alert-success" role="alert"><div class="btn btn-success btn btn-primary btn-sm saveToDb">Insert into DB</div><span>0</span> unique email(s) found</div>');
	
	jQuery('.saveToDb').click(function(){
		if(jQuery('.ajax span').text() !== '0'){
			var emailsList = jQuery('table tbody tr').map(function(){
				return {
					url: decodeURI(jQuery('td:first', this).text()),
					email: jQuery('td:last span').map(function(){
						return jQuery.trim(jQuery(this).text());
					}).get()
				}
			}).get();

			jQuery.ajax({
				url: apiUrl+'?action=saveToDb',
				type: 'POST',
				data: 'emailsList='+JSON.stringify({
					'hostName': getHostName(jQuery('#pageUrl').val()),
					'emailsList': JSON.stringify(emailsList)
				}),
				success:function(r){
					r = JSON.parse(r);
					jQuery('.ajax').html('<div class="alert alert-success" role="alert">'+r.message+'</div>');
				},
				error:function(){console.log(r);},
				timeout: 1000*30
			});	
		}	
	});
	
	jQuery.get(apiUrl+'?action=getContent&pageUrl='+encodeURI(pageURL), function(r){
		crawler(pageURL, r);
		var parser = new DOMParser(), doc = parser.parseFromString(r, 'text/html');
		
		var urls = uniqueUrls(pageURL, jQuery('a', doc).map(function(k, v){
			return jQuery(v).attr('href');
		}).get());
		
		jQuery.each(urls, function(k,v){
			if(v.indexOf('mailto') == -1 && getHostName(v).indexOf(hostName) > -1){
				jQuery.ajax({
					url: apiUrl+'?action=getContent&pageUrl='+encodeURI(v),
					success:function(r){
						crawler(v, r);
						var arr = jQuery('table tbody tr td span').map(function(){
							var email = jQuery.trim(jQuery(this).text());
							if(validateEmail(email)){
								return email;
							}
						}).get();
						jQuery('.alert-success span:first').text(uniqueEmails(arr).length);
					},
					error:function(){console.log(r);},
					timeout: 1000*30
				});
			}
		});
	});
}

jQuery(document).ready(function(){
	jQuery('.loading div').animate({'width': '100%'}, 1000*5, function(){
		jQuery('.loading').remove();
	});
	jQuery('.btn-primary').click(function(){
		eCrawler();
	});
	jQuery('.emailList').click(function(){
		getEmails();
	});
});