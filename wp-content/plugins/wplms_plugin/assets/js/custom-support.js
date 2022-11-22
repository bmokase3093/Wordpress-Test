document.addEventListener('unit_loaded', (e)=>{
	
	if(window.hasOwnProperty('wplms_course_data') && window.wplms_course_data.hasOwnProperty('dynamic_scripts') && window.wplms_course_data.dynamic_scripts && window.wplms_course_data.dynamic_scripts.length){
		window.wplms_course_data.dynamic_scripts.map(function(_script,i){
			if(document.getElementById(_script.id)){
				document.getElementById(_script.id).remove();
			}	
		    const script = document.createElement("script");
		    script.src =_script.src;
		    script.setAttribute("id", _script.id);
			document.body.appendChild(script);
		});
		
	}
	

	/*if(typeof elementorFrontend==='object' && Object.keys(elementorFrontend).length && typeof elementorFrontend.init !=='undefined'){
		elementorFrontend.init();
	}
	if(typeof elementorProFrontend==='object' && Object.keys(elementorProFrontend).length && typeof elementorProFrontend.init !=='undefined'){
		elementorProFrontend.init();
	}*/
   	
	checkiframeheights();
	
});
window.addEventListener('resize',checkiframeheights);
function checkiframeheights(){
	setTimeout(()=>{
		let iframes = document.querySelectorAll('.wplms_iframe_wrapper iframe');
		for(let i=0;i<iframes.length;i++){
		    let iframe = iframes[i];
		    let oldheight = localStorage.getItem(iframe.contentWindow.location.href);
		    if(oldheight){
		    	iframe.setAttribute('style','height:'+oldheight+'px;width:100%;');
		    }
		    iframe.contentWindow.addEventListener('load',function(){
		    	iframe.setAttribute('style','height:'+iframe.contentWindow.document.querySelector('html').scrollHeight+'px;width:100%;');
		    	localStorage.setItem(iframe.contentWindow.location.href,iframe.contentWindow.document.querySelector('html').scrollHeight);

		    	let resizeObserver = new ResizeObserver((entries) =>{ 

				  	iframe.setAttribute('style','height:'+iframe.contentWindow.document.querySelector('html').scrollHeight+'px;width:100%;');
		    		localStorage.setItem(iframe.contentWindow.location.href,iframe.contentWindow.document.querySelector('html').scrollHeight);
				});

				// start observing a DOM node
				resizeObserver.observe(iframe.contentWindow.document.body);

		    });



		    

		}
	},300);
	
}