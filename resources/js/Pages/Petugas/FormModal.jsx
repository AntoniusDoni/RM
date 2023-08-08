import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";
import SelectInputRuang from "../Ruangan/SelectionInput";
import { isEmpty } from "lodash";

export default function FormModal(props) {
    const { modalState } = props;
    const { data, setData, post, put, processing, errors, reset, clearErrors } =
        useForm({
            nama: "",
            kode_petugas: "",
            kode_ruang:"",
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
        const petugas = modalState.data;
        if (petugas !== null) {
            put(route("petugas.update", petugas), {
                onSuccess: () => handleClose(),
            });
            return;
        }
        post(route("petugas.store"), {
            onSuccess: () => handleClose(),
        });
    };

    useEffect(() => {
        const petugas = modalState.data;
        if (isEmpty(petugas) === false) {
            setData({
                nama: petugas.nama,
                kode_petugas: petugas.kode_petugas,
                kode_ruang:petugas.kd_ruang,
            });
            return;
        }
    }, [modalState]);

    return (
        <Modal isOpen={modalState.isOpen} toggle={handleClose} title={"petugasan"}>
            <FormInput
                name="kode_petugas"
                value={data.kode_petugas}
                onChange={handleOnChange}
                label="Kode petugas"
                error={errors.kode_petugas}
            />
            <FormInput
                name="nama"
                value={data.nama}
                onChange={handleOnChange}
                label="Nama"
                error={errors.nama}
            />
            <SelectInputRuang
                 label="Ruangan"
                 itemSelected={data.kode_ruang}
                 onItemSelected={(id) => setData('kode_ruang', id)}
                 error={errors.kode_ruang}
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
