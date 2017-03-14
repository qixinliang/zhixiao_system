<?php
if (!defined('IS_INITPHP')) exit('Access Denied!');
/**
 * 公司列表管理
 * @author aaron
 */
class companyDao extends Dao{
    
    public $table_name = 'cp_zcompany';
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午4:55:11
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @通过id查询单个公司信息
     *************************************************************/
    public function CompanyInfoOne($id){
        $sql=sprintf("select * from %s where id=%s ",$this->table_name,$id);
        return $this->dao->db->get_one_sql($sql);
    }
   /************************************************************
    * @copyright(c): 2017年2月27日
    * @Author:  yuwen
    * @Create Time: 下午4:59:42
    * @qq:32891873
    * @email:fuyuwen88@126.com
    * 更新公司信息
    *************************************************************/
    public function CompanyInfoUpdate($id,$data){
        return $this->dao->db->update($id, $data, $this->table_name);
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:01:15
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @获取全部公司列表
     *************************************************************/
    public function GetCompanyList(){
        $sql=sprintf("select * from %s  order by id desc",$this->table_name);
        return  $this->dao->db->get_all_sql($sql);
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:05:31
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * 添加公司信息
     *************************************************************/
    public function AddSave($data){
       return $this->dao->db->insert($data, $this->table_name); //操作-插入一条数据
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:07:30
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * 修改公司信息
     *************************************************************/
    public function EditSave($data){
        return $this->dao->db->update_by_field($data, array('id' => $data['id']), $this->table_name); //根据条件更新数据
    }
    /************************************************************
     * @copyright(c): 2017年2月27日
     * @Author:  yuwen
     * @Create Time: 下午5:08:07
     * @qq:32891873
     * @email:fuyuwen88@126.com
     * @删除信息
     *************************************************************/
    public function Del($id){
       return $this->dao->db->delete_by_field(array('id'=>$id), $this->table_name);
    }
}