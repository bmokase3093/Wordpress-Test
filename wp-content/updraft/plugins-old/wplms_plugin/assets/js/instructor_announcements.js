!function(e){var n={};function t(o){if(n[o])return n[o].exports;var s=n[o]={i:o,l:!1,exports:{}};return e[o].call(s.exports,s,s.exports,t),s.l=!0,s.exports}t.m=e,t.c=n,t.d=function(e,n,o){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:o})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(t.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var s in e)t.d(o,s,function(n){return e[n]}.bind(null,s));return o},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=1)}([function(e,n,t){},function(e,n,t){"use strict";t.r(n);const{createElement:o,render:s,useState:a,useEffect:r,Fragment:i}=wp.element,{select:u,dispatch:c}=wp.data;var d=e=>{const[n,t]=a([]);return r(()=>{fetch(window.instructor_announcements.api+"/instructor_courses",{method:"post",body:JSON.stringify({token:u("vibebp").getToken()})}).then(e=>e.json()).then(e=>{e.status&&t(e.courses)})},[]),o("select",{onChange:n=>{e.update(n.target.value)},value:e.value},o("option",null,window.instructor_announcements.translations.select_course),n.length?n.map(e=>o("option",{value:e.value},e.label)):"")};t(0);const{createElement:l,render:_,useState:m,useEffect:p,Fragment:w}=wp.element,{select:f,dispatch:g}=wp.data,v=e=>{const[n,t]=m(!0),[o,s]=m(!1),[a,r]=m(!0),[i,u]=m([]),[c,_]=m({message:"",course_id:0,student_type:""}),[v,b]=m(!1);p(()=>{let n={type:e.type,token:f("vibebp").getToken()};e.course_id&&(_({...c,course_id:e.course_id}),n.course_id=e.course_id),fetch(window.instructor_announcements.api+"/instructor_announcements/",{method:"post",body:JSON.stringify(n)}).then(e=>e.ok?e.json():{status:0,message:window.wplms_course_data.translations.error_loading_data}).then(n=>{n.status&&u(n.announcements),t(!1),document.dispatchEvent(new CustomEvent("vibebp_widget_loaded",{detail:{props:e}}))}).catch(e=>{t(!1),console.error("Uh oh, an error!",e),g("vibebp").addNotification({text:window.wplms_course_data.translations.error_loading_data})})},[]);return l("div",{className:"instructor_announcements"},l("h3",{class:"widget_title"},e.settings.title),n?l("div",{class:"widget_loader"},l("div",null),l("div",null),l("div",null),l("div",null)):l(w,null,l("div",{className:"existing_announcements"},i.length?i.map(n=>l("div",{className:"course_announcement"},l("a",{href:n.course_link},l("span",null,n.course_title)),l("span",null,n.announcement),l("a",{className:"vicon vicon-close",onClick:()=>{window.confirm(window.instructor_announcements.translations.are_you_sure)&&(n=>{r(!0),fetch(window.instructor_announcements.api+"/instructor_announcements/remove?post",{method:"post",body:JSON.stringify({course_id:n.id,token:f("vibebp").getToken(),type:e.type})}).then(e=>e.ok?e.json():{status:0,message:window.wplms_course_data.translations.error_loading_data}).then(e=>{if(e.status){let e=[...i];e.splice(e.findIndex(e=>e.id===n.id),1),u(e)}r(!1)}).catch(e=>{console.error("Uh oh, an error!",e),g("vibebp").addNotification({text:window.wplms_course_data.translations.error_loading_data})})})(n)}}))):l("div",{className:"vbp_message"},window.instructor_announcements.translations.no_announcements)),v?l(w,null,l("textarea",{value:c.message,onChange:e=>{_({...c,message:e.target.value})}}),e.course_id?"":l(d,{value:c.course_id,update:e=>{_({...c,course_id:e})}}),l("select",{value:c.student_type,onChange:e=>{_({...c,student_type:e.target.value})}},Object.keys(window.instructor_announcements.student_types).map(e=>l("option",{value:e},window.instructor_announcements.student_types[e]))),l("a",{className:o?"button is-primary is-loading":"button is-primary",onClick:()=>{if(!c.course_id)return void g("vibebp").addNotification({text:window.instructor_announcements.translations.missing_data});let n=[...i];s(!0),fetch(window.instructor_announcements.api+"/instructor_announcements/submit?post",{method:"post",body:JSON.stringify({announcement:c,token:f("vibebp").getToken(),type:e.type,user_id:window.instructor_announcements.user_id})}).then(e=>e.ok?e.json():{status:0,message:window.wplms_course_data.translations.error_loading_data}).then(e=>{s(!1),e.status&&(n.unshift(e.announcement),u(n))}).catch(e=>{s(!1),console.error("Uh oh, an error!",e),g("vibebp").addNotification({text:window.wplms_course_data.translations.error_loading_data})})}},o?"...":window.instructor_announcements.translations.add_announcement)):l("a",{className:"button is-primary",onClick:()=>{b(!0)}},window.instructor_announcements.translations.add_announcement)))};document.addEventListener("wplms_announcement",e=>{document.querySelector(".wplms_dashboard_instructor_announcements")&&_(l(v,{settings:e.detail.widget.options}),document.querySelector(".wplms_dashboard_instructor_announcements"))})}]);