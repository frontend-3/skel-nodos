define(["validate-ext"],function(){function e(e){var t=e;$.each($("input[date]"),function(e){var t=$(this).attr("data-rule-mindate").split("/"),n=$(this).attr("data-rule-maxdate").split("/"),r=new Date(t[2],t[1]-1,t[0]),i=new Date(n[2],n[1]-1,n[0]);$(this).datepicker({dateFormat:"dd/mm/yy",minDate:r,maxDate:i})}),$("input.number").numeric(),$("input[date]").numeric({allow:"/"}),t.validate({errorClass:"ElementForm-label--error"})}function t(){var e=$(this),t=e.val(),n=$("#cod_prov"),r=$("#cod_dist"),i='<option class="ElementForm-selectOption" value="">Selecciona</option>',s;t===""?n.html(i).attr("disabled").parent().addClass("is-disabled"):(n.html(i),n.removeAttr("disabled"),n.parent().removeClass("is-disabled"),n.trigger("change"),r.html(i).attr("disabled","disabled"),r.parent().addClass(".is-disabled"),r.trigger("change"),$.get($("form").attr("data-source-provinces"),{cod_dpto:t}).done(function(e){for(s in e)i+='<option class="ElementForm-selectOption" value="'+e[s].id+'">'+e[s].name+"</option>";n.html(i)}))}function n(){var e=$("#cod_dist"),t=$("#cod_dpto"),n=$(this),r='<option class="ElementForm-selectOption" value="">Selecciona</option>',i=n.val(),s;i===""?(e.html(r),e.attr("disabled","disabled"),e.parent().addClass("ci-select-disabled"),e.trigger("change")):(e.removeAttr("disabled"),e.parent().removeClass("ci-select-disabled"),e.trigger("change"),$.get($("form").attr("data-source-districts"),{cod_prov:n.val(),cod_dpto:t.val()}).done(function(t){for(s in t)r+='<option class="ElementForm-selectOption" value="'+t[s].id+'">'+t[s].name+"</option>";e.html(r)}))}function r(){var e=$(this);e.parent().find(".ElementForm-selectText").html(e.find("option:selected").html())}function i(e){var t=e.message,n="",r,i="";for(r in t)selector_id="#"+r.split("_")[0]+"_error",n=t[r],$(selector_id).text(n).show()}function s(e,t,n){ga("send","event",e,t,n)}function o(i){var s=$(i.form),o=$("#cod_dpto"),a=$("#cod_prov"),f=$("#cod_dist");e(s),s.on("submit",{settings:i},u),o.on("change",r),o.on("change",t),a.on("change",r),a.on("change",n),f.on("change",r),o.val("15"),o.trigger("change")}function u(e){var t=$(this),n=e.data.settings,r=n.tracking;if(!t.valid())return!1;if(n.isAjax){var o={};o.first_name=$("#first_name").val(),o.last_name=$("#last_name").val(),o.email=$("#email").val(),o.dni=$("#document_number").val(),o.phone=$("#phone").val(),o.cod_dpto=$("#cod_dpto").val(),o.cod_prov=$("#cod_prov").val(),o.cod_dist=$("#cod_dist").val(),o.tyc=$("#tyc").val(),o.csrf_token=$("#csrf_token").val(),$.post(t.attr("action"),data).done(function(e){e.status_code!=0?i(e):s(r.category,r.action,r.label)}).fail(function(){})}r&&s(r.category,r.action,r.label)}return{setupRegisterForm:o}});