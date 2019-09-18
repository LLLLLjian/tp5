<?php

namespace app\index\model;

use think\Model;
use think\Db;

class Roles extends Model
{
    protected $table = "roles";

    // 通过roleslevel获取角色名
    public function getRolesNameBylevel($roleslevel = "", $type = 1)
    {
        if (empty($roleslevel)) {
            $roleslevel = session("roleslevel");
        }

        $rolesInfoArr = $this->where(array("is_valid" => 1))
            ->where('roleslevel', 'exp', " & {$roleslevel} > 0")
            ->order("roleslevel asc")
            ->select()->toArray();

        $tempStr = "";
        if (!empty($rolesInfoArr)) {
            $tempArr = array_column($rolesInfoArr, "rolesname");

            if ($type == 1) {
                $tempStr = implode(",", $tempArr);
            } else {
                $tempStr = $tempArr[0];
            }
        }
        return $tempStr;
    }
}
