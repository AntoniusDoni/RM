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
            nama: "",
            kode_ruang: "",
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
        const ruang = modalState.data;
        if (ruang !== null) {
            put(route("ruang.update", ruang), {
                onSuccess: () => handleClose(),
            });
            return;
        }
        post(route("ruang.store"), {
            onSuccess: () => handleClose(),
        });
    };

    useEffect(() => {
        const ruang = modalState.data;
        if (isEmpty(ruang) === false) {
            setData({
                nama: ruang.nama,
                kode_ruang: ruang.kode_ruang,
            });
            return;
        }
    }, [modalState]);

    return (
        <Modal isOpen={modalState.isOpen} toggle={handleClose} title={"Ruangan"}>
            <FormInput
                name="kode_ruang"
                value={data.kode_ruang}
                onChange={handleOnChange}
                label="Kode Ruangan"
                error={errors.kode_ruang}
            />
            <FormInput
                name="nama"
                value={data.nama}
                onChange={handleOnChange}
                label="Nama"
                error={errors.nama}
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
