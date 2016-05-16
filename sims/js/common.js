
/* 全选
 * @p checkbox的容器ID 一般为form ID
   @name checkbox的name
 */
function checkall(p, name) {
    $('#'+p+' :checkbox[name="'+name+'[]"]').prop('checked', this.checked);
}

function CheckOrAll(va){
	form=document.formlist
	for (var i=0;i<form.elements.length;i++){
		var e = form.elements[i];
		if(va=='all'){
			e.checked = true;
		}else{
			e.checked == true ? e.checked = false : e.checked = true;
		}
	}
}
/* 过滤条件收缩
 */
function show_fld(){
	if($('#filter_show').css("display")!="none"){//隐藏
		$('#filter_show').slideUp('slow',function(){
			$('#query_form_f').addClass("hid_fieldset");
		});
	}else{//显示
		$('#query_form_f').removeClass("hid_fieldset");
		$('#filter_show').slideDown('slow');
	}
}


/* 导出Excel
 * @url 执行导出的地址
 */
function export_xls(url) {
    $('#export_ifr').attr('src', url);
}

function export_form_xls(formid, url) {
	var qstr = $('#'+formid).serialize();
	url += (url.indexOf('?') > -1 ? '&' : '?') + qstr;
    $('#export_ifr').attr('src', url);
}

// write by s.exp

function checktext(id){	
	var val = $.trim($("input[name='"+id+"']").val());
	//alert(val);
	var reg	= new RegExp(/^.{2,20}$/);
	if(!reg.test(val)){
		$("#"+id+"_tip").html('输入框内请输入正确的内容！');
		return false;
	}else{
		$("#"+id+"_tip").html('');	
		return true;
	}
}

function checkselect(id){	
	var val = $("#"+id).val();
	//alert(val);
	//var reg	= new RegExp(/^.{1,20}$/);
	if(!val){
		$("#"+id+"_tip").html('请选择内容！');
		return false;
	}else{
		$("#"+id+"_tip").html('');	
		return true;
	}
}

function checkContent(id){	
	var val = document.getElementById(id).value;
	//alert(val);
	var reg	= new RegExp(/^.{2,1000}$/);
	if(!reg.test(val)){
		$("#"+id+"_tip").html('输入框内请输入正确的内容！');
		return false;
	}else{
		$("#"+id+"_tip").html('');	
		return true;
	}
}

function checkradio(id,span){
	var val = $("input[name='"+id+"']:checked").val();
	//var val = $("input:radio:checked").val();
	//alert(val);
	if(val == null){
		$("#"+span+"_tip").html('请选择答案！');
		return false;
	}else{
		$("#"+span+"_tip").html('');	
		return true;
	}
}

function checkquestion(){
	var daan = $("input[name='daan']:checked").val();
	var result = $("#options"+daan).val();
	var reg	= new RegExp(/^.{2,1000}$/);
	if(!reg.test(result)){
		$("#options_tip").html('输入正确答案对应的内容！');
		return false;
	}else{
		$("#options_tip").html('');	
		return true;
	}
}

function checkCheckBox(id){
	if($(":input:checkbox:checked").length == 0){
		$("#"+id+"_tip").html('请选择内容！');
		return false;
	}else{
		$("#"+id+"_tip").html('');	
		return true;	
	}	
}

//判断是否能删除
var checkdelete = function(){
	
		return confirm('确定删除该条记录？')
	
}

var attindex = 2;
function additem(type,oldnum){
    if(oldnum+1>attindex) attindex=oldnum+1;
	if(type=='radio'){
	    var str = "<div id=\"input"+attindex+"\"><textarea name=\"options[]\" rows=\"2\"  id=\"options"+attindex+"\" cols=\"30\"></textarea> <input type=\"radio\" name=\"daan\" value=\""+attindex+"\">正确答案 <a href=\"javascript:void(0);\" onclick='deleteitem(\"input"+attindex+"\")'\">删除选项</a><span class=\"tips\" id=\"options"+attindex+"_tip\"></span></div>"
	}else{
	    var str = "<div id=\"input"+attindex+"\"><textarea name=\"options[]\" rows=\"2\"  id=\"options"+attindex+"\" cols=\"30\"></textarea> <input type=\"checkbox\" name=\"daan[]\" value=\""+attindex+"\">正确答案 <a href=\"javascript:void(0);\" onclick='deleteitem(\"input"+attindex+"\")'\">删除选项</a><span class=\"tips\" id=\"options"+attindex+"_tip\"></span></div>"
	}
	
	$("#attachment").append(str);
	attindex ++;
}


var deleteitem = function(id){
	$("#"+id).remove(); 
}


var trnum = 1;
//复制选项
var addstrategy = function(i){
	$('#tradd'+i).clone(true).appendTo('#tableadd'+i);
	trnum ++;
	getscore(i);
	getnum(i);
}
//删除选项
var deletestrategy = function(obj,i){
	var len = $("#templatefd"+i+" input[name*='num']").length;
	if(len < 2){
		alert('至少保留一个组卷策略方案');
		return
	}
	
	$(obj).parent("td").parent("tr").remove();
	trnum --;
	getscore(i);
	getnum(i);
}
//得到某块知识点分数
var getscore = function(it){
	//console.log(i);
	var len = $("#templatefd"+it+" input[name*='score']").length;
	//console.log(len);
	var score = totalscore  = singlescore = num = 0;
	for(var i=0;i<len;i++){
    	score = $.trim($("#templatefd"+it+" input[name*='score']").eq(i).val());
    	num = $.trim($("#templatefd"+it+" input[name*='num']").eq(i).val());
    	singlescore = score * num;
    	totalscore += Number(singlescore);
    	//console.log("#templatefd"+it+" input[name='score[]']");
	}
	totalscore = Math.ceil(totalscore);
	$("#sptotalscore"+it).html(totalscore);
	getTotalscore();
}

//得到某块知识点题数
var getnum = function(it){
	var len = $("#templatefd"+it+" input[name*='num']").length;
	var num = totalnum  = 0;
	var score = totalscore  = singlescore = 0;
	for(var i=0;i<len;i++){
		score = $.trim($("#templatefd"+it+" input[name*='score']").eq(i).val());
    	num = $.trim($("#templatefd"+it+" input[name*='num']").eq(i).val());
    	totalnum += Number(num);
    	singlescore = score * num;
    	totalscore += Number(singlescore);
	}	
	totalnum = Math.ceil(totalnum);
	$("#sptotalnum"+it).html(totalnum);
	totalscore = Math.ceil(totalscore);
	$("#sptotalscore"+it).html(totalscore);
	getTotalnum();
}

//得到模板试卷总分
var getTotalscore = function(){
	//console.log(i);
	var len = $("input[name*='score']").length;
	//console.log(len);
	var Tscore = Ttotalscore  = Tsinglescore = Tnum = 0;
	for(var i=0;i<len;i++){
    	Tscore = $.trim($("input[name*='score']").eq(i).val());
    	Tnum = $.trim($("input[name*='num']").eq(i).val());
    	Tsinglescore = Tscore * Tnum;
    	Ttotalscore += Number(Tsinglescore);
    	//console.log("#templatefd"+it+" input[name='score[]']");
	}
	Ttotalscore = Math.ceil(Ttotalscore);
	$("#tsptotalscore").html(Ttotalscore);
}

//得到模板试卷总题数
var getTotalnum = function(){
	var len = $("input[name*='num']").length;
	var Tnum = Ttotalnum  = 0;
	var Tscore = Ttotalscore  = Tsinglescore = 0;
	for(var i=0;i<len;i++){
		Tscore = $.trim($("input[name*='score']").eq(i).val());
    	Tnum = $.trim($("input[name*='num']").eq(i).val());
    	Ttotalnum += Number(Tnum);
    	Tsinglescore = Tscore * Tnum;
    	Ttotalscore += Number(Tsinglescore);
	}	
	Ttotalnum = Math.ceil(Ttotalnum);
	$("#tsptotalnum").html(Ttotalnum);
	Ttotalscore = Math.ceil(Ttotalscore);
	$("#tsptotalscore").html(Ttotalscore);
}

//显示指定题型的试题
var showtype = function(id){
	
}

//复制到剪贴板
var getclip = function(id){
	var ab = $("#clip"+id).val();
	if (document.all){                                            //判断Ie
		window.clipboardData.setData('text', ab);
		alert("复制成功，立即开始营销挣钱！");
	}
}


$(document).ready(function(){
	$.antype(); //做题定位
	$("#singlepage").click(function(){
		if(!checkselect('course_id')){
			return false;
		}
		if(!checkContent('question')){
			return false;
		}
		if(!checkradio('daan','options')){
			return false;
		}
		if(!checkquestion()){
			return false;
		}
	})
	
	//策略添加
	$("#strategy").click(function(){
		if(!checktext('name')){
			return false;
		}
		var len = $("input[name='num[]']").length;
		//alert(len);
		for(var i=0;i<len;i++){
			var examtype = $("#tableadd select").eq(i).val();
			//alert(examtype);
    	    var num = $.trim($("input[name='num[]']").eq(i).val());
    	    var score = $.trim($("input[name='score[]']").eq(i).val());
    	    var regnum	= new RegExp(/^([0-9]){1,3}$/);
    	    var regscore	= new RegExp(/^([0-9][\.]*){1,3}$/);
    	    if(!examtype || !regnum.test(num) || !regscore.test(score)){
    	        //alert('aaab');
    	        $("#strategy_tip").html('请填写正确的方案');	
    			return false;
    	    }
		}
	})
	/**
	//模板试卷\方案添加
	$("#strategy1").click(function(){
		var flag = true;
		if(!checktext('name')){
			flag = false;
		}
		if(!checkselect('course_id')){
			flag = false;
		}
		var len = 0;
		len = $("input[name^='num']").length;
		//var len = $("input[name*='score']").length;
		//alert(len);
		if(len == 1){
			$("#strategy_tip").html('请选择知识模块!');	
			flag = false;
		}
		 $("#strategy_tip").html('');	
		var totalscore = singlescore = 0;
		var arrscore = Array();
		for(var i=1;i<len;i++){
			//var examtype = $("#tableadd select").eq(i).val();
			var examtype = $.trim($("select[name^='examtype']").eq(i).val());
			//alert(examtype);
			var num = parseInt($.trim($("input[name^='num']").eq(i).val()));
			var ts = parseInt($.trim($("input[name^='ts']").eq(i).val()));
			var score = $.trim($("input[name^='score']").eq(i).val());
			singlescore = score * num;
			totalscore += Number(singlescore);
			//console.log(num+'-'+score+'-'+examtype);
			var regnum	= new RegExp(/^([0-9]){1,3}$/);
			var regscore	= new RegExp(/^([0-9][\.]*){1,3}$/);
			if(!examtype || !regnum.test(num) || !regscore.test(score)){
			    //$("#strategy_tip").html('请填写正确的方案!');	
			    $("span[name^='strategy']").eq(i).html('请填写正确的方案!');
				    flag = false;
			}else{
			    if(num > ts)
			    {
				    $("span[name^='strategy']").eq(i).html('您输入的题目数量已经超过了题库题数!!');
					    flag = false;
			    }
    	    	
			    $("span[name^='strategy']").eq(i).html('');
			}
			//判断各知识点同一题型的分数是否一致
			       
			//console.log(arrscore[examtype]);
			if(typeof(arrscore[examtype]) == 'undefined'){
			    arrscore[examtype] = score;
			}else{
			    if(arrscore[examtype] !== score){
				    $("span[name^='strategy']").eq(i).html('与上一知识点的该题型每题分值不相同!!');
					    flag = false;
			    }
			}
			    
			//arrscore[examtype] = score;
			//arrscore[i] = score;
		}
		if(totalscore!=100){
			$("#totalscore_tip").html('试卷的总分必须为100分');
			flag = false;
		}else{
			$("#totalscore_tip").html('');
			flag = false;
		}
	

	})
	 */
	//编辑模板试卷
	$("#strategy2").click(function(){
		if(!checktext('name')){
			return false;
		}else{
		    $('.fsimple').submit();
		}
	})
	
	//学生试题生成
	$("#examstu").click(function(){ 
		if(!checkselect('project')){
			return false;
		}
		
	})
	
	//模板试题显示方式
	$('.ks_zhong div:first').addClass('cur');
	$('.shijuantitle input:first').addClass('inputOver');
	
    $('.shijuantitle input').click(function(){
        $('.shijuantitle input').each(function() {
            $(this).removeClass('inputOver');
        });
        var typeid = $(this).attr('title');
        $('.ks_zhong div').removeClass('cur');
        $("#tbody"+typeid).addClass('cur');
        $(this).addClass('inputOver');
    })
    
    //学生试题导出
	$("#examexport").click(function(){ 
		if(!checkselect('project')){
			return false;
		}
		if(!checkselect('site')){
			return false;
		}
		if(!checkCheckBox('rooms')){
			return false;	
		}
	})
	
	//生成模板试卷：点击选择知识模块
	$.selectknow();
			    
})

jQuery.extend({
	selectknow:function(){
		$("#knowbody span").click(function(){
			var i = $(this).attr('i');
			var m = $(this).attr('m');
			var name = $(this).html();
			if(m == 'unselected'){
				$.addfieldset(i,name);
				$(this).attr('m','selected');
			}else{
				$.delfieldset(i);
				$(this).attr('m','unselected');
			}
			//改变样式
			$.antypecss(i,0); 
			//显示模块方案
			
		});
	},
	
	/**
	 * 添加样式
	 */
	antypecss:function(i,n){
		//alert('aaa');
		var css="";
		if(n==0)
			css="a";
		else if(n==1)
			css="t";
		else if(n==2)
			css="f";
		//$("#antype"+i).removeClass();
		$("#spanknow"+i).toggleClass(css);
	},
	/**
	 * 删除样式
	 */
	deltypecss:function(i,n){
		//$("#antype"+i).removeClass();
		var css="";
		if(n==0)
			css="a";
		else if(n==1)
			css="t";
		else if(n==2)
			css="f";
		$("#antype"+i).removeClass(css);
	},
	//复制方案内容
	addfieldset:function(i,name){
		//$('#templatefd').clone(true).appendTo('#tdfieldset').removeClass('templatefd');
		//$('#templatefd').clone(true).appendTo('#tdfieldset').removeClass('templatefd');
		var newfieldset = $('#templatediv').clone(true);
		var newfieldsetstr = $(newfieldset).html()
		//console.log($(newfieldset).html());
		//newfieldset = '{id}aaa';
		var str = newfieldsetstr.replace(/{id}/g,i);
		str = str.replace(/{name}/,name);
		//$("legend").html(i);
		//console.log($("legend").html());
		$(str).appendTo('#tdfieldset');
		//$(str).appendTo('#tdfieldset').removeClass('templatefd');
	},
	//删除方案内容
	delfieldset:function(i){
		//$('#templatefd').clone(true).appendTo('#tdfieldset').removeClass('templatefd');
		//$(obj).parent("td").parent("tr").remove();
		$("#templatefd"+i).remove();
	},
	
	
	antype:function(){

		/*
		$("#exambody input:radio").click(function(){
			var name = $(this).attr('name');
			var i = name.substring(8);
			var val=$(this).parent().parent().find("input:checked").val();
			if(val)
				$.antypecss(i,0);
		});
		*/
		$("#exambody input:radio").click(function(){$.onantypecss(1,this);});		//单选+判断	
		$("#exambody input:checkbox").click(function(){$.onantypecss(2,this);});    //多选
		//$("#exambody input:text").change(function(){$.onantypecss(3,this);});
		$("#exambody textarea").change(function(){$.onantypecss(4,this);});			//填空+简答
		//$("#exambody input:file").change(function(){$.onantypecss(5,this);});
		//$("#exambody input:button").click(function(){$.onantypecss(5,this);});
		//$("span[name*='span']").click(function(){alert(this.attr('name'));});

	},
	
	onantypecss:function(n,obj){
		var name = $(obj).attr('name');
		var i = name.substring(8);	
		if(n==1){
			var val=$(obj).parent().parent().find("input:checked").val();
			
			$.answer(i,val);
			//$.jstorageSet(i,val,1);
			//浏览器是否支持html5
			//if(window.localStorage) $.storage(i,val);
		}
		
		if(n==2){
			var val=$(obj).parent().parent().find("input:checked");
			var arrval = new Array();
			var m = 0;
			$("input[name='"+name+"']:checked").each(function(){		
				arrval[m] = $(this).val();
				m++;
			});
			//console.log(name);
			//var strval = arrval.join(',');
			//var strval = arrval.toString();
			var strval = arrval.join('==*,*==');
			$.answer(i,strval);
			//$.jstorageSet(i,arrval,1);
			
		}
		if(n==3){
			var val=$(obj).parent().parent().find("input:text");
			$.answer(i,val);
			//$.jstorageSet(i,val,1);
			
		}
		if(n==4){
			var val=$(obj).parent().parent().find("textarea").val();
			$.answer(i,val);
			//$.jstorageSet(i,val,1);
			
		}
		//操作题以swfopload方式操作，不需要在此处再处理
		/*
		if(n==5){
			//var val=$(obj).parent().parent().find("input:file");
			var val = $("input[name='question"+i+"']").val();
			//var val = $("#question3441").val();
			//alert(val);
			if(val!=''){
				//$.fileupload(i);
				$.antypecss(i,0);
			}
		}
		*/
	},
	
	removeknow:function(obj,val){
		
		var examtype = $("select[name^='examtype']").eq(1).val();
		var oldcourse_id = $("#oldcourse_id").val();
		
		if(oldcourse_id == ''){
			oldcourse_id = val;
			$("#oldcourse_id").val(val);
			//alert(oldcourse_id);
		}
		
		if(typeof(examtype) != 'undefined'){
			if(confirm("更改科目将清除下面的设置，确定要更改吗？")){
				$("fieldset[id^='templatefd']:gt(0)").remove();
				//$("span[m='selected']").attr('m','unselected');
				$("span[id^='spanknow']").attr('m','unselected');
				$("span[id^='spanknow']").removeClass('a');
				$("#oldcourse_id").val(val);
				$("#tsptotalnum").html(0);
				$("#tsptotalscore").html(0);
				return true;
			}else{
				//console.log(course_id);
				//$("select[name='course_id']").val(course_id);
				//$(obj).get(0).selectedIndex=0;
				//$(obj).attr('value',val);
				//$(obj).get(0).value = val;
				$(obj).val(oldcourse_id);
				return false;
				//alert(val);
			}
		}
		return true;
	}
	
})

