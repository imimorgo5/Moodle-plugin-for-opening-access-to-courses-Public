YUI.add("moodle-availability_registrationdate-form",(function(e,i){M.availability_registrationdate=M.availability_registrationdate||{},M.availability_registrationdate.form=e.Object(M.core_availability.plugin),M.availability_registrationdate.form.timeFields=null,M.availability_registrationdate.form.description_before=null,M.availability_registrationdate.form.description_after=null,M.availability_registrationdate.form.isSection=null,M.availability_registrationdate.form.initInner=function(e,i,a,t){this.timeFields=e,this.description_before=i,this.description_after=a,this.isSection=t},M.availability_registrationdate.form.getNode=function(i){var a='<span class="availability_registrationdate">';a+='<span class="desc_before">'+this.description_before+"</span>",a+='<label><select name="regnumber">';for(var t=1;t<60;t++)a+='<option value="'+t+'">'+t+"</option>";a+="</select></label> ",a+='<label><select name="regdnw">';for(t=0;t<this.timeFields.length;t++)a+='<option value="'+this.timeFields[t].field+'">'+this.timeFields[t].display+"</option>";a+="</select></label> ",a+='<span class="desc_after">'+this.description_after+"</span>";var l=e.Node.create("<span>"+a+"</span>"),r=1;(void 0!==i.n&&(r=i.n),l.one("select[name=regnumber]").set("value",r),r=2,void 0!==i.d&&(r=i.d),l.one("select[name=regdnw]").set("value",r),M.availability_registrationdate.form.addedEvents)||(M.availability_registrationdate.form.addedEvents=!0,e.one(".availability-field").delegate("change",(function(){M.core_availability.form.update()}),".availability_registrationdate select"));return l},M.availability_registrationdate.form.fillValue=function(e,i){e.n=Number(i.one("select[name=regnumber]").get("value")),e.d=Number(i.one("select[name=regdnw]").get("value"))},M.availability_registrationdate.form.fillErrors=function(e,i){this.fillValue({},i)}}),"@VERSION@",{requires:["base","node","event","moodle-core_availability-form"]});