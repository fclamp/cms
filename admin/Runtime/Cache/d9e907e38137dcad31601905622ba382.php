<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=7" /><link href="__ROOT__/statics/admin/css/style.css" rel="stylesheet" type="text/css"/><link href="__ROOT__/statics/css/dialog.css" rel="stylesheet" type="text/css" /><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery-1.4.2.min.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidator.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidatorregex.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/admin/js/admin_common.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/dialog.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/iColorPicker.js"></script><script language="javascript">var URL = '__URL__';
var ROOT_PATH = '__ROOT__';
var APP	 =	 '__APP__';
var lang_please_select = "<?php echo (L("please_select")); ?>";
var def=<?php echo ($def); ?>;
$(function($){
	$("#ajax_loading").ajaxStart(function(){
		$(this).show();
	}).ajaxSuccess(function(){
		$(this).hide();
	});
});

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading" style="width: 96%;text-align: center;">您的请求正在提交中，请稍候...</div><?php if($show_header != false):  if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif;  endif; ?><div class="pad-10" ><form name="searchform" action="" method="get" ><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col"><?php if(is_access('article','add') == '1'): ?><a class="cat" href="javascript:add()" style="color:#fff;">添加新文章</a><?php endif;  echo L('time');?>：         
				<link rel="stylesheet" type="text/css" href="__ROOT__/statics/js/calendar/calendar-blue.css"/><script type="text/javascript" src="__ROOT__/statics/js/calendar/calendar.js"></script><input class="date input-text" type="text" name="s_t" id="s_t" size="18" value="<?php echo ($s_t); ?>" /><script language="javascript" type="text/javascript">	                    Calendar.setup({
	                        inputField     :    "s_t",
	                        ifFormat       :    "%Y-%m-%d",
	                        showsTime      :    "true",
	                        timeFormat     :    "24"
	                    });
	     </script>                -      
				
		<input class="date input-text" type="text" name="e_t" id="e_t" size="18" value="<?php echo ($e_t); ?>" /><script language="javascript" type="text/javascript">	                    Calendar.setup({
	                        inputField     :    "e_t",
	                        ifFormat       :    "%Y-%m-%d",
	                        showsTime      :    "true",
	                        timeFormat     :    "24"
	                    });
	     </script><select name="c_id"><option>--请选择栏目--</option><?php echo tree_select_option($category_list,$c_id);?></select><select name="status"><?php if(is_array($status_types)): $i = 0; $__LIST__ = $status_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == $post_status): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><select name="keyword_type"><option>--关键字类别--</option><?php if(is_array($keyword_types)): $i = 0; $__LIST__ = $keyword_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == $keyword_type): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>             	 关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" /><input type="hidden" name="m" value="article" /><input type="submit" name="search" class="button" value="搜索" /></div></td></tr></tbody></table></form><form id="myform" name="myform" action="<?php echo u('article/delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width=50>ID<input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width=260>标题</th><th width=100>栏目</th><th width=100>发布时间</th><th width=100>发布人</th><th width=60><?php echo L('sort');?></th><th width="100"><?php echo L('status');?></th><th width="80">操作</th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td align="left"><?php echo ($val["title"]);  if($val['attachment']): ?><br/>(<a href="<?php echo ($val['attachment']); ?>" style="color:red;">下载附件</a>)<?php endif; ?></td><td align="center"><?php echo ($val["catid"]); ?></td><td align="left"><?php echo ($val["push_time"]); ?></td><td align="left"><?php echo ($val["author"]); ?></td><td align="center"><input  type="text" class="input-text-c input-text" value="<?php echo ($val["sort"]); ?>" id="sort_<?php echo ($val["id"]); ?>" onblur="sort(<?php echo ($val["id"]); ?>,'sort',this.value)" size="4" name="listorders[<?php echo ($val["id"]); ?>]"></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>" title="改变状态"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" />      			(<span class="article_status_<?php echo ($val['id']); ?>"><?php echo ($status_types[$val['status']]); ?></span>)
      		</td><td align="center"><a class="blue" href="<?php echo ($val['view_url']); ?>" target="_blank">查看</a><?php if(is_access('article','edit') == '1'): ?>&nbsp;|&nbsp;<a class="blue" href="javascript:edit(<?php echo ($val["id"]); ?>,'<?php echo ($val["title"]); ?>')">编辑</a><?php endif; ?></td><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box" style="float:left;"><?php echo (L("select_all")); ?>/<?php echo (L("cancel")); ?></label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/><div id="pages"><?php echo ($page); ?></div></div></div></form></div><script language="javascript">function add() {
	var lang_add = "添加新文章";
	window.top.art.dialog({id:'add'}).close();
	window.top.art.dialog({
		title:lang_add,
		id:'add',
		iframe:'?m=article&a=add',width:'800',height:'480'
		}, 
		function()
		{
			var d = window.top.art.dialog({id:'add'}).data.iframe;
			if(d.document.getElementById('dosubmit')){
				var form=d.document.getElementById('dosubmit');
				form.click();return false;
			}else{
				window.top.art.dialog({id:'add'}).close()
			}
		}, 
		function()
		{
			window.top.art.dialog({id:'add'}).close()
		});

}


function edit(id, name) {
	var lang_edit = "编辑文章";
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({
		title:lang_edit+'--'+name,
		id:'edit',
		iframe:'?m=article&a=edit&id='+id,
		width:'800',
		height:'480'}, 
		function(){
			var d = window.top.art.dialog({id:'edit'}).data.iframe;
			d.document.getElementById('dosubmit').click();
			return false;
			}, 
			function(){
				window.top.art.dialog({id:'edit'}).close()
				}
			);
}


var lang_cate_name = "资讯！";
function check(){
	if($("#myform").attr('action') == '<?php echo u("article/delete");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});

		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_cate_name,lock:true,width:'200',height:'50',time:3},function(){});
			return false;
		}
	}
	return true;
}

function status(id,type){
    $.get("<?php echo u('article/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif');
		var s=['草稿','已发布']
		$('.article_status_'+id).text(s[return_data.data]);
		
		
	}); 
}
//排序方法
function sort(id,type,num){
    
    $.get("<?php echo u('article/sort');?>", { id: id, type: type,num:num }, function(jsondata){
        
		$("#"+type+"_"+id+" ").attr('value', jsondata.data);
	},'json'); 
}
</script></body></html>