import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import SelectInputRuang from "../Ruangan/SelectionInput";
import SelectInputPasien from "../Pasien/SelectionInput";
import SelectInputPetugas from "../Petugas/SelectionInput";
import { isEmpty } from "lodash";
import FormInputDate from "@/Components/FormInputDate";

export default function FormModalPeminjaman(props) {
    const { modalState } = props;
    const { data, setData, post, put, processing, errors, reset, clearErrors } =
        useForm({
            no_rm: null,
            kode_petugas: null,
            kode_ruang: null,
            tgl_pinjam: new Date(),
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
                kode_ruang: petugas.kd_ruang,
            });
            return;
        }
    }, [modalState]);

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Pemijaman"}
        >
            <SelectInputPasien
                label="No RM Pasien"
                itemSelected={data.no_rm}
                onItemSelected={(id) => setData("no_rm", id)}
                error={errors.no_rm}
            />
            <SelectInputRuang
                label="Ruang"
                itemSelected={data.kode_ruang}
                onItemSelected={(id) => setData("kode_ruang", id)}
                error={errors.kode_ruang}
            />
            <SelectInputPetugas
                label="Petugas"
                itemSelected={data.kode_petugas}
                onItemSelected={(id) => setData("kode_petugas", id)}
                error={errors.kode_petugas}
                disabled={data.kode_ruang==null?true:false}
                kode_ruang={data.kode_ruang}
            />
            <FormInputDate
                name="tgl_pinjam"
                selected={data.tgl_pinjam}
                onChange={(date) => setData("tgl_pinjam", date)}
                label="Tanggal Pinjam"
                error={errors.tgl_pinjam}
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
