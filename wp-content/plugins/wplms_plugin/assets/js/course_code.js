!function(e){var t={};function o(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=e,o.c=t,o.d=function(e,t,r){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(o.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)o.d(r,n,function(t){return e[t]}.bind(null,n));return r},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=0)}([function(e,t,o){"use strict";o.r(t);o(1);const{createElement:r,render:n,useState:s,useEffect:a,Fragment:i}=wp.element,{select:c,dispatch:d}=wp.data,u=e=>{const[t,o]=s(!1),[n,i]=s(""),[u,l]=s(!1),[p,m]=s(!1);a(()=>{fetch(`${window.wplms_course_codes.api}/user/check_course_member/${e.course}?force`,{method:"post",body:JSON.stringify({token:c("vibebp").getToken()})}).then(e=>e.ok?e.json():{status:0,message:window.wplms_course_data.translations.error_loading_data}).then(e=>{if(e.hasOwnProperty("is_member")&&e.is_member){let t=new Date;e.hasOwnProperty("expiry")&&e.expiry&&parseInt(e.expiry)>t.getTime()/1e3?l(!1):l(!0)}else l(!0)}).catch(e=>{console.error("Uh oh, an error!",e),d("vibebp").addNotification({text:window.wplms_course_data.translations.error_loading_data})})},[]);return u?r("div",{className:"course_code form_field"},r("input",{type:"text",placeholder:window.wplms_course_codes.translations.enter_code,onChange:e=>{i(e.target.value)}}),r("a",{className:t?"fade is-loading button is-primary":"button is-primary",onClick:()=>{o(!0),fetch(`${window.wplms_course_codes.api}/user/check_code/${e.course}?post`,{method:"post",body:JSON.stringify({token:c("vibebp").getToken(),code:n})}).then(e=>e.ok?e.json():{status:0,message:window.wplms_course_data.translations.error_loading_data}).then(e=>{e&&e.hasOwnProperty("status")&&e.status&&window.location.reload(),e&&e.hasOwnProperty("message")&&e.message&&m(e.message),o(!1)}).catch(e=>{o(!1),console.error("Uh oh, an error!",e),d("vibebp").addNotification({text:window.wplms_course_data.translations.error_loading_data})})}},window.wplms_course_codes.translations.submit),p&&p.length?r("div",{dangerouslySetInnerHTML:{__html:p},className:""}):""):""},l=()=>{document.querySelectorAll(".wplms_course_codes").forEach(e=>{n(r(u,{course:document.querySelector(".wplms_course_codes").getAttribute("data-course")}),e)}),document.removeEventListener("userLoaded",l,!1)};document.addEventListener("userLoaded",l,!1)},function(e,t,o){}]);