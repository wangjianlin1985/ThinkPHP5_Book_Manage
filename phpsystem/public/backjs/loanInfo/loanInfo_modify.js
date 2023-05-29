$(function () {
	$.ajax({
		url :  backURL + getVisitPath("LoanInfo") + "/update",
		type : "get",
		data : {
			loadId : $("#loanInfo_loadId_edit").val(),
		},
		dataType: "json",
		beforeSend : function () {
			$.messager.progress({
				text : "正在获取中...",
			});
		},
		success : function (loanInfo, response, status) {
			$.messager.progress("close");
			if (loanInfo) { 
				$("#loanInfo_loadId_edit").val(loanInfo.loadId);
				$("#loanInfo_loadId_edit").validatebox({
					required : true,
					missingMessage : "请输入借阅编号",
					editable: false
				});
				$("#loanInfo_book_barcode_edit").combobox({
					url: backURL + getVisitPath("Book") + "/listAll",
					valueField:"barcode",
					textField:"bookName",
					panelHeight: "auto",
					editable: false, //不允许手动输入 
					onLoadSuccess: function () { //数据加载完毕事件
						$("#loanInfo_book_barcode_edit").combobox("select", loanInfo.book);
						//var data = $("#loanInfo_book_barcode_edit").combobox("getData"); 
						//if (data.length > 0) {
							//$("#loanInfo_book_barcode_edit").combobox("select", data[0].barcode);
						//}
					}
				});
				$("#loanInfo_reader_readerNo_edit").combobox({
					url: backURL + getVisitPath("Reader") + "/listAll",
					valueField:"readerNo",
					textField:"readerName",
					panelHeight: "auto",
					editable: false, //不允许手动输入 
					onLoadSuccess: function () { //数据加载完毕事件
						$("#loanInfo_reader_readerNo_edit").combobox("select", loanInfo.reader);
						//var data = $("#loanInfo_reader_readerNo_edit").combobox("getData"); 
						//if (data.length > 0) {
							//$("#loanInfo_reader_readerNo_edit").combobox("select", data[0].readerNo);
						//}
					}
				});
				$("#loanInfo_borrowDate_edit").datetimebox({
					value: loanInfo.borrowDate,
					required: true,
					showSeconds: true,
				});
				$("#loanInfo_returnDate_edit").datetimebox({
					value: loanInfo.returnDate,
					required: true,
					showSeconds: true,
				});
			} else {
				$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
				$(".messager-window").css("z-index",10000);
			}
		}
	});

	$("#loanInfoModifyButton").click(function(){ 
		if ($("#loanInfoEditForm").form("validate")) {
			$("#loanInfoEditForm").form({
			    url: backURL + getVisitPath("LoanInfo") + "/update",
			    onSubmit: function(){
					if($("#loanInfoEditForm").form("validate"))  {
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
			$("#loanInfoEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
