$(function () {
	$("#loanInfo_book_barcode").combobox({
	    url: backURL + getVisitPath("Book") + '/listAll',
	    valueField: "barcode",
	    textField: "bookName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#loanInfo_book_barcode").combobox("getData"); 
            if (data.length > 0) {
                $("#loanInfo_book_barcode").combobox("select", data[0].barcode);
            }
        }
	});
	$("#loanInfo_reader_readerNo").combobox({
	    url: backURL + getVisitPath("Reader") + '/listAll',
	    valueField: "readerNo",
	    textField: "readerName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#loanInfo_reader_readerNo").combobox("getData"); 
            if (data.length > 0) {
                $("#loanInfo_reader_readerNo").combobox("select", data[0].readerNo);
            }
        }
	});
	$("#loanInfo_borrowDate").datetimebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	$("#loanInfo_returnDate").datetimebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	//单击添加按钮
	$("#loanInfoAddButton").click(function () {
		//验证表单 
		if(!$("#loanInfoAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#loanInfoAddForm").form({
			    url: backURL + getVisitPath("LoanInfo") + "/add",
			    onSubmit: function(){
					if($("#loanInfoAddForm").form("validate"))  { 
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
                        $("#loanInfoAddForm").form("clear");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#loanInfoAddForm").submit();
		}
	});

	//单击清空按钮
	$("#loanInfoClearButton").click(function () { 
		$("#loanInfoAddForm").form("clear"); 
	});
});
