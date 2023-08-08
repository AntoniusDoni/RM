import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";

import { isEmpty } from "lodash";
import FormInputDate from "@/Components/FormInputDate";

export default function FormModal(props) {
    const { modalState } = props;
    const { data, setData, post, put, processing, errors, reset, clearErrors } =
        useForm({
            norm: "",
            nama: "",
            tempat_lahir: "",
            tgl_lahir: "",
            jk: "Laki-Laki",
            alamat: "",
            phone: "",

        });

    const handleOnChange = (event) => {
        setData(
            event.target.name,
            event.target.type === "checkbox"
                ? event.target.checked
                    ? 1
                    : 0
                : event.target.value
        );
    };

    const handleReset = () => {
        modalState.setData(null);
        reset();
        clearErrors();
    };

    const handleClose = () => {
        handleReset();
        modalState.toggle();
    };

    const handleSubmit = () => {
        const pasien = modalState.data;
        if (pasien !== null) {
            put(route("pasien.update", pasien), {
                onSuccess: () => handleClose(),
            });
            return;
        }
        post(route("pasien.store"), {
            onSuccess: () => handleClose(),
        });
    };

    useEffect(() => {
        const pasien = modalState.data;
        if (isEmpty(pasien) === false) {
            setData({
                norm: pasien.no_rm,
                nama: pasien.nama,
                tempat_lahir:pasien.tempat_lahir,
                tgl_lahir: pasien.tgl_lahir,
                jk: pasien.jk,
                alamat: pasien.alamat,
                phone: pasien.phone,
            });
            return;
        }
    }, [modalState]);

    return (
        <Modal isOpen={modalState.isOpen} toggle={handleClose} title={"Pasien"}>
            <FormInput
                name="norm"
                value={data.norm}
                onChange={handleOnChange}
                label="NO RM"
                error={errors.norm}
            />
            <FormInput
                name="nama"
                value={data.nama}
                onChange={handleOnChange}
                label="Nama"
                error={errors.nama}
            />
            <FormInput
                name="tempat_lahir"
                selected={data.tempat_lahir}
                onChange={handleOnChange}
                label="Tempat Lahir"
                error={errors.tempat_lahir}
            />
            <FormInputDate
                name="tgl_lahir"
                selected={data.tgl_lahir}
                onChange={(date) => setData("tgl_lahir", date)}
                label="Tanggal Lahir"
                error={errors.tgl_lahir}
            />
             <div className="flex-auto px-2">
                                <label className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Jenis Kelamin
                                </label>
                                <select
                                    className="mb-2 bg-gray-50 border  text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white"
                                    name="jk"
                                    onChange={handleOnChange}
                                    value={data.jk}
                                >
                                    <option value={""}>
                                        -- Pilih Jenis Kelamin --
                                    </option>
                                  <option value={'Laki-Laki'}>Laki-Laki</option>
                                  <option value={'Wanita'}>Wanita</option>
                                </select>
                                {errors.detail_month && (
                                    <p className="mb-2 text-sm text-red-600 dark:text-red-500">
                                        {errors.jk}
                                    </p>
                                )}
                            </div>
            <FormInput
                name="alamat"
                value={data.alamat}
                onChange={handleOnChange}
                label="Alamat"
                error={errors.alamat}
            />
            <FormInput
                name="phone"
                value={data.phone}
                onChange={handleOnChange}
                label="No Telephone"
                error={errors.phone}
            />



            <div className="flex items-center">
                <Button onClick={handleSubmit} processing={processing}>
                    Simpan
                </Button>
                <Button onClick={handleClose} type="secondary">
                    Batal
                </Button>
            </div>
        </Modal>
    );
}
