var weightChart,measureChart,mask,lazy,menu,sideMenu,burger,hdr,overlay,body,fakeScrollbar,resetQuestionnairePopup,allowedProductsPopup,nutritionRulesPopup,workoutRulesPopup,inventoryPopup,productsCartPopup,loginPopup,workoutPopup,errorPopup,thanksPopup,thanksPopupTimer,mobileMenu,browser={isOpera:!!window.opr&&!!opr.addons||!!window.opera||0<=navigator.userAgent.indexOf(" OPR/"),isFirefox:"undefined"!=typeof InstallTrigger,isSafari:/constructor/i.test(window.HTMLElement)||"[object SafariRemoteNotification]"===(!window.safari||"undefined"!=typeof safari&&safari.pushNotification).toString(),isIE:!!document.documentMode,isEdge:!document.documentMode&&!!window.StyleMedia,isChrome:!!window.chrome&&!!window.chrome.webstore,isYandex:!!window.yandex,isMac:0<=window.navigator.platform.toUpperCase().indexOf("MAC")},dispatchEvent=function(e,t){"function"==typeof window.CustomEvent&&(e=new CustomEvent(e),t.dispatchEvent(e))},SLIDER={dot:'<button type="button" class="dot"></button>',hasSlickClass:function(e){return e.hasClass("slick-slider")},unslick:function(e){e.slick("unslick")},createArrow:function(e,t){return'<button type="button" class="arrow arrow-'+(e=(-1===e.indexOf("prev")?"next ":"prev ")+e)+'">'+t+"</button>"},arrow:'<svg class="arrow__svg" width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.22 17.983a.665.665 0 0 1-.073-.91l.073-.08 6.469-6.034-6.47-6.034a.665.665 0 0 1-.072-.91l.073-.08a.79.79 0 0 1 .976-.067l.084.068 7 6.529c.267.248.29.637.073.91l-.073.08-7 6.528a.789.789 0 0 1-1.06 0Z" fill="currentColor"/></svg>'},windowFuncs={load:[],resize:[],scroll:[],call:function(e){for(var t=windowFuncs[e.type]||e,n=t.length-1;0<=n;n--)t[n]()}},resetQuestionnaire=function(e){var t=this!==window?this:event.target,n=t.getAttribute("data-user"),i="action=questionnaire_send&reset=reset",o=siteUrl+"/wp-admin/admin-ajax.php";n&&(i+="&user="+n),e&&(i+="&reset_by_user=1"),t.classList.add("loading"),fetch(o,{method:"POST",body:i,headers:{"Content-Type":"application/x-www-form-urlencoded"}}).then(function(e){return e.ok?e.text():""}).then(function(e){location.reload()}).catch(function(e){t.classList.remove("loading")})},q=function(e,t){return(t=t||document.body).querySelector(e)},qa=function(e,t,n){return t=t||document.body,n?Array.prototype.slice.call(t.querySelectorAll(e)):t.querySelectorAll(e)},id=function(e){return document.getElementById(e)},setVh=function(){var e=.01*window.innerHeight;document.documentElement.style.setProperty("--vh",e+"px")},media=function(e){return window.matchMedia(e).matches},scrollToTarget=function(e,t){var n,i,o,r,a;e&&e.preventDefault(),_=this===window?e.target:this,(t=0==t?body:t||_.getAttribute("data-scroll-target"))||"A"!==_.tagName||(t=_.getAttribute("href").replace(/http:\/\/|https:\/\//,""),t=q(t)),t.constructor===String&&(t=t.replace(/http:\/\/|https:\/\//,""),t=q(t)),t&&(menu&&menu.close(),n=window.pageYOffset,e=getComputedStyle(t),i=t.getBoundingClientRect().top-+e.paddingTop.slice(0,-2)-+e.marginTop.slice(0,-2),o=null,r=.35,a=function(e){e-=o=null===o?e:o,e=i<0?Math.max(n-e/r,n+i):Math.min(n+e/r,n+i);window.scrollTo(0,e),e!=n+i&&requestAnimationFrame(a)},requestAnimationFrame(a))},pageScroll=function(e){fakeScrollbar&&(fakeScrollbar.classList.toggle("active",e),body.classList.toggle("no-scroll",e),body.style.paddingRight=e?fakeScrollbar.offsetWidth-fakeScrollbar.clientWidth+"px":"")},sticky=function(e,t,n){e="string"==typeof e?q(e):e,n=n||"fixed",t=t||"bottom";var i=e.getBoundingClientRect()[t]+pageYOffset,o=e.cloneNode(!0),r=e.parentElement,a=function(){!e.classList.contains(n)&&pageYOffset>=i&&(r.appendChild(r.replaceChild(o,e)),e.classList.add(n),window.removeEventListener("scroll",a),window.addEventListener("scroll",s))},s=function(){e.classList.contains(n)&&pageYOffset<=i&&(r.replaceChild(e,o),e.classList.remove(n),window.removeEventListener("scroll",s),window.addEventListener("scroll",a))};o.classList.add("clone"),a(),window.addEventListener("scroll",a)};document.addEventListener("DOMContentLoaded",function(){function e(){t.style.transition="none",t.style.height="auto",t.style.height=t.scrollHeight+"px",t.style.transition=""}var t,n;body=document.body,function(){mask=function(){var e="+7(___)___-__-__",t=0,n=e.replace(/\D/g,""),i=this.value.replace(/\D/g,"");n.length>=i.length&&(i=n),this.value=e.replace(/./g,function(e){return/[_\d]/.test(e)&&t<i.length?i.charAt(t++):t>=i.length?"":e}),"blur"===event.type?2===this.value.length&&(this.value="",this.classList.remove("filled")):(n=this.value.length,(e=this).focus(),e.setSelectionRange?e.setSelectionRange(n,n):e.createTextRange&&((e=e.createTextRange()).collapse(!0),e.moveEnd("character",n),e.moveStart("character",n),e.select()))};for(var e=qa("[name=tel]"),t=0;t<e.length;t++)e[t].addEventListener("input",mask),e[t].addEventListener("focus",mask),e[t].addEventListener("blur",mask)}(),(t=document.querySelector(".account-hero-maybe-close"))&&(e(),t.addEventListener("click",function(e){e&&"click"===e.type&&e.target.classList.contains("account-hero__close")&&(t.addEventListener("transitionend",function(e){"height"===e.propertyName&&(t.style.display="none")}),t.style.cssText="padding:0;margin:0;height:0;opacity:0"),fetch(siteUrl+"/wp-admin/admin-ajax.php",{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:"action=close_welcome_block&user-id="+t.getAttribute("data-user-id")}).then(function(e){return e.ok?e.text():""}).then(function(e){}).catch(function(e){})}),windowFuncs.resize.push(e)),(n=q(".form-sign"))&&n.addEventListener("submit",function(e){e.preventDefault(),n.classList.add("loading"),n.blur();var t=new FormData(n),e=q(".invalid",n);t.append("action","auth"),e&&e.parentElement.removeChild(e),fetch(siteUrl+"/wp-admin/admin-ajax.php",{method:"POST",body:t}).then(function(e){return e.ok?e.text():""}).then(function(e){(e=JSON.parse(e)).error?(n.user_pass.insertAdjacentHTML("afterend",'<label class="invalid">Неверный логин или пароль</label>'),n.classList.remove("loading")):location.replace(siteUrl+"/account")}).catch(function(e){n.classList.remove("loading"),errorPopup.openPopup()})}),function(){function e(e){function u(e){var t,n={},i=f,o=function(e){var t,n=e.elements,i={};for(t in v){var o=n[t];o&&(i[t]=o.value)}return i}(i);for(t in o){var r=v[t],a=i[t],s=o[t],l=r.or,c=i[l];if(r&&(a.hasAttribute("required")||!0===r.required)){var d=a.type,r=r.pattern;if(("checkbox"===d||"radio"===d)&&!a.checked||""===s){if(!l||!c){n[t]=h[t].required;continue}if(""===c.value){n[t]=h[t].required;continue}}"cehckbox"!==d&&"radio"!==d&&r&&""!==s&&!1===r.test(s)?n[t]=h[t].pattern:p(a)}}0==Object.keys(n).length?(i.removeEventListener("change",u),i.removeEventListener("input",u),f.validatie=!0):(i.addEventListener("change",u),i.addEventListener("input",u),function(e,t){var n=e.elements,i;for(i in t){var o=t[i],r='<label class="'+m+'">'+o+"</label>",a=n[i],s=a.nextElementSibling;if(s&&s.classList.contains(m)){if(s.textContent!==o)s.textContent=o;continue}else a.insertAdjacentHTML("afterend",r);a.classList.add(m)}}(i,n),f.validatie=!1)}function p(e){var t=e.nextElementSibling;e.classList.remove(m),t&&t.classList.contains(m)&&t.parentElement.removeChild(t)}var f=e.form,t=e.formBtn,l=e.uploadFilesBlock,m="invalid",v=(e.filesInput,{name:{required:!0},surname:{required:!0},tel:{required:!0,pattern:/\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}/},email:{required:!0,pattern:/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z])+$/},msg:{required:!0,pattern:/[^\<\>\[\]%\&'`]+$/},policy:{required:!0}}),h={tel:{required:"Введите ваш телефон",pattern:"Укажите верный телефон"},name:{required:"Введите ваше имя"},surname:{required:"Введите вашу фамилию"},email:{required:"Введите ваш E-mail",pattern:"Введите верный E-mail"},msg:{required:"Введите ваше сообщение",pattern:"Введены недопустимые символы"},policy:{required:"Согласитель с политикой обработки персональных данных"}};f.setAttribute("novalidate",""),f.validatie=!1,t.addEventListener("click",function(e){if((u(),"pay-form"===f.id)&&(e.preventDefault(),f.validatie))return f.classList.add("loading"),void(new cp.CloudPayments).pay("charge",{publicId:"pk_82de7baa82dfeede0822c06c01cbc",description:"Оплата марафона стройности",amount:+f.price.value,currency:"RUB",accountId:f.email.value,email:f.email.value,skin:"mini"},{onSuccess:function(e){q(".pay-hero").classList.remove("active"),q("#success-pay").classList.add("active"),f.classList.remove("loading");var t=new FormData(f);t.append("action","create_payment"),fetch(siteUrl+"/wp-admin/admin-ajax.php",{method:"POST",body:t}).then(function(e){return e.ok?e.text():""}).then(function(e){}).catch(function(e){errorPopup&&errorPopup.openPopup(),f.classList.remove("loading")})},onFail:function(e,t){q(".pay-hero").classList.remove("active"),q("#failure-pay").classList.add("active"),f.classList.remove("loading")},onComplete:function(e,t){}});!1===f.validatie?e.preventDefault():f.classList.add("loading")}),document.wpcf7mailsent||(document.addEventListener("wpcf7mailsent",function(e){var t=q("#"+e.detail.id+">form"),e=e.type;if("wpcf7mailsent"===e){for(var n=t.elements,i=0;i<n.length;i++)p(n[i]),n[i].classList.remove("filled");t.reset(),l&&(l.innerHTML=""),thanksPopup.openPopup(),thanksPopupTimer=setTimeout(function(){thanksPopup.closePopup()},3e3)}else"wpcf7mailfailed"===e&&errorPopup.openPopup();t.classList.remove("loading"),setTimeout(function(){t.classList.remove("sent")},3e3)}),document.wpcf7mailsent=!0),f.addEventListener("input",function(){var e=event.target,t=e.type,n=e.files,i=e.classList,o=e.value;if("text"===t||"email"===t||"tel"===t||"number"===t||"TEXTAREA"===e.tagName)""===o?i.remove("filled"):i.add("filled");else if("file"===t){for(var r="",a=0,s=n.length;a<s;a++)r+='<span class="uploadedfiles__file"><span class="uploadedfiles__file-text">'+n[a].name+"</span></span>";l.innerHTML=r}})}for(var t=[id("index-form"),id("pay-form")],n=t.length-1;0<=n;n--)t[n]&&e({form:t[n],formBtn:q(".btn",t[n])||q('.btn[form="'+t[n].id+'"]'),uploadFilesBlock:q(".uploadedfiles",t[n]),filesInput:q('input[type="file"]',t[n])})}(),mobileMenu=function(e){function t(){N?(i.isOpened=N=!1,o.addEventListener("click",g),r.removeEventListener("click",w),c||pageScroll(!1),sticky(hdr)):(i.isOpened=N=!0,o.removeEventListener("click",g),r.addEventListener("click",w))}function n(){if(C){for(var e in T=null,C)media(e)&&(T=e);T!==A&&function(){if(T){for(var e in C[T])S[e]=C[T][e];A=T}else{for(var t in P)S[t]=P[t];A=null}i&&(x(),k())}()}i||k()}var i,a,o,r,s,l,c,d,u,p,f,m,v=function(e,t){for(var n=[e,t],i=["transform","transition"],o=["translate3d("+e+", 0px, 0px)","transform "+t],r=n.length-1;0<=r;r--)0!==n[r]&&(""===n[r]?n[r]="":n[r]=o[r],a.style[i[r]]=n[r])},h=function(e){return e.constructor===String?q(e):e},g=function(){N||(dispatchEvent("menubeforeopen",i),i.hasAttribute("style")&&(i.removeAttribute("style"),i.offsetHeight),i.classList.add("active"),o.classList.add("active"),a.scrollTop=0,f||(v("0px",".5s"),j=a.offsetWidth),c||pageScroll(!0))},w=function(e,t){var n;N&&(n=e&&e.target,(t||!e||"keyup"===e.type&&27===e.keyCode||n===i||n===r)&&(i.classList.remove("active"),o.classList.remove("active"),f||v(p,".5s")))},y=function(e){F&&(e=e.touches[0]||window.e.touches[0],O=B=!1,Y=R=e.clientX,D=e.clientY,s=Date.now(),a.addEventListener("touchend",b),a.addEventListener("touchmove",L),v(0,""))},L=function(e){var t;F&&(t=e.touches[0]||window.e.touches[0],e=+a.style.transform.match(M)[0],z=R-t.clientX,R=t.clientX,H=D-t.clientY,D=t.clientY,O||B||(t=Math.abs(H),Math.abs(z),7<t||0===z?B=!0:t<7&&(O=!0)),O&&v(u&&R<Y||d&&Y<R?"0px":e-z+"px",0))},b=function(e){m=Y-R;var t=Math.abs(m);l=Date.now(),1<t&&O&&((u&&m<0||d&&0<m)&&(.5*j<=t||l-s<300?w(e,!0):g(e,!(N=!1))),F=!1),i.removeEventListener("touchend",b),i.removeEventListener("touchmove",L)},E=function(e){f?"opacity"===e.propertyName&&t():"transform"===e.propertyName&&t(),F=!0},k=function(){i=h(e.menu),a=h(e.menuCnt),o=h(e.openBtn),r=h(e.closeBtn),c=S.allowPageScroll,d=S.toRight,u=S.toLeft,p=u?"100%":d?"-100%":0,f=S.fade,_("add"),f?d=u=!1:(v(p,0),i.addEventListener("touchstart",y)),i.isOpened=!1},_=function(e){o[e+"EventListener"]("click",g),i[e+"EventListener"]("click",w),i[e+"EventListener"]("transitionend",E),document[e+"EventListener"]("keyup",w)},x=function(){N&&w(),f?d=u=!1:(v("",""),i.removeEventListener("touchstart",y)),_("remove"),r=o=a=i=null},S=JSON.parse(JSON.stringify(e)),P=JSON.parse(JSON.stringify(e)),C=e.responsive,T=null,A=null,M=(pageYOffset,/([-0-9.]+(?=px))/),O=!1,B=!1,F=!1,N=!1,R=0,z=0,D=0,H=0,Y=0,j=0;if(e.menu)return n(),windowFuncs.resize.push(n),{options:S,menu:i,menuCnt:a,openBtn:o,closeBtn:r,open:g,close:w,destroy:x,opened:N}},NodeList.prototype.forEach||(NodeList.prototype.forEach=Array.prototype.forEach),HTMLCollection.prototype.forEach||(HTMLCollection.prototype.forEach=Array.prototype.forEach),fakeScrollbar=id("fake-scrollbar"),burger=q(".hdr__burger"),hdr=q(".hdr"),menu=mobileMenu({menu:q(".menu"),menuCnt:q(".menu__cnt"),openBtn:burger,closeBtn:q(".menu__close"),fade:!0,allowPageScroll:!1}),q(".side-menu")&&(media("(max-width:767.98px)")?(sideMenu=mobileMenu({menu:q(".side-menu"),menuCnt:q(".side-menu__cnt"),openBtn:burger,closeBtn:q(".side-menu__close"),fade:!0,allowPageScroll:!1})).menu.addEventListener("menubeforeopen",function(){for(var e=qa(".side-menu__pic > source, .side-menu__logout-pic > source",this),t=e.length-1;0<=t;t--)e[t].removeAttribute("media")}):sideMenu&&sideMenu.destroy());for(var i,o,r=qa(".hdr .nav-link, .ftr .nav-link, .index-hero__btn, .index-invite__btn"),a=0,s=r.length;a<s;a++)r[a].addEventListener("click",scrollToTarget);for(i in sticky(hdr),lazy=new lazyload({clearSrc:!0,clearMedia:!0}),window.svg4everybody&&svg4everybody(),windowFuncs.resize.push(setVh),windowFuncs)"call"===i||0<(o=windowFuncs[i]).length&&(windowFuncs.call(o),window.addEventListener(i,windowFuncs.call))});