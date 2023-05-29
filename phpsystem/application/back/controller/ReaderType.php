<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\ReaderTypeModel;

class ReaderType extends BackBase {
    protected $readerTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->readerTypeModel = new ReaderTypeModel();
    }

    /*添加读者类型信息*/
    public function add(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $readerType = $this->getReaderTypeForm(true);
            try {
                $this->readerTypeModel->addReaderType($readerType);
                $message = "读者类型添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "读者类型添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return view('readerType/readerType_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("readerTypeId",input("readerTypeId"));
        return view("readerType/readerType_modify");
    }

    /*ajax方式按照查询条件分页查询读者类型信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->readerTypeModel->setRows($this->request->param("rows"));
            $readerTypeRs = $this->readerTypeModel->queryReaderType($this->currentPage);
            $expTableData = [];
            foreach($readerTypeRs as $readerTypeRow) {
                $expTableData[] = $readerTypeRow;
            }
            $data["rows"] = $readerTypeRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->readerTypeModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("readerType/readerType_query");
        }
    }

    /*ajax方式查询读者类型信息*/
    public function listAll() {
        $readerTypeRs = $this->readerTypeModel->queryAllReaderType();
        echo json_encode($readerTypeRs);
    }
    /*更新读者类型信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $readerType = $this->getReaderTypeForm(false);
            try {
                $this->readerTypeModel->updateReaderType($readerType);
                $message = "读者类型更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "读者类型更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取读者类型对象*/
            $readerTypeId = input("readerTypeId");
            $readerType = $this->readerTypeModel->getReaderType($readerTypeId);
            echo json_encode($readerType);
        }
    }

    /*删除多条读者类型记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $readerTypeIds = input("readerTypeIds");
        try {
            $count = $this->readerTypeModel->deleteReaderTypes($readerTypeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

    /*按照查询条件导出读者类型信息到Excel*/
    public function outToExcel() {
        $readerTypeRs = $this->readerTypeModel->queryOutputReaderType();
        $expTableData = [];
        foreach($readerTypeRs as $readerTypeRow) {
            $expTableData[] = $readerTypeRow;
        }
        $xlsName = "ReaderType";
        $xlsCell = array(
            array('readerTypeId','读者类型编号','int'),
            array('readerTypeName','读者类型','string'),
            array('number','可借阅数目','int'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

