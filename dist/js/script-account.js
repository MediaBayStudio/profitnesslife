document.addEventListener("DOMContentLoaded",function(){function c(t){var e=a.getAttribute("r"),e=Math.PI*(2*e);(t=100-t)<=0||(e=(100-(t=100<t?100:t))/100*e,a.style.opacity=1,a.style.strokeDashoffset=e)}var h,u,p,g,a,i,s,r,o,n,d,l,m,b,w,f,v,C,L,x;h=q(".weight-form"),u=q(".weight-chart-sect"),p=id("weight-goal-current"),g=id("weight-goal-total"),a=id("weight-goal-svg-bar"),i=document.forms["user-avatar-form"],s={source:q("source",i),img:q("img",i)},c(parseInt(p.textContent)/parseInt(g.textContent)*100),h["current-weight"].addEventListener("input",function(t){h.submit.classList.toggle("disabled",t.target.value.length<=1)}),h.addEventListener("submit",function(t){t.preventDefault();var e=new FormData(h),t=new Date,d=h["current-weight"].value,l=("0"+t.getDate()).slice(-2)+"."+("0"+(t.getMonth()+1)).slice(-2)+"."+t.getFullYear();e.append("action","weight_send"),e.append("date",l),h.classList.add("loading"),h.submit.blur(),fetch(h.action,{method:"POST",body:e}).then(function(t){return t.ok?t.text():""}).then(function(t){var e,a,i,s,r,o,n;h.classList.remove("loading"),h.classList.add("disabled"),id("current-weight-date").textContent=l,id("current-weight-number").innerHTML=d+' <span class="user-data__current-weight-units">кг</span>',p.textContent=Math.abs(d-h.getAttribute("data-target-weight")).toFixed(1)+" /",c(parseInt(p.textContent)/parseInt(g.textContent)*100),weightChart&&(e=weightChart,s={date:a=l,weight:i=d},r=q(".weight-chart__tab.active"),o=q(".weight-chart-sect").getAttribute("data-week"),n=q(".weight-chart__tab:nth-child("+o+")"),(o=n.getAttribute("data-chart"))&&((o=JSON.parse(o)).push(s),s=o),s=JSON.stringify([s]),n.setAttribute("data-chart",s),r&&r.classList.remove("active"),u.classList.remove("hide"),n.classList.add("active"),7===weightChart.config.data.datasets[0].data.length?(e.data.labels=[a.slice(0,-5)],e.data.datasets.forEach(function(t){return t.data=[i]}),n.classList.remove("disabled"),weightChart.options.scales.y.min=i-2,weightChart.options.scales.y.max=+i+1):(e.data.labels.push(a.slice(0,-5)),e.data.datasets.forEach(function(t){return t.data.push(i)})),e.options.scales.y.min=i-2,e.update())}).catch(function(t){})}),i.addEventListener("change",function(t){var e=new FormData(i);e.append("action","photo_send"),e.append("type","avatar"),i.classList.add("loading"),fetch(i.action,{method:"POST",body:e}).then(function(t){return t.ok?t.text():""}).then(function(t){t=JSON.parse(t),i.classList.remove("loading"),s.source.srcset=t.img_webp,s.img.src=t.img,i.reset()}).catch(function(t){errorPopup.openPopup(),i.reset(),i.classList.remove("loading")})}),f=document.forms["measure-form"],v=id("measure-chart"),C=v.getContext("2d"),L=media("(max-width:767.98px)")?10:16,x=media("(max-width:767.98px)")?14:16,f&&(r=v.getAttribute("data-initial-date"),o=v.getAttribute("data-initial-chest"),n=v.getAttribute("data-initial-waist"),d=v.getAttribute("data-initial-hip"),l=v.getAttribute("data-measure-chart"),m="null"!==l,b=function(t,e,a,i){measureChart=new Chart(C,{type:"line",data:{labels:t,datasets:[{label:"Грудь",data:e,backgroundColor:"#85B921",borderColor:"#85B921",borderWidth:1},{label:"Талия",data:a,backgroundColor:"#F0BE0F",borderColor:"#F0BE0F",borderWidth:1},{label:"Бёдра",data:i,backgroundColor:"#E99A8B",borderColor:"#E99A8B",borderWidth:1}]},options:{plugins:{legend:{padding:10,labels:{padding:15,boxWidth:5,boxHeight:5,usePointStyle:!0,font:{size:x,family:"Roboto"}}}},scales:{x:{ticks:{color:"#B0BBA7",font:{size:L,family:"Roboto"}},grid:{display:!1}},y:{ticks:{stepSize:1,color:"#B0BBA7",font:{size:L,family:"Roboto"}},grid:{display:!1}}}}})},w=function(t){var t=(t=t||v.getAttribute("data-measure-chart")).constructor===Array?t:JSON.parse(t),e=[r.slice(0,-5)],a=[],i=[],s=[];return o&&a.push(o),n&&i.push(n),d&&s.push(d),t.forEach(function(t){e.push(t.date.slice(0,-5)),a.push(t.chest),i.push(t.waist),s.push(t.hip)}),{date:e,chest:a,waist:i,hip:s}},f.addEventListener("input",function(t){f.submit.classList.toggle("disabled",f.chest.value.length<2||f.waist.value.length<2||f.hip.value.length<2)}),f.addEventListener("submit",function(t){t.preventDefault();var e=new FormData(f),t=new Date,a=("0"+t.getDate()).slice(-2)+"."+(t.getMonth()+1)+"."+t.getFullYear();e.append("action","measure_send"),e.append("date",a),f.classList.add("loading"),f.submit.blur(),fetch(f.action,{method:"POST",body:e}).then(function(t){return t.ok?t.text():""}).then(function(t){t=JSON.parse(t),f.classList.add("disabled"),f.classList.remove("loading"),q(".measure-current").classList.remove("no-data"),q(".measure-form__descr").textContent="Будет доступно завтра",id("measure-chest-value").textContent=f.chest.value,id("measure-waist-value").textContent=f.waist.value,id("measure-hip-value").textContent=f.hip.value,id("measure-date").textContent=a,m?(l=w(t.chart_data),measureChart.data.labels=l.date,measureChart.data.datasets.forEach(function(t){var e;switch(t.label){case"Грудь":e="chest";break;case"Талия":e="waist";break;case"Бёдра":e="hip"}return t.data=l[e]}),measureChart.update()):"null"===l?t.initial_measure||(l=w(t.chart_data),v.classList.remove("hide"),b(l.date,l.chest,l.waist,l.hip),m=!0):(l=w(),v.classList.remove("hide"),b(l.date,l.chest,l.waist,l.hip),m=!0),f.chest.value="",f.waist.value="",f.hip.value="",f.chest.setAttribute("tabindex","-1"),f.waist.setAttribute("tabindex","-1"),f.hip.setAttribute("tabindex","-1")}).catch(function(t){})}),o&&n&&d&&"null"!==l&&(l=w(),b(l.date,l.chest,l.waist,l.hip),m=!0)),function(){var t=id("weight-chart-sect");if(t){var e=id("weight-chart"),a=q(".weight-chart__tabs",t),i=q(".weight-chart__tab.active",a),s=JSON.parse(i.getAttribute("data-chart")),e=e.getContext("2d");if(a.addEventListener("click",function(t){var t=t.target,e=t.getAttribute("data-chart");if(t.classList.contains("weight-chart__tab")&&e){var a,e=JSON.parse(e),i=[],s=[];for(a in e)i[i.length]=e[a].date.slice(0,-5),s[s.length]=e[a].weight;weightChart.data.labels=i,weightChart.data.datasets.forEach(function(t){return t.data=s}),weightChart.options.scales.y.min=s[s.length-1]-2,weightChart.options.scales.y.max=+s[0]+1,weightChart.update()}}),!t.classList.contains("hide")){s||(i=q(".weight-chart__tab[data-chart]",a),s=JSON.parse(i.getAttribute("data-chart")));var r,o=[],n=[],i=media("(max-width:767.98px)")?10:16;for(r in s)o[o.length]=s[r].date.slice(0,-5),n[n.length]=s[r].weight;weightChart=new Chart(e,{type:"line",defaults:{borderColor:"red"},data:{labels:o,datasets:[{label:"Вес, кг",data:n,backgroundColor:"#85B921",borderColor:"#85B921",borderWidth:1}]},options:{plugins:{legend:{display:!1}},scales:{x:{ticks:{color:"#B0BBA7",font:{size:i,family:"Roboto"}},grid:{display:!1}},y:{ticks:{stepSize:1,color:"#B0BBA7",font:{size:i,family:"Roboto"}},grid:{display:!1},beginAtZero:!0,min:n[n.length-1]-2,max:+n[0]+1}}}})}}}(),function(){function i(){media("(min-width:1279.98px)")&&n.length<5||media("(min-width:1023.98px)")&&n.length<4||media("(min-width:767.98px)")&&n.length<4||media("(min-width:575.98px)")&&n.length<4?SLIDER.hasSlickClass(o)&&SLIDER.unslick(o):SLIDER.hasSlickClass(o)||n.length&&2<n.length&&o.slick({slidesToShow:2,slidesToScroll:2,infinite:!1,appendArrows:$(".photo-progress-slider__nav",r.parentElement),nextArrow:SLIDER.createArrow("photo-progress-slider__next",SLIDER.arrow),prevArrow:SLIDER.createArrow("photo-progress-slider__prev",SLIDER.arrow),dots:!0,variableWidth:!0,slide:"form, picture",dotsClass:"photo-progress-slider__dots dots",appendDots:$(".photo-progress-slider__nav",r.parentElement),customPaging:function(){return SLIDER.dot},mobileFirst:!0,responsive:[{breakpoint:575.98,settings:{slidesToShow:3,slidesToScroll:3}},{breakpoint:767.98,settings:{slidesToShow:4,slidesToScroll:4}},{breakpoint:1023.98,settings:{slidesToShow:3,slidesToScroll:3}},{breakpoint:1279.98,settings:{slidesToShow:4,slidesToScroll:4}}]})}var s=document.forms["photo-progress-form"],r=id("photo-progress-slider"),o=$(r),n=qa("form, picture",r);n.length;i(),windowFuncs.resize.push(i),s.addEventListener("change",function(t){var e=new FormData(s);e.append("action","photo_send"),r.classList.add("loading"),fetch(s.action,{method:"POST",body:e}).then(function(t){return t.ok?t.text():""}).then(function(t){var e=(t=JSON.parse(t)).img,a=t.img_webp;if(!e)return s.reset(),r.classList.remove("loading"),void errorPopup.openPopup();e='<picture class="photo-progress-pic">';a&&(e+='<source type="image/webp" srcset="'+a+'">'),e+='<img src="'+t.img+'" alt="Фото" class="photo-progress-img"></picture>',SLIDER.hasSlickClass(o)?o.slick("slickAdd",e,1,!0):s.insertAdjacentHTML("afterend",e),n=qa("form, picture",r),n.length,i(),s.reset(),r.classList.remove("loading")}).catch(function(t){errorPopup.openPopup(),r.classList.remove("loading"),s.reset()})})}()});