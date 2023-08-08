import React, { useEffect, useState } from "react";
import { router } from "@inertiajs/react";
import { usePrevious } from "react-use";
import { Head } from "@inertiajs/react";
import { Button, Dropdown } from "flowbite-react";
import { HiPencil, HiTrash } from "react-icons/hi";
import { useModalState } from "@/hooks";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Pagination from "@/Components/Pagination";
import FormModal from "./FormModalPengembalian";
import SearchInput from "@/Components/SearchInput";

import { dateToStringDB, hasPermission } from "@/utils";
import FormInputDate from "@/Components/FormInputDate";
import moment from "moment";

export default function Pengembalian(props) {

    const {
        query: { links, data },
        auth,
    } = props;
    const curdate = moment().format("DD-MM-YYYY");
    const [search, setSearch] = useState("");
    const preValue = usePrevious(search);
    const [dateStart, setDateStart] = useState("");
    const [dateEnd, setDateEnd] = useState("");
    const confirmModal = useModalState();
    const formModal = useModalState();

    const toggleFormModal = (pengembalian = null) => {
        formModal.setData(pengembalian);
        formModal.toggle();
    };
    const searchDate = () => {
        router.get(
            route(route().current()),
            {
                q: search,
                datestart: dateToStringDB(dateStart),
                dateend: dateToStringDB(dateEnd),
            },
            {
                replace: true,
                preserveState: true,
            }
        );
    };

    const params = { q: search };
    const paramsdate = { q: search , 
        datestart:dateStart!=""?dateToStringDB(dateStart):"",
        dateend: dateEnd!=""?dateToStringDB(dateEnd):"", };
    useEffect(() => {
        if (preValue) {
            router.get(
                route(route().current()),
                { q: search },
                {
                    replace: true,
                    preserveState: true,
                }
            );
        }
    }, [search]);

    // const canCreate = hasPermission(auth, "view-peminjaman");
    const canCreate = hasPermission(auth, 'create-peminjaman')
    const canUpdate = hasPermission(auth, 'update-peminjaman')
    const canDelete = hasPermission(auth, 'delete-peminjaman')

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={"Dashboard"}
            action={"Pengembalian Berkas RM"}
        >
            <Head title="Pengembalian Berkas RM" />
            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-gray-200 dark:bg-gray-800 space-y-4">
                        <div className="flex justify-between">

                            <div className="flex items-end">
                            <div className="flex -mx-2">
                                    <div className="w-1/2 px-2 py-6">
                                        <SearchInput
                                            onChange={(e) =>
                                                setSearch(e.target.value)
                                            }
                                            value={search}
                                        />
                                    </div>
                                    <div className="w-1/3 px-2">
                                        <FormInputDate
                                            name="dateStart"
                                            selected={dateStart}
                                            onChange={(date) =>
                                                setDateStart(date)
                                            }
                                            label="Tanggal Mulai"
                                            // error={dateStart}
                                        />
                                    </div>
                                    <div className="w-1/3 px-2">
                                        <FormInputDate
                                            name="dateEnd"
                                            selected={dateEnd}
                                            value={dateEnd}
                                            onChange={(date) =>
                                                setDateEnd(date)
                                            }
                                            label="Tanggal Akhir"
                                            // error={dateStart}
                                        />
                                    </div>
                                    <div className="w-1/3 px-2 mt-6">
                                        <Button onClick={searchDate}>
                                            Cari
                                        </Button>
                                    </div>
                                    <div className="w-1/3 mt-6 py-2">
                                    <a
                                                    href={route(
                                                        'pengembalian.export',paramsdate,
                                                    )}
                                                    target="_blank"
                                                    className="text-white cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                                >
                                                    Export Pdf
                                                </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="overflow-auto">
                            <div>
                                <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                                    <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th
                                                scope="col"
                                                className="py-3 px-6"
                                            >
                                                No RM
                                            </th>
                                            <th
                                                scope="col"
                                                className="py-3 px-6"
                                            >
                                                Nama Pasien
                                            </th>
                                            <th
                                                scope="col"
                                                className="py-3 px-6"
                                            >
                                                Ruangan
                                            </th>
                                            <th
                                                scope="col"
                                                className="py-3 px-6"
                                            >
                                                Petugas Pengembali
                                            </th>
                                            <th
                                                scope="col"
                                                className="py-3 px-6"
                                            >
                                                Tanggal Pinjam
                                            </th>
                                            <th
                                                scope="col"
                                                className="py-3 px-6"
                                            />
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {
                                            data.map((peminjam,index)=>(
                                                <tr
                                                className="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                                key={peminjam.id}
                                            >
                                                <td
                                                    scope="row"
                                                    className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                >
                                                    {peminjam?.pasien?.no_rm}
                                                </td>
                                                <td
                                                    scope="row"
                                                    className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                >
                                                    {peminjam?.pasien?.nama}
                                                </td>
                                                <td
                                                    scope="row"
                                                    className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                >
                                                    {peminjam?.petugas_pinjam?.ruang?.nama}
                                                </td>
                                                <td
                                                    scope="row"
                                                    className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                >
                                                    {peminjam?.petugas_pinjam?.kode_petugas} - {peminjam?.petugas_pinjam?.nama}
                                                </td>
                                                <td
                                                    scope="row"
                                                    className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                >
                                                    {peminjam?.tgl_pinjam}
                                                </td>
                                                <td className="py-4 px-6 flex justify-end">
                                                { peminjam.tanggal_kembali==null&&(

                                                    <Dropdown
                                                        label={"Opsi"}
                                                        floatingArrow={true}
                                                        arrowIcon={true}
                                                        dismissOnClick={true}
                                                        size={"sm"}
                                                    >
                                                        {canUpdate && (
                                                            <Dropdown.Item
                                                                onClick={() =>
                                                                    toggleFormModal(
                                                                        peminjam
                                                                    )
                                                                }
                                                            >
                                                                <div className="flex space-x-1 items-center">
                                                                    <HiPencil />
                                                                    <div>
                                                                        Pengembalian Berkas
                                                                    </div>
                                                                </div>
                                                            </Dropdown.Item>
                                                        )}

                                                    </Dropdown>
                                                )}
                                                    
                                                </td>
                                                </tr>
                                            )
                                            )
                                        }
                                    </tbody>
                                </table>
                            </div>
                        <div className="w-full flex items-center justify-center">
                                <Pagination links={links} params={params} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <FormModal modalState={formModal} />
        </AuthenticatedLayout>
    );
}
