define([],function(){function e(){var e=$(this),t=e.val(),n=$("#cod_prov"),r=$("#cod_dist"),i='<option class="ElementForm-selectOption" value="">Selecciona</option>',s;t===""?(n.html(i).attr("disabled"),n.parent().addClass("is-disabled")):(n.html(i),n.removeAttr("disabled"),n.parent().removeClass("is-disabled"),n.trigger("change"),r.html(i).attr("disabled","disabled"),r.parent().addClass("is-disabled"),r.trigger("change"),$.get($("form").attr("data-source-provinces"),{cod_dpto:t}).done(function(e){for(s in e)i+='<option class="ElementForm-selectOption" value="'+e[s].id+'">'+e[s].name+"</option>";n.html(i)}))}function t(){var e=$("#cod_dist"),t=$("#cod_dpto"),n=$(this),r='<option class="ElementForm-selectOption" value="">Selecciona</option>',i=n.val(),s;i===""?(e.html(r),e.attr("disabled","disabled"),e.parent().addClass("is-disabled"),e.trigger("change")):(e.removeAttr("disabled"),e.parent().removeClass("is-disabled"),e.trigger("change"),$.get($("form").attr("data-source-districts"),{cod_prov:n.val(),cod_dpto:t.val()}).done(function(t){for(s in t)r+='<option class="ElementForm-selectOption" value="'+t[s].id+'">'+t[s].name+"</option>";e.html(r)}))}function n(){var n=$("#cod_dpto"),r=$("#cod_prov"),i=$("#cod_dist");r.length>0&&(n.on("change",e),r.on("change",t))}return{setupRegisterForm:n}});