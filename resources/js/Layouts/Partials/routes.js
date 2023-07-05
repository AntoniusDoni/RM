import {
    HiChartPie,
    HiUser,
    HiCollection,
    HiAdjustments,
    HiPlusCircle,
    HiCurrencyDollar,
    HiCash,
    HiClipboardList,
    HiHashtag,
    HiUsers,
    HiUserGroup,
    HiUserCircle,
    HiOutlineTruck,
    HiDatabase,
    HiShoppingBag,
    HiReceiptTax,
    HiHome,
    HiInboxIn,
    HiOutlineCash,
    HiOutlineTable,
} from 'react-icons/hi'

export default [
    {
        name: 'Dashboard',
        show: true,
        icon: HiChartPie,
        route: route('dashboard'),
        active: 'dashboard',
        permission: 'view-dashboard',
    },
    {
        name: 'Peminjaman',
        show: true,
        icon: HiOutlineCash,
        route: route('peminjaman.index'),
        active: 'peminjaman',
        permission: 'view-peminjaman',
    },
    {
        name: 'Pengembalian',
        show: true,
        icon: HiInboxIn,
        route: route('pengembalian.index'),
        active: 'pengembalian',
        permission: 'view-pengembalian',
    },
    {
        name: 'Riwayat Berkas',
        show: true,
        icon: HiCollection,
        route: route('riwayat.index'),
        active: 'riwayat',
        permission: 'view-riwayat',
    },
    {
        name:'Master Data',
        show:true,
        icon:HiDatabase,
        items:[
            {
                name:'Pasien',
                show:true,
                icon:HiUserGroup,
                route:route('pasien.index'),
                active:'pasien.*',
                permission:'view-pasien'
            },
            {
                name:'Ruang',
                show:true,
                icon:HiPlusCircle,
                route:route('ruang.index'),
                active:'ruang.*',
                permission:'view-ruang'
            },
            {
                name:'Petugas',
                show:true,
                icon:HiUser,
                route:route('petugas.index'),
                active:'petugas.*',
                permission:'view-petugas'
            },
        ],
    },
    {
        name: 'User',
        show: true,
        icon: HiUser,
        items: [
            {
                name: 'Roles',
                show: true,
                icon: HiUserGroup,
                route: route('roles.index'),
                active: 'roles.*',
                permission: 'view-role',
            },
            {
                name: 'Users',
                show: true,
                icon: HiUsers,
                route: route('user.index'),
                active: 'user.index',
                permission: 'view-user',
            },
        ],
    },
    // {
    //     name: 'Setting',
    //     show: true,
    //     icon: HiChartPie,
    //     route: route('setting.index'),
    //     active: 'setting.index',
    //     permission: 'view-setting',
    // },
]