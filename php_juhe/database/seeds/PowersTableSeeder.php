<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('powers')->insert([
            [
                'powers_name'  => '查看权限管理',
                'powers_mark'  => 'power.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加权限管理',
                'powers_mark'  => 'power.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新权限管理',
                'powers_mark'  => 'power.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除权限管理',
                'powers_mark'  => 'power.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看角色管理',
                'powers_mark'  => 'roles.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加角色管理',
                'powers_mark'  => 'roles.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '编辑角色管理',
                'powers_mark'  => 'roles.edit',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新角色管理',
                'powers_mark'  => 'roles.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除角色管理',
                'powers_mark'  => 'roles.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看管理员管理',
                'powers_mark'  => 'admins.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加管理员管理',
                'powers_mark'  => 'admins.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新管理员管理',
                'powers_mark'  => 'admins.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除管理员管理',
                'powers_mark'  => 'admins.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看系统日志',
                'powers_mark'  => 'syslog.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除系统日志',
                'powers_mark'  => 'syslog.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新系统配置',
                'powers_mark'  => 'sysconfig.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看会员管理',
                'powers_mark'  => 'users.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加会员页面',
                'powers_mark'  => 'users.create',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加会员管理',
                'powers_mark'  => 'users.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看会员编辑',
                'powers_mark'  => 'users.edit',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新会员管理',
                'powers_mark'  => 'users.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除会员管理',
                'powers_mark'  => 'users.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看会员冻结',
                'powers_mark'  => 'users.freeze',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加会员冻结',
                'powers_mark'  => 'users.addfreezes',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看会员费率',
                'powers_mark'  => 'users.rates',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加会员费率',
                'powers_mark'  => 'users.rates.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看接口类型',
                'powers_mark'  => 'apistyle.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加接口类型页面',
                'powers_mark'  => 'apistyle.create',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加接口类型',
                'powers_mark'  => 'apistyle.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '编辑接口类型',
                'powers_mark'  => 'apistyle.edit',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新接口类型',
                'powers_mark'  => 'apistyle.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看接口类型轮询',
                'powers_mark'  => 'apistyle.round',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新接口类型轮询',
                'powers_mark'  => 'apistyle.roundstore',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除接口类型',
                'powers_mark'  => 'apistyle.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看上游类型',
                'powers_mark'  => 'upstyle.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '编辑上游类型',
                'powers_mark'  => 'upstyle.edit',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加上游类型',
                'powers_mark'  => 'upstyle.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新上游类型',
                'powers_mark'  => 'upstyle.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除上游类型',
                'powers_mark'  => 'upstyle.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看上游账户',
                'powers_mark'  => 'upaccount.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加上游账户页面',
                'powers_mark'  => 'upaccount.create',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加上游账户',
                'powers_mark'  => 'upaccount.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '编辑上游账户',
                'powers_mark'  => 'upaccount.edit',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '更新上游账户',
                'powers_mark'  => 'upaccount.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除上游账户',
                'powers_mark'  => 'upaccount.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '上游账户应用',
                'powers_mark'  => 'upaccount.changechoose',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看提现订单',
                'powers_mark'  => 'finances.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '审核提现订单',
                'powers_mark'  => 'finances.update',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除提现订单',
                'powers_mark'  => 'finances.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加提现备注',
                'powers_mark'  => 'finances.memo',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '提现回调通知',
                'powers_mark'  => 'finances.notify',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看资金变动',
                'powers_mark'  => 'finances.moneylog',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看通道统计',
                'powers_mark'  => 'finances.tj',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看提现卡号',
                'powers_mark'  => 'bankcard.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除提现卡号',
                'powers_mark'  => 'bankcard.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看订单列表',
                'powers_mark'  => 'order.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看订单详情',
                'powers_mark'  => 'order.edit',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '订单补单',
                'powers_mark'  => 'order.budan',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '订单通知',
                'powers_mark'  => 'order.notice',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '订单退款',
                'powers_mark'  => 'order.back',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看接口日志',
                'powers_mark'  => 'paylog.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除接口日志',
                'powers_mark'  => 'paylog.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看黑名单管理',
                'powers_mark'  => 'blacklist.index',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '添加黑名单管理',
                'powers_mark'  => 'blacklist.store',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '删除黑名单管理',
                'powers_mark'  => 'blacklist.delete',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '代付订单提示',
                'powers_mark'  => 'admin.checkneworder',
                'powers_sort'  => 0
            ],
            [
                'powers_name'  => '查看审核代付订单',
                'powers_mark'  => 'finances.edit',
                'powers_sort'  => 0
            ]
        ]);
    }
}
