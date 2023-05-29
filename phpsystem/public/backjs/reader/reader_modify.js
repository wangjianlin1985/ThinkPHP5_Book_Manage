$(function () {
	$.ajax({
		url :  backURL + getVisitPath("Reader") + "/update",
		type : "get",
		data : {
			readerNo : $("#reader_readerNo_edit").val(),
		},
		dataType: "json",
		beforeSend : function () {
			$.messager.progress({
				text : "正在获取中...",
			});
		},
		success : function (reader, response, status) {
			$.messager.progress("close");
			if (reader) { 
				$("#reader_readerNo_edit").val(reader.readerNo);
				$("#reader_readerNo_edit").validatebox({
					required : true,
					missingMessage : "请输入读者编号",
					editable: false
				});
				$("#reader_readerTypeObj_readerTypeId_edit").combobox({
					url: backURL + getVisitPath("ReaderType") + "/listAll",
					valueField:"readerTypeId",
					textField:"readerTypeName",
					panelHeight: "auto",
					editable: false, //不允许手动输入 
					onLoadSuccess: function () { //数据加载完毕事件
						$("#reader_readerTypeObj_readerTypeId_edit").combobox("select", reader.readerTypeObj);
						//var data = $("#reader_readerTypeObj_readerTypeId_edit").combobox("getData"); 
						//if (data.length > 0) {
							//$("#reader_readerTypeObj_readerTypeId_edit").combobox("select", data[0].readerTypeId);
						//}
					}
				});
				$("#reader_readerName_edit").val(reader.readerName);
				$("#reader_readerName_edit").validatebox({
					required : true,
					missingMessage : "请输入姓名",
				});
				$("#reader_sex_edit").val(reader.sex);
				$("#reader_sex_edit").validatebox({
					required : true,
					missingMessage : "请输入性别",
				});
				$("#reader_birthday_edit").datebox({
					value: reader.birthday,
					required: true,
					showSeconds: true,
				});
				$("#reader_telephone_edit").val(reader.telephone);
				$("#reader_email_edit").val(reader.email);
				$("#reader_qq_edit").val(reader.qq);
				$("#reader_address_edit").val(reader.address);
				$("#reader_photo").val(reader.photo);
				$("#reader_photoImg").attr("src", publicURL + reader.photo);
			} else {
				$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
				$(".messager-window").css("z-index",10000);
			}
		}
	});

	$("#readerModifyButton").click(function(){ 
		if ($("#readerEditForm").form("validate")) {
			$("#readerEditForm").form({
			    url: backURL + getVisitPath("Reader") + "/update",
			    onSubmit: function(){
					if($("#readerEditForm").form("validate"))  {
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
                	var obj = jQuery.parseJSON(data);
                    if(obj.success){
                        $.messager.alert("消息","信息修改成功！");
                        $(".messager-window").css("z-index",10000);
                        //location.href="frontlist";
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    } 
			    }
			});
			//提交表单
			$("#readerEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
