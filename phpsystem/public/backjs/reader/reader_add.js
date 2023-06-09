$(function () {
	$("#reader_readerNo").validatebox({
		required : true, 
		missingMessage : '请输入读者编号',
	});

	$("#reader_readerTypeObj_readerTypeId").combobox({
	    url: backURL + getVisitPath("ReaderType") + '/listAll',
	    valueField: "readerTypeId",
	    textField: "readerTypeName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#reader_readerTypeObj_readerTypeId").combobox("getData"); 
            if (data.length > 0) {
                $("#reader_readerTypeObj_readerTypeId").combobox("select", data[0].readerTypeId);
            }
        }
	});
	$("#reader_readerName").validatebox({
		required : true, 
		missingMessage : '请输入姓名',
	});

	$("#reader_sex").validatebox({
		required : true, 
		missingMessage : '请输入性别',
	});

	$("#reader_birthday").datebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	//单击添加按钮
	$("#readerAddButton").click(function () {
		//验证表单 
		if(!$("#readerAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#readerAddForm").form({
			    url: backURL + getVisitPath("Reader") + "/add",
			    onSubmit: function(){
					if($("#readerAddForm").form("validate"))  { 
	                	$.messager.progress({
							text : "正在提交数据中...",
						}); 
	                	return true;
	                } else {
	                    return false;
	                }
			    },
			    success:function(data){
			    	$.messager.progress("close");
                    //此处data={"Success":true}是字符串
                	var obj = jQuery.parseJSON(data); 
                    if(obj.success){ 
                        $.messager.alert("消息","保存成功！");
                        $(".messager-window").css("z-index",10000);
                        $("#readerAddForm").form("clear");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#readerAddForm").submit();
		}
	});

	//单击清空按钮
	$("#readerClearButton").click(function () { 
		$("#readerAddForm").form("clear"); 
	});
});
