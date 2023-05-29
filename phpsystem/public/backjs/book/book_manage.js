var book_manage_tool = null; 
$(function () { 
	initBookManageTool(); //建立Book管理对象
	book_manage_tool.init(); //如果需要通过下拉框查询，首先初始化下拉框的值
	$("#book_manage").datagrid({
		url : backURL + getVisitPath("Book") + '/backList',
		fit : true,
		fitColumns : true,
		striped : true,
		rownumbers : true,
		border : false,
		pagination : true,
		pageSize : 5,
		pageList : [5, 10, 15, 20, 25],
		pageNumber : 1,
		sortName : "barcode",
		sortOrder : "desc",
		toolbar : "#book_manage_tool",
		columns : [[
			{
				field : "barcode",
				title : "图书条形码",
				width : 140,
			},
			{
				field : "bookName",
				title : "图书名称",
				width : 140,
			},
			{
				field : "bookTypeObj",
				title : "图书所在类别",
				width : 140,
			},
			{
				field : "price",
				title : "图书价格",
				width : 70,
			},
			{
				field : "count",
				title : "库存",
				width : 70,
			},
			{
				field : "publishDate",
				title : "出版日期",
				width : 140,
			},
			{
				field : "publish",
				title : "出版社",
				width : 140,
			},
			{
				field : "bookPhoto",
				title : "图书图片",
				width : "70px",
				height: "65px",
				formatter: function(val,row) {
					return "<img src='" + publicURL + val + "' width='65px' height='55px' />";
				}
 			},
			{
				field : "bookFile",
				title : "图书文件",
				width : "350px",
				formatter: function(val,row) {
 					if(val == "") return "暂无文件";
					return "<a href='" + publicURL + val + "' target='_blank' style='color:red;'>" + val + "</a>";
				}
 			},
		]],
	});

	$("#bookEditDiv").dialog({
		title : "修改管理",
		top: "10px",
		width : 1000,
		height : 600,
		modal : true,
		closed : true,
		iconCls : "icon-edit-new",
		buttons : [{
			text : "提交",
			iconCls : "icon-edit-new",
			handler : function () {
				if ($("#bookEditForm").form("validate")) {
					//验证表单 
					if(!$("#bookEditForm").form("validate")) {
						$.messager.alert("错误提示","你输入的信息还有错误！","warning");
					} else {
						$("#bookEditForm").form({
						    url: backURL + getVisitPath("Book") + "/update",
						    onSubmit: function(){
								if($("#bookEditForm").form("validate"))  {
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
						    	console.log(data);
			                	var obj = jQuery.parseJSON(data);
			                    if(obj.success){
			                        $.messager.alert("消息","信息修改成功！");
			                        $("#bookEditDiv").dialog("close");
			                        book_manage_tool.reload();
			                    }else{
			                        $.messager.alert("消息",obj.message);
			                    } 
						    }
						});
						//提交表单
						$("#bookEditForm").submit();
					}
				}
			},
		},{
			text : "取消",
			iconCls : "icon-redo",
			handler : function () {
				$("#bookEditDiv").dialog("close");
				$("#bookEditForm").form("reset"); 
			},
		}],
	});
});

function initBookManageTool() {
	book_manage_tool = {
		init: function() {
			$.ajax({
				url : backURL + getVisitPath("BookType") + "/listAll",
				type : "post",
				dataType: "json",
				success : function (data, response, status) {
					$("#bookTypeObj_bookTypeId_query").combobox({ 
					    valueField:"bookTypeId",
					    textField:"bookTypeName",
					    panelHeight: "200px",
				        editable: false, //不允许手动输入 
					});
					data.splice(0,0,{bookTypeId:0,bookTypeName:"不限制"});
					$("#bookTypeObj_bookTypeId_query").combobox("loadData",data); 
				}
			});
		},
		reload : function () {
			$("#book_manage").datagrid("reload");
		},
		redo : function () {
			$("#book_manage").datagrid("unselectAll");
		},
		search: function() {
			var queryParams = $("#book_manage").datagrid("options").queryParams;
			queryParams["barcode"] = $("#barcode").val();
			queryParams["bookName"] = $("#bookName").val();
			queryParams["bookTypeObj.bookTypeId"] = $("#bookTypeObj_bookTypeId_query").combobox("getValue");
			queryParams["publishDate"] = $("#publishDate").datebox("getValue"); 
			$("#book_manage").datagrid("options").queryParams=queryParams; 
			$("#book_manage").datagrid("load");
		},
		exportExcel: function() {
			$("#bookQueryForm").form({
			    url: backURL + getVisitPath("Book") + "/outToExcel",
			});
			//提交表单
			$("#bookQueryForm").submit();
		},
		remove : function () {
			var rows = $("#book_manage").datagrid("getSelections");
			if (rows.length > 0) {
				$.messager.confirm("确定操作", "您正在要删除所选的记录吗？", function (flag) {
					if (flag) {
						var barcodes = [];
						for (var i = 0; i < rows.length; i ++) {
							barcodes.push(rows[i].barcode);
						}
						$.ajax({
							type : "POST",
							url :  backURL + getVisitPath("Book") + "/deletes",
							data : {
								barcodes : barcodes.join(","),
							},
							dataType: "json",
							beforeSend : function () {
								$("#book_manage").datagrid("loading");
							},
							success : function (data) {
								if (data.success) {
									$("#book_manage").datagrid("loaded");
									$("#book_manage").datagrid("load");
									$("#book_manage").datagrid("unselectAll");
									$.messager.show({
										title : "提示",
										msg : data.message
									});
								} else {
									$("#book_manage").datagrid("loaded");
									$("#book_manage").datagrid("load");
									$("#book_manage").datagrid("unselectAll");
									$.messager.alert("消息",data.message);
								}
							},
						});
					}
				});
			} else {
				$.messager.alert("提示", "请选择要删除的记录！", "info");
			}
		},
		edit : function () {
			var rows = $("#book_manage").datagrid("getSelections");
			if (rows.length > 1) {
				$.messager.alert("警告操作！", "编辑记录只能选定一条数据！", "warning");
			} else if (rows.length == 1) {
				$.ajax({
					url : backURL + getVisitPath("Book") + "/update",
					type : "get",
					data : {
						barcode : rows[0].barcode,
					},
					dataType: "json",
					beforeSend : function () {
						$.messager.progress({
							text : "正在获取中...",
						});
					},
					success : function (book, response, status) {
						$.messager.progress("close");
						if (book) { 
							$("#bookEditDiv").dialog("open");
							$("#book_barcode_edit").val(book.barcode);
							$("#book_barcode_edit").validatebox({
								required : true,
								missingMessage : "请输入图书条形码",
								editable: false
							});
							$("#book_bookName_edit").val(book.bookName);
							$("#book_bookName_edit").validatebox({
								required : true,
								missingMessage : "请输入图书名称",
							});
							$("#book_bookTypeObj_bookTypeId_edit").combobox({
							    url: backURL + getVisitPath("BookType") + "/listAll",
							    dataType: "json",
							    valueField:"bookTypeId",
							    textField:"bookTypeName",
							    panelHeight: "auto",
						        editable: false, //不允许手动输入 
						        onLoadSuccess: function () { //数据加载完毕事件
									$("#book_bookTypeObj_bookTypeId_edit").combobox("select", book.bookTypeObj);
									//var data = $("#book_bookTypeObj_bookTypeId_edit").combobox("getData"); 
						            //if (data.length > 0) {
						                //$("#book_bookTypeObj_bookTypeId_edit").combobox("select", data[0].bookTypeId);
						            //}
								}
							});
							$("#book_price_edit").val(book.price);
							$("#book_price_edit").validatebox({
								required : true,
								validType : "number",
								missingMessage : "请输入图书价格",
								invalidMessage : "图书价格输入不对",
							});
							$("#book_count_edit").val(book.count);
							$("#book_count_edit").validatebox({
								required : true,
								validType : "integer",
								missingMessage : "请输入库存",
								invalidMessage : "库存输入不对",
							});
							$("#book_publishDate_edit").datebox({
								value: book.publishDate,
							    required: true,
							    showSeconds: true,
							});
							$("#book_publish_edit").val(book.publish);
							$("#book_bookPhoto").val(book.bookPhoto);
							$("#book_bookPhotoImg").attr("src", publicURL + book.bookPhoto);
							book_bookDesc_editor.setContent(book.bookDesc, false);
							$("#book_bookFile").val(book.bookFile);
							if(book.bookFile == "") $("#book_bookFileA").html("暂无文件");
							else $("#book_bookFileA").html(book.bookFile);
							$("#book_bookFileA").attr("href", publicURL + book.bookFile);
						} else {
							$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
						}
					}
				});
			} else if (rows.length == 0) {
				$.messager.alert("警告操作！", "编辑记录至少选定一条数据！", "warning");
			}
		},
	};
}
